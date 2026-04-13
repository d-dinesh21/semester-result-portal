<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Web Portal for Semester Result Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="index_page">
<?php include 'header.php'; ?>
    <div class="container">
        <h1>Welcome Guys</h1><br>
        <marquee><h4>This portal allows admins, faculty members, and students to manage and access semester results efficiently.</h4></marquee>        
        <div class="login-options">
            <h2>Login Options</h2>
            <ul>
                <li><a href="admin_login.php">Admin Login</a></li>
                <li><a href="faculty_login.php">Faculty Login</a></li>
                <li><a href="student_login.php">Student Login</a></li>
            </ul>
        </div>
        <h3> Contact: +91-1234567890 </h3>
        <h3> Mail: abcd@gmail.com </h3>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
