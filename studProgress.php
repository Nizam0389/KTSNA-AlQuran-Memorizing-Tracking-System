<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Set the timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Fetch student details
$student_id = $_SESSION["id"];
$student_sql = "SELECT student_name, student.class_id, class.class_name, class.year FROM student 
                INNER JOIN class ON student.class_id = class.class_id 
                WHERE student.student_id = ?";
        
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_name, $class_id, $class_name, $year);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch the latest memorizing records for the logged-in student
$latest_records_sql = "SELECT mr.memo_id, mh.page, mh.juzu, mh.surah, mh.date, mh.time, mh.session, mh.status, s.staff_name 
                       FROM memorizing_record mr
                       INNER JOIN memorizing_history mh ON mr.memo_id = mh.memo_id
                       INNER JOIN staff s ON mr.staff_id = s.staff_id
                       WHERE mr.student_id = ? AND (mh.date, mh.time) = (
                           SELECT mh2.date, mh2.time FROM memorizing_history mh2 WHERE mh2.memo_id = mr.memo_id
                           ORDER BY mh2.date DESC, mh2.time DESC LIMIT 1
                       )";
        
$latest_records = [];
if ($stmt = mysqli_prepare($dbCon, $latest_records_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $memo_id, $page, $juzu, $surah, $date, $time, $session, $status, $staff_name);
        while (mysqli_stmt_fetch($stmt)) {
            $surah_name = getSurahName($surah);
            $session_desc = getSessionDescription($session);
            $status_desc = getStatusDescription($status);
            $latest_records[] = [
                'memo_id' => $memo_id ?? '',
                'page' => $page ?? '',
                'juzu' => $juzu ?? '',
                'surah' => $surah ?? '',
                'surah_name' => $surah_name ?? '',
                'date' => $date ?? '',
                'time' => $time ?? '',
                'session' => $session_desc ?? '',
                'status' => $status_desc ?? '',
                'staff_name' => $staff_name ?? ''
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Pagination setup
$rowsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

// Fetch memorizing history for the logged-in student
$history_sql = "SELECT mh.page, mh.juzu, mh.surah, mh.date, mh.time, mh.session, mh.status, mr.memo_id 
                FROM memorizing_history mh
                INNER JOIN memorizing_record mr ON mh.memo_id = mr.memo_id
                WHERE mr.student_id = ?
                ORDER BY mh.date DESC, mh.time DESC
                LIMIT ? OFFSET ?";
        
$history_records = [];
if ($stmt = mysqli_prepare($dbCon, $history_sql)) {
    mysqli_stmt_bind_param($stmt, "sii", $student_id, $rowsPerPage, $offset);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $page, $juzu, $surah, $date, $time, $session, $status, $memo_id);
        $no = $offset + 1;
        while (mysqli_stmt_fetch($stmt)) {
            $surah_name = getSurahName($surah);
            $session_desc = getSessionDescription($session);
            $status_desc = getStatusDescription($status);
            $history_records[] = [
                'no' => $no++,
                'page' => $page ?? '',
                'juzu' => $juzu ?? '',
                'surah' => $surah ?? '',
                'surah_name' => $surah_name ?? '',
                'date' => $date ?? '',
                'time' => $time ?? '',
                'session' => $session_desc ?? '',
                'status' => $status_desc ?? '',
                'memo_id' => $memo_id ?? ''
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Get total number of records for pagination
$total_records_sql = "SELECT COUNT(*) FROM memorizing_history mh
                      INNER JOIN memorizing_record mr ON mh.memo_id = mr.memo_id
                      WHERE mr.student_id = ?";
$total_records = 0;
if ($stmt = mysqli_prepare($dbCon, $total_records_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $total_records);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

$totalPages = ceil($total_records / $rowsPerPage);
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
                <h2>Your Latest Memorizing Progress</h2>
                <?php if (!empty($latest_records)): ?>
                    <?php foreach ($latest_records as $record): ?>
                        <div class="record">
                            <div class="record-item"><strong>Memo ID:</strong> <?php echo htmlspecialchars($record['memo_id']); ?></div>
                            <div class="record-item"><strong>Page:</strong> <?php echo htmlspecialchars($record['page']); ?></div>
                            <div class="record-item"><strong>Juzu:</strong> <?php echo htmlspecialchars($record['juzu']); ?></div>
                            <div class="record-item"><strong>Surah:</strong> <?php echo htmlspecialchars($record['surah']); ?> - <?php echo htmlspecialchars($record['surah_name']); ?></div>
                            <div class="record-item"><strong>Date:</strong> <?php echo htmlspecialchars($record['date']); ?></div>
                            <div class="record-item"><strong>Time:</strong> <?php echo htmlspecialchars($record['time']); ?></div>
                            <div class="record-item"><strong>Session:</strong> <?php echo htmlspecialchars($record['session']); ?></div>
                            <div class="record-item"><strong>Status:</strong> <?php echo htmlspecialchars($record['status']); ?></div>
                            <div class="record-item"><strong>Ustaz Name:</strong> <?php echo htmlspecialchars($record['staff_name']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No records found</p>
                <?php endif; ?>
            </div>
            <div class="section">
                <h2>Your Memorizing History</h2>
                <?php if (!empty($history_records)): ?>
                    <div class="history-table-container">
                        <table class="history-table">
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
                                <?php foreach ($history_records as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['no']); ?></td>
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
                    </div>
                    <div class="pagination">
                        <!-- <?php if ($page == 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                        <?php endif; ?> -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <!-- <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                        <?php endif; ?> -->
                    </div>
                <?php else: ?>
                    <p>No history records found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
