<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";

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

// Process form submission
$class_id = $year = '';
$students = [];
$class_name_full = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class'];
    $year = $_POST['year'];

    // Fetch students for the selected class and year
    $student_sql = "SELECT student.student_id, student.student_name, class.class_name, class.year, memorizing_record.page, memorizing_record.status 
                    FROM memorizing_record 
                    INNER JOIN student ON memorizing_record.student_id = student.student_id 
                    INNER JOIN class ON student.class_id = class.class_id 
                    WHERE student.class_id = ? AND class.year = ?";

    if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
        mysqli_stmt_bind_param($stmt, "si", $class_id, $year);
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
            $class_name_full = $year . ' ' . $class_name;
            mysqli_stmt_close($stmt);
        }
    }
}

function getStatusDescription($status) {
    return $status == 'p' ? 'Pass' : 'Not Pass';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Report - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/classReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><button class="menu-btn" onclick="location.href='index.php'"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($staff_username); ?></span>
                </div>
            </header>
            <div class="report-form">
                <form method="post" action="classReport.php">
                    <div class="form-group">
                        <label for="year">Select Year:</label>
                        <select id="year" name="year" required>
                            <option value="">--Select Year--</option>
                            <?php foreach ($years as $year_option): ?>
                                <option value="<?php echo htmlspecialchars($year_option); ?>" <?php echo $year_option == $year ? 'selected' : ''; ?>><?php echo htmlspecialchars($year_option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class">Select Class:</label>
                        <select id="class" name="class" required>
                            <option value="">--Select Class--</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo htmlspecialchars($class['class_id']); ?>" <?php echo $class['class_id'] == $class_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($class['class_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Confirm</button>
                </form>
            </div>
            <?php if (!empty($students)): ?>
                <div class="report-container">
                    <header>
                        <!-- <div class="header-content">
                            <img src="image/ktsna logo.png" alt="KTSNA Logo">
                            <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                            <h2>Class Report for <?php echo htmlspecialchars($class_name_full); ?></h2>
                        </div> -->
                    </header>
                    <section class="memorizing-records">
                        <h3>Student Memorizing Records</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Page</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['page']); ?></td>
                                        <td><?php echo htmlspecialchars($student['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                    <div class="print-button-container">
                        <button onclick="window.open('classReportPrint.php?class_id=<?php echo $class_id; ?>&year=<?php echo $year; ?>', '_blank')">Generate Report</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
