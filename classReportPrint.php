<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php";

if (!isset($_GET['class_id']) || !isset($_GET['class_name_full'])) {
    header("location: classReport.php");
    exit;
}

$class_id = $_GET['class_id'];
$class_name_full = urldecode($_GET['class_name_full']);

// Fetch students for the selected class
$student_sql = "SELECT s.student_id, s.student_name, c.class_name, c.year, mh.page, mh.juzu, mh.surah, mh.status 
                FROM student s
                INNER JOIN class c ON s.class_id = c.class_id
                INNER JOIN memorizing_record mr ON s.student_id = mr.student_id
                INNER JOIN memorizing_history mh ON mr.memo_id = mh.memo_id
                WHERE s.class_id = ? AND mh.memoHistory_id = (
                    SELECT mh2.memoHistory_id 
                    FROM memorizing_history mh2
                    WHERE mh2.memo_id = mh.memo_id
                    ORDER BY mh2.date DESC, mh2.time DESC
                    LIMIT 1
                )
                ORDER BY s.student_id";

$students = [];
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
                'status' => getStatusDescription($status)
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
    <title>Class Report Print - KTSNA Al Quran Memorizing Tracking System</title>
    <style>
        /* A4 layout styling */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 1cm;
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            box-sizing: border-box;
        }
        .report-container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            width: 100%;
            height: 100%;
            max-width: 21cm;
            max-height: 29.7cm;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            overflow: hidden;
        }
        .header-content {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-content h1, .header-content h2 {
            margin: 10px 0;
        }
        .memorizing-records {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .print-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .print-button-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-button-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <header>
            <div class="header-content">
                <img src="image/ktsna logo.png" alt="KTSNA Logo">
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
            <button onclick="window.print()">Print Report</button>
        </div>
    </div>
</body>
</html>
