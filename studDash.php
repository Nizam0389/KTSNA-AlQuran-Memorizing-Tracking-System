<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";

// Fetch student details from the database
$student_id = $_SESSION["id"];
$sql = "SELECT student_name, student_username, class.year FROM student 
        INNER JOIN class ON student.class_id = class.class_id 
        WHERE student.student_id = ?";
        
if ($stmt = mysqli_prepare($dbCon, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_name, $student_username, $year);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/studDash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="profile">
                <img src="image/ktsna logo.png" alt="Profile Icon">
            </div>
            <ul class="menu">
                <li><button class="menu-btn" onclick="location.href='studDash.php'"><i class="fas fa-tachometer-alt"></i>Dashboard</button></li>
                <li><button class="menu-btn" onclick="location.href='studProgress.php'"><i class="fas fa-chart-line"></i>Progress</button></li>
                <li><button class="menu-btn" onclick="location.href='studProgReport.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="location.href='index.html'"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($student_username); ?></span>
                    <span><?php echo htmlspecialchars($year); ?>th year</span>
                </div>
            </header>
            <div class="welcome-message">
                <h1>Welcome back, <?php echo htmlspecialchars($student_name); ?>!</h1>
                <p>Always stay updated in your student portal</p>
            </div>
            <div class="section">
                <h2>Progress</h2>
                <div class="cards-container">
                    <a href="#" class="card">
                        <h2>Al Quran Memorizing Status</h2>
                    </a>
                </div>
            </div>
            <div class="section">
                <h2>Report</h2>
                <div class="cards-container">
                    <a href="#" class="card">
                        <h3>Progress Report</h3>
                        <button>View</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>
