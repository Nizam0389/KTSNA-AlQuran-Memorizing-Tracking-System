<?php
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "dbConnect.php";
require_once "processDetails.php"; // Include the external PHP file

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

// Get the student_id from the query string
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

// Fetch student details
$student_sql = "SELECT student_name FROM student WHERE student_id = ?";
$student_name = "";
if ($stmt = mysqli_prepare($dbCon, $student_sql)) {
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $student_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch the latest memo_id and generate the next one
$latest_memo_id_sql = "SELECT memo_id FROM memorizing_record ORDER BY memo_id DESC LIMIT 1";
$latest_memo_id = 0;
if ($result = mysqli_query($dbCon, $latest_memo_id_sql)) {
    if ($row = mysqli_fetch_assoc($result)) {
        $latest_memo_id = intval($row['memo_id']);
    }
    mysqli_free_result($result);
}
$new_memo_id = str_pad($latest_memo_id + 1, 5, '0', STR_PAD_LEFT);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $page = $_POST['page'];
    $status = $_POST['status'];
    $session = $_POST['session'];
    $staff_id = $_SESSION["id"];
    $juzu = calculateJuzu($page);
    $surah = calculateSurah($page);
    $date = date('Y-m-d');

    $insert_sql = "INSERT INTO memorizing_record (memo_id, page, juzu, surah, date, session, status, student_id, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($dbCon, $insert_sql)) {
        mysqli_stmt_bind_param($stmt, "siiisssss", $new_memo_id, $page, $juzu, $surah, $date, $session, $status, $student_id, $staff_id);
        if (mysqli_stmt_execute($stmt)) {
            header("location: uRecord.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTSNA Al Quran Memorizing Tracking System - Create Record</title>
    <link rel="stylesheet" href="css/studDash.css">
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
    <style>
        form {
            max-width: 600px;
            margin: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #45a049;
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
                <li><button class="menu-btn" onclick="location.href='ustazReport.php'"><i class="fas fa-file-alt"></i>Report</button></li>
                <li><button class="menu-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</button></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>KOLEJ TAHFIZ SAINS NURUL AMAN</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($staff_username); ?></span>
                </div>
            </header>
            <a href="newRecord.php" class="back-button">Back to New Record</a>
            <h2>Create Memorizing Record for <?php echo htmlspecialchars($student_name); ?></h2>
            <form method="post" action="createRecord.php?student_id=<?php echo $student_id; ?>">
                <div class="form-group">
                    <label for="page">Page:</label>
                    <input type="number" id="page" name="page" required>
                </div>
                <div class="form-group">
                    <label for="session">Session:</label>
                    <select id="session" name="session" required>
                        <option value="d">Day</option>
                        <option value="n">Night</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="p">Pass</option>
                        <option value="f">Not Pass</option>
                    </select>
                </div>
                <button type="submit">Create Record</button>
            </form>
        </div>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>
