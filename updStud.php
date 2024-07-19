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

// Fetch the latest memorizing record and its history
$record_sql = "SELECT memorizing_record.memo_id, memorizing_history.page, memorizing_history.status, memorizing_history.juzu, memorizing_history.surah, memorizing_history.date, memorizing_history.time, memorizing_history.session, student.student_name 
               FROM memorizing_history 
               INNER JOIN memorizing_record ON memorizing_history.memo_id = memorizing_record.memo_id
               INNER JOIN student ON memorizing_record.student_id = student.student_id 
               WHERE student.student_id = ?
               ORDER BY memorizing_history.date DESC, memorizing_history.time DESC
               LIMIT 1";

$records = [];
$student_name = "";
if ($stmt = mysqli_prepare($dbCon, $record_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $memo_id, $page, $status, $juzu, $surah, $date, $time, $session, $student_name);
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
                'time' => $time,
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
    $memo_id = $_POST['memo_id'];

    // Validate page input
    if ($new_page < 1 || $new_page > 604) {
        echo "<script>alert('Page value must be between 1 and 604.');</script>";
    } else {
        $new_juzu = calculateJuzu($new_page);
        $new_surah = calculateSurah($new_page);
        $new_date = date('Y-m-d');
        $new_time = date('H:i:s');
        $new_session = getSessionByTime($new_time);

        $insert_sql = "INSERT INTO memorizing_history (memo_id, page, juzu, surah, date, time, status, session) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($dbCon, $insert_sql)) {
            mysqli_stmt_bind_param($stmt, "siisssss", $memo_id, $new_page, $new_juzu, $new_surah, $new_date, $new_time, $new_status, $new_session);
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        select#status {
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

        function confirmUpdate(event) {
            event.preventDefault();
            Swal.fire({
                title: "Do you want to save the changes?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Saved!',
                        text: 'Your changes have been saved.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        willClose: () => {
                            event.target.submit();
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                }
            });
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
                <li><button class="menu-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Unknown'; ?></span>
                </div>
            </header>
            
            <div class="content-container">
                <button onclick="location.href='studentList.php'" style="margin-bottom: 20px;">Back to Student List</button>
                <h2>Update Memorizing Record for <?php echo htmlspecialchars($student_name); ?></h2>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                        <div class="record">
                            <form method="post" action="updStud.php?student_id=<?php echo $student_id; ?>" onsubmit="return confirmUpdate(event) && validatePageInput();">
                                <input type="hidden" name="memo_id" value="<?php echo $record['memo_id']; ?>">
                                <label for="page">Page:</label>
                                <input type="number" id="page" name="page" value="<?php echo htmlspecialchars($record['page']); ?>" min="1" max="604" required>
                                
                                <label for="status">Status:</label>
                                <select id="status" name="status">
                                    <option value="p" <?php if ($record['status'] == 'p') echo 'selected'; ?>>Pass</option>
                                    <option value="f" <?php if ($record['status'] == 'f') echo 'selected'; ?>>Not Pass</option>
                                </select>

                                <label for="session">Session:</label>
                                <input type="text" id="session" name="session" value="<?php echo htmlspecialchars($record['session']); ?>" readonly>

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
