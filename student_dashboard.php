<?php
session_start();
include('db.php');

if (!isset($_SESSION['student_regno'])) {
    header("Location: student_login.php");
    exit();
}

$regno = $_SESSION['student_regno'];

// Fetch student details
$student_query = "SELECT name, class FROM students WHERE regno='$regno'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

if (!$student) {
    die("Student details not found.");
}

// Fetch student results
$query = "SELECT semester, subject, marks FROM results WHERE regno='$regno'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="student_dashboard_bg">
<?php include 'header.php'; ?>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?></h2>
        <p><b>Class: <?php echo htmlspecialchars($student['class']); ?></b></p><br>

        <h3><u>Your Result</u></h3>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table border="1">
                <tr>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Result</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    $status = ($row['marks'] < 35) ? "Fail" : "Pass";
                    $status_class = ($row['marks'] < 35) ? "fail" : "pass";
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td><?php echo isset($row['subject']) ? htmlspecialchars($row['subject']) : 'N/A'; ?></td>
                        <td><?php echo isset($row['marks']) ? htmlspecialchars($row['marks']) : 'N/A'; ?></td>
                        <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                    </tr>
                <?php } ?>
            </table><br><br>
        <?php } else { ?>
            <p>No results found.</p>
        <?php } ?>

        <a class="exit" href="compute_cgpa.php">Compute CGPA</a><br>

        <a class="exit" href="logout.php">Logout</a>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
