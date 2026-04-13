<?php
session_start();
include('db.php');

if (isset($_POST['login'])) {
    $regno = mysqli_real_escape_string($conn, $_POST['regno']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    $query = "SELECT * FROM students WHERE regno='$regno' AND dob='$dob'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['student_regno'] = $regno;
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid Register Number or Date of Birth!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="student_login_bg">
<?php include 'header.php'; ?>
<?php
        if (isset($error_message)) {
            echo "<p style='background: black; color:red; font-size:25px; text-align:center;'>$error_message</p>";
        }
        ?>
    <div class="form-container">
        <form method="POST" action="">
        <h2><u><i>STUDENT LOGIN</i></u></h2>
            <input type="text" name="regno" placeholder="Register Number" required>
            <input type="date" name="dob" required>
            <button type="submit" name="login">Login</button>
            <a class="exit" href="index.php">Back</a>
        </form>
        
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
