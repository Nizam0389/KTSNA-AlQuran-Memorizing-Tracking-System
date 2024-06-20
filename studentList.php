<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";

// Fetch the logged-in staff's ID
$staff_id = $_SESSION["id"];

// Fetch distinct students associated with the logged-in staff from memorizing_record table
$student_sql = "SELECT student.student_id, student.student_name, class.class_name, memorizing_record.page, memorizing_record.status 
                FROM memorizing_record 
                INNER JOIN student ON memorizing_record.student_id = student.student_id 
                INNER JOIN class ON student.class_id = class.class_id 
                WHERE memorizing_record.staff_id = ?";

$students = [];
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_id, $student_name, $class_name, $page, $status);
        while (mysqli_stmt_fetch($stmt)) {
            $students[] = ['student_id' => $student_id, 'student_name' => $student_name, 'class_name' => $class_name, 'page' => $page, 'status' => $status];
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all classes
$class_sql = "SELECT class_id, class_name FROM class";
$classes = [];
if ($stmt = mysqli_prepare($dbCon, $class_sql)) {
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $class_id, $class_name);
        while (mysqli_stmt_fetch($stmt)) {
            $classes[] = ['class_id' => $class_id, 'class_name' => $class_name];
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/studentList.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
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

        function filterClass() {
            let filter = document.getElementById('classFilter').value;
            let table = document.getElementById('studentTable');
            let tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td')[2];
                if (td) {
                    let textValue = td.textContent || td.innerText;
                    if (filter === "all" || textValue === filter) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
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
                <li><button class="menu-btn" onclick="location.href='studentList.php'"><i class="fas fa-users"></i>Student List</button></li>
                <li><button class="menu-btn" onclick="location.href='ustazReport.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="location.href='index.html'"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Unknown'; ?></span>
                    <span><?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Unknown'; ?></span>
                </div>
            </header>
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchStudent()" placeholder="Search for students..">
                <select id="classFilter" onchange="filterClass()">
                    <option value="all">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['class_name']); ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="student-list">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Page</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['page']); ?></td>
                                <td><?php echo htmlspecialchars($student['status']); ?></td>
                                <td><button onclick="location.href='updStud.php?student_id=<?php echo $student['student_id']; ?>'">Update</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
