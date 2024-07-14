<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

// Check if student_id is set
if (!isset($_GET['student_id'])) {
    header("location: studentList.php");
    exit;
}

$student_id = $_GET['student_id'];

// Fetch student's memorizing record
$record_sql = "SELECT memorizing_record.memo_id, memorizing_record.page, memorizing_record.status, memorizing_record.juzu, memorizing_record.surah, memorizing_record.date, memorizing_record.session, student.student_name 
               FROM memorizing_record 
               INNER JOIN student ON memorizing_record.student_id = student.student_id 
               WHERE student.student_id = ?";

$records = [];
$student_name = "";
if ($stmt = mysqli_prepare($dbCon, $record_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $memo_id, $page, $status, $juzu, $surah, $date, $session, $student_name);
        while (mysqli_stmt_fetch($stmt)) {
            $surah_name = getSurahName($surah);
            $session_desc = getSessionDescription($session);
            $records[] = [
                'memo_id' => $memo_id,
                'page' => $page,
                'status' => $status,
                'juzu' => $juzu,
                'surah' => $surah_name,
                'date' => $date,
                'session' => $session_desc,
                'student_name' => $student_name
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle form submission for updating records
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_page = $_POST['page'];
    $new_status = $_POST['status'];
    $new_session = $_POST['session'];
    $memo_id = $_POST['memo_id'];

    // Validate page input
    if ($new_page < 1 || $new_page > 604) {
        echo "<script>alert('Page value must be between 1 and 604.');</script>";
    } else {
        $new_juzu = calculateJuzu($new_page);
        $new_surah = calculateSurah($new_page);
        $new_date = date('Y-m-d');

        $update_sql = "UPDATE memorizing_record SET page = ?, juzu = ?, surah = ?, date = ?, status = ?, session = ? WHERE memo_id = ?";
        if ($stmt = mysqli_prepare($dbCon, $update_sql)) {
            mysqli_stmt_bind_param($stmt, "iiissss", $new_page, $new_juzu, $new_surah, $new_date, $new_status, $new_session, $memo_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Record - KTSNA Al Quran Memorizing Tracking System</title>
    <link rel="stylesheet" href="css/updStud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        select#status, select#session {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
    </style>
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this record?");
        }

        function validatePageInput() {
            const pageInput = document.getElementById('page');
            if (pageInput.value < 1 || pageInput.value > 604) {
                alert("Page value must be between 1 and 604.");
                return false;
            }
            return true;
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
                <li><button class="menu-btn" onclick="location.href='ustazReport.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="location.href='index.php'"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <!-- <span><?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Unknown'; ?></span> -->
                    <span><?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Unknown'; ?></span>
                </div>
            </header>
            
            <div class="content-container">
                <button onclick="location.href='studentList.php'" style="margin-bottom: 20px;">Back to Student List</button>
                <h2>Update Memorizing Record for <?php echo htmlspecialchars($student_name); ?></h2>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                        <div class="record">
                            <form method="post" action="updStud.php?student_id=<?php echo $student_id; ?>" onsubmit="return confirmUpdate() && validatePageInput();">
                                <input type="hidden" name="memo_id" value="<?php echo $record['memo_id']; ?>">
                                <label for="page">Page:</label>
                                <input type="number" id="page" name="page" value="<?php echo htmlspecialchars($record['page']); ?>" min="1" max="604" required>
                                
                                <label for="status">Status:</label>
                                <select id="status" name="status">
                                    <option value="p" <?php if ($record['status'] == 'p') echo 'selected'; ?>>Pass</option>
                                    <option value="f" <?php if ($record['status'] == 'f') echo 'selected'; ?>>Not Pass</option>
                                </select>

                                <label for="session">Session:</label>
                                <select id="session" name="session">
                                    <option value="d" <?php if ($record['session'] == 'Day') echo 'selected'; ?>>Day</option>
                                    <option value="n" <?php if ($record['session'] == 'Night') echo 'selected'; ?>>Night</option>
                                </select>

                                <label for="juzu">Juzu:</label>
                                <input type="text" id="juzu" name="juzu" value="<?php echo htmlspecialchars($record['juzu']); ?>" readonly>

                                <label for="surah">Surah:</label>
                                <input type="text" id="surah" name="surah" value="<?php echo htmlspecialchars($record['surah']); ?>" readonly>

                                <label for="date">Date:</label>
                                <input type="text" id="date" name="date" value="<?php echo htmlspecialchars($record['date']); ?>" readonly>

                                <button type="submit">Update</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No records found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
