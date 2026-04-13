<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Fetch students for dropdown
$students_query = mysqli_query($conn, "SELECT regno, name FROM students");

// Fetch results for deletion dropdown
$results_query = mysqli_query($conn, "SELECT regno, class, semester, subject FROM results");

// Update Results
if (isset($_POST['update_result'])) {
    $regno = mysqli_real_escape_string($conn, $_POST['regno']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $marks = mysqli_real_escape_string($conn, $_POST['marks']);

    // Ensure marks are within range
    if ($marks < 0 || $marks > 100) {
        $message = "<p style='background: black; color:red; font-size:25px; text-align:center;'>Marks should be between 0 and 100!</p>";
    } else {
        // Check if record exists
        $check_query = "SELECT * FROM results WHERE regno='$regno' AND class='$class' AND semester='$semester' AND subject='$subject'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $update_query = "UPDATE results SET marks='$marks' WHERE regno='$regno' AND class='$class' AND semester='$semester' AND subject='$subject'";
            if (mysqli_query($conn, $update_query)) {
                $message = "<p style='background: black; color:green; font-size:25px; text-align:center;'>Result updated successfully!</p>";
            } else {
                $message = "<p style='background: black; color:red; font-size:25px; text-align:center;'>Error updating result!</p>";
            }
        } else {
            $message = "<p style='background: black; color:red; font-size:25px; text-align:center;'>Record not found! Please check student details.</p>";
        }
    }
}

// Delete Results
if (isset($_POST['delete_result'])) {
    $delete_regno = mysqli_real_escape_string($conn, $_POST['delete_regno']);
    $delete_class = mysqli_real_escape_string($conn, $_POST['delete_class']);
    $delete_semester = mysqli_real_escape_string($conn, $_POST['delete_semester']);
    $delete_subject = mysqli_real_escape_string($conn, $_POST['delete_subject']);

    // Ensure record exists before deleting
    $check_query = "SELECT * FROM results WHERE regno='$delete_regno' AND class='$delete_class' AND semester='$delete_semester' AND subject='$delete_subject'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM results WHERE regno='$delete_regno' AND class='$delete_class' AND semester='$delete_semester' AND subject='$delete_subject'";
        if (mysqli_query($conn, $delete_query)) {
            $message = "<p style='background: black; color:green; font-size:25px; text-align:center;'>Result deleted successfully!</p>";
        } else {
            $message = "<p style='background: black; color:red; font-size:25px; text-align:center;'>Error deleting result!</p>";
        }
    } else {
        $message = "<p style='background: black; color:red; font-size:25px; text-align:center;'>No such record found to delete!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Results</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="modify_results_bg">
<?php include 'header.php'; ?>
        
<?php if ($message) echo $message; ?>

<form method="POST">
    <h2><u><i>MODIFY STUDENT RESULTS</i></u></h2>
    <label for="regno">Select Student:</label>
    <select name="regno" required>
        <option value="">Select Student</option>
        <?php while ($row = mysqli_fetch_assoc($students_query)) { ?>
            <option value="<?php echo $row['regno']; ?>">
                <?php echo $row['regno'] . " - " . $row['name']; ?>
            </option>
        <?php } ?>
    </select>

    <label for="class">Class:</label>
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
            <option value="BSc. CS">BSc. Computer Science</option>
        </optgroup>
    </select>

    <label for="semester">Semester:</label>
    <input type="number" name="semester" min="1" max="8" required>

    <label for="subject">Subject:</label>
    <input type="text" name="subject" required>

    <label for="marks">Marks:</label>
    <input type="number" name="marks" min="0" max="100" required>

    <button type="submit" name="update_result">Update Result</button>
    <a class="exit" href="admin_dashboard.php">Back</a>
</form>

<form method="POST">
    <h3><u><i>DELETE STUDENT RESULT</i></u></h3>
    <label for="delete_regno">Select Record to Delete:</label>
    <select name="delete_regno" required>
        <option value="">Select Student</option>
        <?php while ($row = mysqli_fetch_assoc($results_query)) { ?>
            <option value="<?php echo $row['regno']; ?>">
                <?php echo $row['regno']. " - " . $row['class']." - ".$row['subject']; ?>
            </option>
        <?php } ?>
    </select>

    <label for="delete_class">Class:</label>
    <select name="delete_class" required>
        <option value="">Select Class</option>
        <option value="BCA">BCA</option>
        <option value="BSc. CS">BSc. Computer Science</option>
        <option value="AI">AI</option>
        <option value="B.Com">B.Com</option>
        <option value="Bio Chemistry">Bio Chemistry</option>
        <option value="BBA">BBA</option>
        <option value="BSc. Mathematics">BSc. Mathematics</option>
        <option value="MCA">MCA</option>
        <option value="M.Com">M.Com</option>
        <option value="MSc. Bio Chemistry">MSc. Bio Chemistry</option>
        <option value="MBA">MBA</option>
    </select>

    <label for="delete_semester">Semester:</label>
    <input type="number" name="delete_semester" min="1" max="8" required>

    <label for="delete_subject">Subject:</label>
    <input type="text" name="delete_subject" required>

    <button type="submit" name="delete_result" onclick="return confirm('Are you sure you want to delete this result?');" style="background-color: red;">Delete Result</button>
    <a class="exit" href="admin_dashboard.php">Back</a>
</form>

<?php include 'footer.php'; ?>
</body>
</html>
