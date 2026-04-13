<?php
session_start();
include('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Additional server-side validation (backup)
    if (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
        $error_message = "Password must be at least 8 characters long, contain a number, and a special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if admin already exists
        $check_sql = "SELECT * FROM admin WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Admin with this username already exists!";
        } else {
            // Insert new admin
            $insert_sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";
            $insert_result = mysqli_query($conn, $insert_sql);

            if ($insert_result) {
                $success_message = "Admin added successfully!";
            } else {
                $error_message = "Error adding admin: " . mysqli_error($conn);
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
    <title>Add Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="add_admin_bg">
<?php include 'header.php'; ?>
    <div class="form-container">
        <form method="POST" action="">
            <h2><u><i>ADD ADMIN</i></u></h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" 
                   pattern="^(?=.*[0-9])(?=.*[\W_]).{8,}$" 
                   title="Password must be at least 8 characters long, contain a number, and a special character." 
                   required>
            <button type="submit" name="submit">Add Admin</button>
            <a class="exit" href="index.php">Back</a>
        </form>

        <?php
        if (isset($success_message)) {
            echo "<p style='background: black; color: green; font-size: 25px; text-align: center;'>$success_message</p>";
        }
        if (isset($error_message)) {
            echo "<p style='background: black; color: red; font-size: 25px; text-align: center;'>$error_message</p>";
        }
        ?>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
