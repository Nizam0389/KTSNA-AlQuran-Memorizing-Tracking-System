<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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

// Set a fallback value for username
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : 'Unknown User';

// Fetch students who do not have a record in the memorizing_record table
$sql = "SELECT student.student_id, student.student_name, class.class_name 
        FROM student 
        LEFT JOIN memorizing_record ON student.student_id = memorizing_record.student_id 
        LEFT JOIN class ON student.class_id = class.class_id
        WHERE memorizing_record.student_id IS NULL";

$students = [];
if ($result = mysqli_query($dbCon, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTSNA Al Quran Memorizing Tracking System - New Record</title>
    <link rel="stylesheet" href="css/studDash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .back-button {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #45a049;
        }
        .create-button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .create-button:hover {
            background-color: #0056b3;
        }
    </style>
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
            <a href="uRecord.php" class="back-button">Back to Record</a>
            <h2>Students Without Memorizing Record</h2>
            <?php if (!empty($students)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                                <td><a href="createRecord.php?student_id=<?php echo $student['student_id']; ?>" class="create-button">Create</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No students found without a memorizing record.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>
