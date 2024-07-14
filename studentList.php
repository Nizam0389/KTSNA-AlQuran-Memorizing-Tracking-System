<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Fetch staff details from the database
$staff_id = $_SESSION["id"];
$sql = "SELECT staff_name, staff_username FROM staff WHERE staff_id = ?";

if ($stmt = mysqli_prepare($dbCon, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $staff_name, $staff_username);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Pagination setup
$rowsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

// Fetch students associated with the logged-in staff from memorizing_record table
$student_sql = "SELECT DISTINCT student.student_id, student.student_name, class.class_name, class.year, 
                (SELECT page FROM memorizing_record WHERE student_id = student.student_id AND staff_id = ? ORDER BY date DESC LIMIT 1) as latest_page,
                (SELECT status FROM memorizing_record WHERE student_id = student.student_id AND staff_id = ? ORDER BY date DESC LIMIT 1) as latest_status
                FROM memorizing_record 
                INNER JOIN student ON memorizing_record.student_id = student.student_id 
                INNER JOIN class ON student.class_id = class.class_id 
                WHERE memorizing_record.staff_id = ?
                LIMIT ? OFFSET ?";

$students = [];
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "sssii", $staff_id, $staff_id, $staff_id, $rowsPerPage, $offset);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_id, $student_name, $class_name, $year, $page, $status);
        while (mysqli_stmt_fetch($stmt)) {
            $students[] = [
                'student_id' => $student_id,
                'student_name' => $student_name,
                'class_name' => $class_name,
                'year' => $year,
                'page' => $page,
                'status' => getStatusDescription($status)
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Get total number of students for pagination
$total_students_sql = "SELECT COUNT(DISTINCT student.student_id) 
                       FROM memorizing_record 
                       INNER JOIN student ON memorizing_record.student_id = student.student_id 
                       WHERE memorizing_record.staff_id = ?";
$total_students = 0;
if ($stmt = mysqli_prepare($dbCon, $total_students_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $total_students);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

$totalPages = max(1, ceil($total_students / $rowsPerPage));
$page = min(max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1), $totalPages);
$offset = ($page - 1) * $rowsPerPage;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
        }

        .dashboard-container {
            display: flex;
            height: 100%;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .sidebar .menu li {
            width: 100%;
        }

        .sidebar .menu .menu-btn {
            width: 100%;
            padding: 20px 30px;
            font-size: 18px;
            border: none;
            background: none;
            color: #fff;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s, padding-left 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar .menu .menu-btn:hover {
            background-color: #34495e;
            padding-left: 40px;
        }

        .sidebar .menu .menu-btn i {
            margin-right: 15px;
            font-size: 24px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        header h1 {
            font-size: 24px;
            color: #333;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info span {
            font-size: 18px;
            font-weight: bold;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .search-container input, .search-container select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            flex: 1;
        }

        .search-container input {
            flex: 2;
        }

        .student-list {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        th.student-id, td.student-id {
            width: 10%;
        }

        th.name, td.name {
            width: 25%;
        }

        th.year, td.year {
            width: 10%;
        }

        th.class, td.class {
            width: 20%;
        }

        th.page, td.page {
            width: 10%;
        }

        th.status, td.status {
            width: 15%;
        }

        th.action, td.action {
            width: 10%;
        }

        .action-btn {
            padding: 5px 10px;
            font-size: 14px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #45a049;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 5px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            cursor: pointer;
            border-radius: 5px;
        }

        .pagination button:disabled {
            cursor: not-allowed;
            background-color: #e0e0e0;
        }

        .pagination span {
            font-size: 16px;
        }
    </style>
    <script>
        function logout() {
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out of the system.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logging Out!',
                        text: 'You are being logged out.',
                        icon: 'success',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    setTimeout(() => {
                        window.location.href = 'logout.php';
                    }, 1000);
                }
            });
        }

        function searchStudent() {
            let input = document.getElementById('searchInput').value.toUpperCase();
            let table = document.getElementById('studentTable');
            let tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td')[1];
                if (td) {
                    let textValue = td.textContent || td.innerText;
                    if (textValue.toUpperCase().indexOf(input) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function changePage(page) {
            window.location.href = `studentList.php?page=${page}`;
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="profile">
                <img src="image/ktsna logo.png" alt="Profile Icon">
            </div>
            <ul class="menu">
                <li><button class="menu-btn" onclick="location.href='ustazDash.php'"><i class="fas fa-tachometer-alt"></i>Dashboard</button></li>
                <li><button class="menu-btn" onclick="location.href='uRecord.php'"><i class="fas fa-clipboard-list"></i>Record</button></li>
                <li><button class="menu-btn" onclick="location.href='ustazReportHome.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($staff_username); ?></span>
                </div>
            </header>
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchStudent()" placeholder="Search for students..">
            </div>
            <div class="student-list">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th class="student-id">Student ID</th>
                            <th class="name">Name</th>
                            <th class="year">Year</th>
                            <th class="class">Class</th>
                            <th class="page">Latest Page</th>
                            <th class="status">Latest Status</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="student-id"><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td class="name"><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td class="year"><?php echo htmlspecialchars($student['year']); ?></td>
                                <td class="class"><?php echo htmlspecialchars($student['class_name']); ?></td>
                                <td class="page"><?php echo htmlspecialchars($student['page']); ?></td>
                                <td class="status"><?php echo htmlspecialchars($student['status']); ?></td>
                                <td class="action">
                                    <a href="updStud.php?student_id=<?php echo $student['student_id']; ?>" class="action-btn">Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <button onclick="changePage(<?php echo max(1, $page - 1); ?>)" <?php echo $page == 1 ? 'disabled' : ''; ?>>Previous</button>
                <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                <button onclick="changePage(<?php echo min($totalPages, $page + 1); ?>)" <?php echo $page == $totalPages ? 'disabled' : ''; ?>>Next</button>
            </div>
        </div>
    </div>
</body>
</html>