<?php
session_start();
include('db.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle adding student
if (isset($_POST['add_student'])) {
    $regno = mysqli_real_escape_string($conn, $_POST['regno']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);

    // Server-side validation: Ensure name contains only letters and spaces
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $error_message = "Name should only contain alphabets and spaces!";
    } else {
        // Convert the DOB to a timestamp and get the current date
        $dob_timestamp = strtotime($dob);
        $current_timestamp = time();

        if ($dob_timestamp > $current_timestamp) {
            $error_message = "Date of Birth cannot be a future date!";
        } else {
            // Check if student already exists
            $check_sql = "SELECT * FROM students WHERE regno = '$regno'";
            $check_result = mysqli_query($conn, $check_sql);
            
            if (mysqli_num_rows($check_result) > 0) {
                $error_message = "Student with this Register Number already exists!";
            } else {
                $insert_sql = "INSERT INTO students (regno, name, dob, class) VALUES ('$regno', '$name', '$dob', '$class')";
                if (mysqli_query($conn, $insert_sql)) {
                    $success_message = "Student added successfully!";
                } else {
                    $error_message = "Error adding student: " . mysqli_error($conn);
                }
            }
        }
    }
}

// Handle removing student
if (isset($_POST['remove_student'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $delete_sql = "DELETE FROM students WHERE id = '$student_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $success_message = "Student removed successfully!";
    } else {
        $error_message = "Error removing student: " . mysqli_error($conn);
    }
}

// Fetch student list
$student_list = mysqli_query($conn, "SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Remove Student</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
    <script>
        // JavaScript to prevent selecting future dates in DOB field
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split("T")[0];
            document.querySelector(".dob").setAttribute("max", today);
        });
    </script>
</head>
<body class="add_remove_student_bg">
<?php include 'header.php'; ?>
<?php
if (isset($success_message)) {
    echo "<p style='background: black; color:green; font-size:25px; text-align:center;'>$success_message</p>";
}
if (isset($error_message)) {
    echo "<p style='background: black; color:red; font-size:25px; text-align:center;'>$error_message</p>";
}
?>
<div class="form-container">
    <form method="POST" action="">
        <h2><u><i>ADD STUDENT</i></u></h2>
        <input type="text" name="regno" placeholder="Register Number" required>
        <input type="text" name="name" placeholder="Name" pattern="[A-Za-z ]+" 
               title="Only alphabets and spaces are allowed" required>
        <input type="date" name="dob" class="dob" required>
        <label for="class">Class</label>
        <select name="class" required>
            <option value="">Select Class</option>
            <optgroup label="UG">
                <option value="BCA">BCA</option>
                <option value="BSc. CS">BSc. Computer Science</option>
                <option value="AI">AI</option>
                <option value="B.Com">B.Com</option>
                <option value="Bio Chemistry">Bio Chemistry</option>
                <option value="BBA">BBA</option>
                <option value="BSc. Mathematics">BSc. Mathematics</option>
            </optgroup>
            <optgroup label="PG">
                <option value="MCA">MCA</option>
                <option value="M.Com">M.Com</option>
                <option value="MSc. Bio Chemistry">MSc. Bio Chemistry</option>
                <option value="MBA">MBA</option>
                <option value="MSc. CS">MSc. Computer Science</option>
            </optgroup>
        </select>
        <button type="submit" name="add_student">Add Student</button>
    </form>

    <form method="POST" action="">
        <h2><u><i>REMOVE STUDENT</i></u></h2>
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while ($student = mysqli_fetch_assoc($student_list)) { ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo $student['regno'] . " - " . $student['name']; ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" name="remove_student">Remove Student</button>
        <a class="exit" href="admin_dashboard.php">Back</a>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
