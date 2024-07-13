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

// Fetch all students
$student_sql = "SELECT student.student_id, student.student_name, class.class_name, class.year 
                FROM student 
                INNER JOIN class ON student.class_id = class.class_id";

$students = [];
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_id, $student_name, $class_name, $year);
        while (mysqli_stmt_fetch($stmt)) {
            $students[] = [
                'student_id' => $student_id,
                'student_name' => $student_name,
                'class_name' => $class_name,
                'year' => $year
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all classes and years
$class_sql = "SELECT DISTINCT class_id, class_name, year FROM class ORDER BY year, class_name";
$classes = [];
$years = [];
if ($stmt = mysqli_prepare($dbCon, $class_sql)) {
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $class_id, $class_name, $year);
        while (mysqli_stmt_fetch($stmt)) {
            $classes[] = ['class_id' => $class_id, 'class_name' => $class_name, 'year' => $year];
            if (!in_array($year, $years)) {
                $years[] = $year;
            }
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
    <title>Individual Report - KTSNA Al Quran Memorizing Tracking System</title>
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
            let yearFilter = document.getElementById('yearFilter').value;
            let classFilter = document.getElementById('classFilter').value;
            let table = document.getElementById('studentTable');
            let tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                let yearTd = tr[i].getElementsByTagName('td')[2];
                let classTd = tr[i].getElementsByTagName('td')[3];
                if (yearTd && classTd) {
                    let yearTextValue = yearTd.textContent || yearTd.innerText;
                    let classTextValue = classTd.textContent || classTd.innerText;
                    if ((yearFilter === "all" || yearTextValue === yearFilter) && (classFilter === "all" || classTextValue === classFilter)) {
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
                <li><button class="menu-btn" onclick="location.href='uRecord.php'"><i class="fas fa-clipboard-list"></i>Record</button></li>
                <li><button class="menu-btn" onclick="location.href='ustazReportHome.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="location.href='index.html'"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
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
                <select id="yearFilter" onchange="filterClass()">
                    <option value="all">All Years</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                    <?php endforeach; ?>
                </select>
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
                            <th class="student-id">Student ID</th>
                            <th class="name">Name</th>
                            <th class="year">Year</th>
                            <th class="class">Class</th>
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
                                <td class="action"><button onclick="location.href='studentReport.php?student_id=<?php echo $student['student_id']; ?>'">Generate Report</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
