<?php
session_start();
include('db.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle adding faculty
if (isset($_POST['add_faculty'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Server-side password validation (backup)
    if (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
        $error_message = "Password must be at least 8 characters long, contain a number, and a special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if faculty already exists
        $check_sql = "SELECT * FROM faculty WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Faculty with this username already exists!";
        } else {
            $insert_sql = "INSERT INTO faculty (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $insert_sql)) {
                $success_message = "Faculty added successfully!";
            } else {
                $error_message = "Error adding faculty: " . mysqli_error($conn);
            }
        }
    }
}

// Handle removing faculty
if (isset($_POST['remove_faculty'])) {
    $faculty_id = mysqli_real_escape_string($conn, $_POST['faculty_id']);
    $delete_sql = "DELETE FROM faculty WHERE id = '$faculty_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $success_message = "Faculty removed successfully!";
    } else {
        $error_message = "Error removing faculty: " . mysqli_error($conn);
    }
}

// Fetch faculty list
$faculty_list = mysqli_query($conn, "SELECT * FROM faculty");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Remove Faculty</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="add_remove_faculty_bg">
<?php include 'header.php'; ?>

    <!-- Display success or error message at the top -->
    <div class="message-container">
        <?php
        if (isset($success_message)) {
            echo "<p style='background: black; color:green; font-size:25px; text-align:center;' class='success-message'>$success_message</p>";
        }
        if (isset($error_message)) {
            echo "<p style='background: black; color:red; font-size:25px; text-align:center;' class='error-message'>$error_message</p>";
        }
        ?>
    </div>

    <div class="form-container">
        <form method="POST" action="">
        <h2><u><i>ADD FACULTY</i></u></h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" 
                   pattern="^(?=.*[0-9])(?=.*[\W_]).{8,}$" 
                   title="Password must be at least 8 characters long, contain a number, and a special character." 
                   required>
            <button type="submit" name="add_faculty">Add Faculty</button>
        </form>

        <form method="POST" action="">
        <h2><u><i>REMOVE FACULTY</i></u></h2>
            <select name="faculty_id" required>
                <option value="">Select Faculty</option>
                <?php while ($faculty = mysqli_fetch_assoc($faculty_list)) { ?>
                    <option value="<?php echo $faculty['id']; ?>">
                        <?php echo $faculty['username']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" name="remove_faculty">Remove Faculty</button>
            <a class="exit" href="admin_dashboard.php">Back</a>
        </form>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>
