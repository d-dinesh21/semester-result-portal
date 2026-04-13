<?php
session_start();
include('db.php');

// Ensure faculty is logged in
if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch students list
$students = mysqli_query($conn, "SELECT * FROM students");

if (isset($_POST['submit_results'])) {
    $regno = mysqli_real_escape_string($conn, $_POST['regno']);
    $class = mysqli_real_escape_string($conn, $_POST['class']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    
    $subjects = $_POST['subjects'];
    $marks = $_POST['marks'];

    // Fetch the class of the selected student from the database
    $check_class_query = "SELECT class FROM students WHERE regno = '$regno'";
    $class_result = mysqli_query($conn, $check_class_query);
    $row = mysqli_fetch_assoc($class_result);
    $actual_class = $row['class'];

    // Check if the selected class matches the actual class of the student
    if ($class !== $actual_class) {
        $error_message = "Class Mismatch! You are trying to upload results for the wrong class.";
    } else {
        foreach ($subjects as $index => $subject) {
            $subject = mysqli_real_escape_string($conn, $subject);
            $mark = mysqli_real_escape_string($conn, $marks[$index]);

            // Validate marks (should be between 0 and 100)
            if ($mark < 0 || $mark > 100) {
                $error_message = "Invalid marks entered! Marks should be between 0 and 100.";
                break;
            }

            $check_sql = "SELECT * FROM results WHERE regno = '$regno' AND subject = '$subject' AND semester = '$semester'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) == 0) {
                $insert_sql = "INSERT INTO results (regno, class, semester, subject, marks) VALUES ('$regno', '$class', '$semester', '$subject', '$mark')";
                mysqli_query($conn, $insert_sql);
            }
        }
        if (!isset($error_message)) {
            $success_message = "Results entered successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
    <script>
        function addSubjectFields() {
            let numSubjects = document.getElementById("num_subjects").value;
            let subjectsDiv = document.getElementById("subjectsDiv");
            subjectsDiv.innerHTML = "";
            for (let i = 0; i < numSubjects; i++) {
                let subjectInput = document.createElement("input");
                subjectInput.type = "text";
                subjectInput.name = "subjects[]";
                subjectInput.placeholder = "Subject Name";
                subjectInput.required = true;
                subjectInput.classList.add('input-field');

                let marksInput = document.createElement("input");
                marksInput.type = "number";
                marksInput.name = "marks[]";
                marksInput.placeholder = "Marks";
                marksInput.required = true;
                marksInput.classList.add('input-field');
                marksInput.min = 0;
                marksInput.max = 100;
                marksInput.oninput = function () {
                    if (this.value > 100) this.value = 100;
                    if (this.value < 0) this.value = 0;
                };

                subjectsDiv.appendChild(subjectInput);
                subjectsDiv.appendChild(marksInput);
                subjectsDiv.appendChild(document.createElement("br"));
            }
        }
    </script>
</head>
<body class="faculty_dashboard_bg">
<?php include 'header.php'; ?>
<?php
if (isset($success_message)) {
    echo "<p style='background: black; color:green; font-size:25px; text-align:center;' class='success-message'>$success_message</p>";
}
if (isset($error_message)) {
    echo "<p style='background: black; color:red; font-size:25px; text-align:center;' class='error-message'>$error_message</p>";
}
?>
    <div class="faculty-dashboard">
        <form method="POST" action="">
        <h2><u><i>FACULTY DASHBOARD</i></u></h2>
            <label for="regno">Select Student</label>
            <select name="regno" class="input-field" required>
                <option value="">Select Student</option>
                <?php while ($student = mysqli_fetch_assoc($students)) { ?>
                    <option value="<?php echo $student['regno']; ?>">
                        <?php echo $student['regno'] . " - " . $student['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="class">Class</label>
            <select name="class" class="input-field" required>
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

            <label for="semester">Semester</label>
            <input type="number" name="semester" min="1" max="8" required class="input-field">

            <label for="num_subjects">Number of Subjects</label>
            <input type="number" id="num_subjects" min="1" required oninput="addSubjectFields()" class="input-field">
            
            <div id="subjectsDiv"></div>

            <button type="submit" name="submit_results">Submit Results</button>
            <a class="exit" href="faculty_login.php">Logout</a>
        </form>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
