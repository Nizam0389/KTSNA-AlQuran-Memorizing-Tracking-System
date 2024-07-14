<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the new external PHP file

// Fetch memorizing records for the logged-in student
$student_id = $_SESSION["id"];
$sql = "SELECT memo_id, page, juzu, surah, date, session, status, staff_id FROM memorizing_record WHERE student_id = ?";
        
$records = [];
if ($stmt = mysqli_prepare($dbCon, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $memo_id, $page, $juzu, $surah, $date, $session, $status, $staff_id);
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
                'staff_id' => $staff_id
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
    <title>Student Progress - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/studProgress.css">
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
                    window.location.href = "logout.php";
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
                <li><button class="menu-btn" onclick="location.href='studDash.php'"><i class="fas fa-tachometer-alt"></i>Dashboard</button></li>
                <li><button class="menu-btn" onclick="location.href='studProgress.php'"><i class="fas fa-chart-line"></i>Progress</button></li>
                <li><button class="menu-btn" onclick="location.href='studProgReport.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
            </header>
            <div class="section">
                <h2>Your Memorizing Progress</h2>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                        <div class="record">
                            <div class="record-item"><strong>Memo ID:</strong> <?php echo htmlspecialchars($record['memo_id']); ?></div>
                            <div class="record-item"><strong>Page:</strong> <?php echo htmlspecialchars($record['page']); ?></div>
                            <div class="record-item"><strong>Juzu:</strong> <?php echo htmlspecialchars($record['juzu']); ?></div>
                            <div class="record-item"><strong>Surah:</strong> <?php echo htmlspecialchars($record['surah']); ?> - <?php echo htmlspecialchars($record['surah_name']); ?></div>
                            <div class="record-item"><strong>Date:</strong> <?php echo htmlspecialchars($record['date']); ?></div>
                            <div class="record-item"><strong>Session:</strong> <?php echo htmlspecialchars($record['session']); ?></div>
                            <div class="record-item"><strong>Status:</strong> <?php echo htmlspecialchars($record['status']); ?></div>
                            <div class="record-item"><strong>Staff ID:</strong> <?php echo htmlspecialchars($record['staff_id']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No records found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
