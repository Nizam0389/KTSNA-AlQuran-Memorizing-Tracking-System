<?php
session_start();
require_once "dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password format using regex
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $login_err = "Username should contain only letters and numbers.";
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $login_err = "Password should contain only letters and numbers.";
    } else {
        $password = md5($password); // Hash the password with md5

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
                    $login_err = "Username not existed or password incorrect.";
                }
            }
            mysqli_stmt_close($stmt);
        }
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
    <link rel="stylesheet" href="css/login.css">
    <style>
        .error-message {
            color: red;
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
                <input type="text" id="username" name="username" maxlength = "20" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" maxlength = "20" required>
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
    <script>
        document.getElementById("username").addEventListener("input", function(event) {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });

        document.getElementById("password").addEventListener("input", function(event) {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    </script>
</body>
</html>


