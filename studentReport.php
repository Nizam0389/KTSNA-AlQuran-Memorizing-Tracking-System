<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Fetch student details
$student_id = $_SESSION["id"];
$student_sql = "SELECT student_name, class.class_name, class.year FROM student 
                INNER JOIN class ON student.class_id = class.class_id 
                WHERE student.student_id = ?";
        
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_name, $class_name, $year);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch memorizing records for the logged-in student
$records_sql = "SELECT memo_id, page, juzu, surah, date, session, status, staff.staff_name 
                FROM memorizing_record 
                INNER JOIN staff ON memorizing_record.staff_id = staff.staff_id 
                WHERE memorizing_record.student_id = ?";
        
$records = [];
if ($stmt = mysqli_prepare($dbCon, $records_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $memo_id, $page, $juzu, $surah, $date, $session, $status, $staff_name);
        while (mysqli_stmt_fetch($stmt)) {
            $surah_name = getSurahName($surah);
            $calculated_juzu = calculateJuzu($page);
            $session_desc = getSessionDescription($session);
            $status_desc = getStatusDescription($status);
            $records[] = [
                'memo_id' => $memo_id,
                'page' => $page,
                'juzu' => $calculated_juzu,
                'surah' => $surah,
                'surah_name' => $surah_name,
                'date' => $date,
                'session' => $session_desc,
                'status' => $status_desc,
                'staff_name' => $staff_name
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
    <link rel="stylesheet" href="css/studentReport.css">
</head>
<body>
    <div class="report-container">
        <header>
            <div class="header-content">
                <img src="image/ktsna logo.png" alt="KTSNA Logo">
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <h2>Student Progress Report</h2>
            </div>
        </header>
        <section class="student-info">
            <h3>Student Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($class_name); ?></p>
            <p><strong>Year:</strong> <?php echo htmlspecialchars($year); ?>th Year</p>
        </section>
        <section class="memorizing-records">
            <h3>Memorizing Records</h3>
            <?php if (!empty($records)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Memo ID</th>
                            <th>Page</th>
                            <th>Juzu</th>
                            <th>Surah</th>
                            <th>Date</th>
                            <th>Session</th>
                            <th>Status</th>
                            <th>Staff Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['memo_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['page']); ?></td>
                                <td><?php echo htmlspecialchars($record['juzu']); ?></td>
                                <td><?php echo htmlspecialchars($record['surah']); ?> - <?php echo htmlspecialchars($record['surah_name']); ?></td>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['session']); ?></td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                                <td><?php echo htmlspecialchars($record['staff_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found</p>
            <?php endif; ?>
        </section>
        <div class="print-button-container">
            <button onclick="window.print()">Print Report</button>
        </div>
    </div>
</body>
</html>
