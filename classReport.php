<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'ustaz') {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php";

$staff_id = $_SESSION["id"];
$staff_name = $staff_username = '';
$sql = "SELECT staff_name, staff_username FROM staff WHERE staff_id = ?";
if ($stmt = mysqli_prepare($dbCon, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $staff_name, $staff_username);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

$classes = [];
$class_sql = "SELECT class_id, CONCAT(year, ' ', class_name) AS class_full_name FROM class ORDER BY year, class_name";
if ($stmt = mysqli_prepare($dbCon, $class_sql)) {
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $class_id, $class_full_name);
        while (mysqli_stmt_fetch($stmt)) {
            $classes[] = ['class_id' => $class_id, 'class_full_name' => $class_full_name];
        }
    }
    mysqli_stmt_close($stmt);
}

$class_id = $class_name_full = '';
$students = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class'])) {
    $class_id = $_POST['class'];
    $student_sql = "SELECT s.student_id, s.student_name, c.class_name, c.year, mr.page, mr.juzu, mr.surah, mr.status 
                    FROM memorizing_record mr
                    INNER JOIN student s ON mr.student_id = s.student_id 
                    INNER JOIN class c ON s.class_id = c.class_id 
                    WHERE s.class_id = ?
                    ORDER BY mr.page DESC";

    if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
        mysqli_stmt_bind_param($stmt, "s", $class_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $student_id, $student_name, $class_name, $year, $page, $juzu, $surah, $status);
            while (mysqli_stmt_fetch($stmt)) {
                $students[] = [
                    'student_id' => $student_id,
                    'student_name' => $student_name,
                    'class_name' => $class_name,
                    'year' => $year,
                    'page' => $page,
                    'juzu' => $juzu,
                    'surah' => getSurahName($surah),
                    'status' => $status == 'p' ? 'Pass' : 'Not Pass'
                ];
            }
            $class_name_full = $year . ' ' . $class_name;
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($dbCon);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Report - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/classReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
            <div class="report-form">
                <form method="post" action="classReport.php">
                    <div class="form-group">
                        <label for="class">Select Class:</label>
                        <select id="class" name="class" required>
                            <option value="">--Select Class--</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo htmlspecialchars($class['class_id']); ?>" <?php echo $class['class_id'] == $class_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($class['class_full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Confirm</button>
                </form>
            </div>
            <?php if (!empty($students)): ?>
                <div class="report-container">
                    <header>
                        <div class="header-content">
                            <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                            <h2>Class Report for <?php echo htmlspecialchars($class_name_full); ?></h2>
                        </div>
                    </header>
                    <section class="memorizing-records">
                        <h3>Student Memorizing Records</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Page</th>
                                    <th>Juzu</th>
                                    <th>Surah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['page']); ?></td>
                                        <td><?php echo htmlspecialchars($student['juzu']); ?></td>
                                        <td><?php echo htmlspecialchars($student['surah']); ?></td>
                                        <td><?php echo htmlspecialchars($student['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                    <div class="print-button-container">
                        <button onclick="window.open('classReportPrint.php?class_id=<?php echo $class_id; ?>&class_name_full=<?php echo urlencode($class_name_full); ?>', '_blank')">Generate Report</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>