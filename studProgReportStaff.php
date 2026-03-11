<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Ensure a student ID is provided
if (!isset($_GET['student_id'])) {
    header("location: individualReport.php");
    exit;
}

$student_id = $_GET['student_id'];

$student_sql = "SELECT student_name, student.class_id, class.class_name, class.year, staff.staff_name FROM student 
                INNER JOIN class ON student.class_id = class.class_id 
                INNER JOIN memorizing_record ON student.student_id = memorizing_record.student_id
                INNER JOIN staff ON memorizing_record.staff_id = staff.staff_id
                WHERE student.student_id = ?
                LIMIT 1"; // Limit to one record to get the Ustaz name

if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_name, $class_id, $class_name, $year, $ustaz_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch memorizing records for the specified student
$records_sql = "SELECT mh.page, mh.juzu, mh.surah, mh.date, mh.time, mh.session, mh.status
                FROM memorizing_history mh
                INNER JOIN memorizing_record mr ON mh.memo_id = mr.memo_id
                WHERE mr.student_id = ? ORDER BY mh.date DESC, mh.time DESC";

$records = [];
if ($stmt = mysqli_prepare($dbCon, $records_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $page, $juzu, $surah, $date, $time, $session, $status);
        while (mysqli_stmt_fetch($stmt)) {
            $surah_name = getSurahName($surah);
            $session_desc = getSessionDescription($session);
            $status_desc = getStatusDescription($status);
            $records[] = [
                'page' => $page,
                'juzu' => $juzu,
                'surah' => $surah,
                'surah_name' => $surah_name,
                'date' => $date,
                'time' => $time,
                'session' => $session_desc,
                'status' => $status_desc
            ];
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
    <title>Student Progress Report - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/studProgReport.css">
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
            </header>
            <div class="report-container">
                <header>
                    <div class="header-content">
                        <h2>Student Progress Report</h2>
                    </div>
                </header>
                <section class="student-info">
                    <h3>Student Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name ?? ''); ?></p>
                    <p><strong>Class:</strong> <?php echo htmlspecialchars($class_name ?? ''); ?></p>
                    <p><strong>Year:</strong> <?php echo htmlspecialchars($year ?? ''); ?>th Year</p>
                    <p><strong>Ustaz:</strong> <?php echo htmlspecialchars($ustaz_name ?? ''); ?></p>
                </section>
                <section class="memorizing-records">
                    <h3>Memorizing Records</h3>
                    <?php if (!empty($records)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Page</th>
                                    <th>Juzu</th>
                                    <th>Surah</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $index => $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($index + 1); ?></td>
                                        <td><?php echo htmlspecialchars($record['page']); ?></td>
                                        <td><?php echo htmlspecialchars($record['juzu']); ?></td>
                                        <td><?php echo htmlspecialchars($record['surah']); ?> - <?php echo htmlspecialchars($record['surah_name']); ?></td>
                                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['time']); ?></td>
                                        <td><?php echo htmlspecialchars($record['session']); ?></td>
                                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No records found</p>
                    <?php endif; ?>
                </section>
                <div class="print-button-container">
                    <button onclick="window.open('studentReportStaff.php?student_id=<?php echo $student_id; ?>', '_blank')">Print Report</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
