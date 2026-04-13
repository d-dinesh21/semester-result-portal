<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Using Prepared Statements
    $stmt = $conn->prepare("SELECT * FROM faculty WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['faculty_username'] = $row['username'];
            header("Location: faculty_dashboard.php");
            exit();
        } else {
            $error = "Incorrect username or password!";
        }
    } else {
        $error = "Incorrect username or password!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="faculty_login_bg">
<?php include 'header.php'; ?>
    <div class="login-container">
        <h2><u><i>FACULTY LOGIN</i></u></h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a class="exit" href="index.php">Back</a>
        </form>
        <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
