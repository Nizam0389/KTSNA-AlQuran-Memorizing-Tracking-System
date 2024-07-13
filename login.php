<?php
session_start();
require_once "dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash the password with md5

    if ($role === 'student') {
        $sql = "SELECT student_id, student_name FROM student WHERE student_username = ? AND student_pass = ?";
    } else {
        $sql = "SELECT staff_id, staff_name, staff_type FROM staff WHERE staff_username = ? AND staff_pass = ? AND staff_type = ?";
    }

    if ($stmt = mysqli_prepare($dbCon, $sql)) {
        if ($role === 'student') {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                if ($role === 'student') {
                    mysqli_stmt_bind_result($stmt, $id, $name);
                    mysqli_stmt_fetch($stmt);
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["name"] = $name;
                    $_SESSION["role"] = $role;  // Add role to session
                    header("location: studDash.php");
                } else {
                    mysqli_stmt_bind_result($stmt, $id, $name, $type);
                    mysqli_stmt_fetch($stmt);
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["name"] = $name;
                    $_SESSION["role"] = $type;  // Add role to session
                    if ($type === 'ustaz') {
                        header("location: ustazDash.php");
                    } else if ($type === 'mudir') {
                        header("location: mudirDash.php");
                    }
                }
            } else {
                $login_err = "Invalid username or password.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($dbCon);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Al-Quran Memorizing Tracking System</title>
    <style>
        /* Basic styling for demonstration purposes */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="role">Select Role:</label>
                <select id="role" name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="ustaz">Ustaz</option>
                    <option value="mudir">Mudir</option>
                    <option value="student">Student</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <?php 
            if(isset($login_err)){
                echo '<div class="error-message">' . $login_err . '</div>';
            }        
            ?>
        </form>
    </div>
</body>
</html>
