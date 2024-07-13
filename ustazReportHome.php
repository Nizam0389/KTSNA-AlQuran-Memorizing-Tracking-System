<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'ustaz') {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/ustazDash.css">
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
            <div class="section">
                <h2>Reports</h2>
                <div class="cards-container">
                    <a href="individualReport.php" class="card">
                        <h2>Individual Report</h2>
                    </a>
                    <a href="classReport.php" class="card">
                        <h2>Class Report</h2>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>
