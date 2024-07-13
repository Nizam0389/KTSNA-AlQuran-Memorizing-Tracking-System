<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Ensure class ID and year are provided
if (!isset($_GET['class_id']) || !isset($_GET['year'])) {
    header("location: classReport.php");
    exit;
}

$class_id = $_GET['class_id'];
$year = $_GET['year'];

// Fetch students for the selected class and year
$student_sql = "SELECT student.student_id, student.student_name, class.class_name, class.year, memorizing_record.page, memorizing_record.status 
                FROM memorizing_record 
                INNER JOIN student ON memorizing_record.student_id = student.student_id 
                INNER JOIN class ON student.class_id = class.class_id 
                WHERE student.class_id = ? AND class.year = ?";

$students = [];
$class_name_full = '';
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Report Print - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/classReportPrint.css">
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
        .report-container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header-content {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-content img {
            width: 100px;
        }
        .header-content h1, .header-content h2 {
            margin: 10px 0;
        }
        .student-info, .memorizing-records {
            margin-bottom: 20px;
        }
        .student-info p, .memorizing-records p {
            margin: 5px 0;
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
            <button onclick="window.print()">Print Report</button>
        </div>
    </div>
</body>
</html>
