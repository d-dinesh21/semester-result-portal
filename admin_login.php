<?php
session_start();
include('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Check if admin exists
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="admin_login_page">
<?php include 'header.php'; ?>
    <div class="login-container">
        <h2><u><i>ADMIN LOGIN</i></u></h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <a class="exit" href="index.php">Back</a>
            <a class="exit" href="add_admin.php">Add Admin</a>
        </form>

        <?php
        if (isset($error_message)) {
            echo "<p style= size:25px color:red; text-align:center;'>$error_message</p>";
        }
        ?>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
