<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="admin_dashboard_bg">
<?php include 'header.php'; ?>
    <div class="dashboard-container">
        <h2><u>Welcome, <?php echo $_SESSION['admin_username']; ?>!</u></h2>
        <nav>
            <ul>
                <li><a href="add_remove_faculty.php">Add/Remove Faculty</a></li>
                <li><a href="add_remove_student.php">Add/Remove Student</a></li>
                <li><a href="modify_results.php">Modify Results</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
