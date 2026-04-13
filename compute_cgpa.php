<?php
$gpa = null; // Initialize GPA variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total_credit_points = 0;
    $total_credits = 0;

    foreach ($_POST['credit'] as $key => $credit) {
        $credit = floatval($credit);
        $grade_point = floatval($_POST['grade_point'][$key]);

        if ($credit > 0) {
            $total_credit_points += ($credit * $grade_point);
            $total_credits += $credit;
        }
    }

    // Compute CGPA only if total credits are greater than 0
    $gpa = ($total_credits > 0) ? round($total_credit_points / $total_credits, 2) : "Invalid input. Ensure credits are greater than 0.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compute CGPA</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="./images/index_bg.jpg">
</head>
<body class="compute_cgpa_bg">
<?php include 'header.php'; ?>
    <div class="form-container">
        
        <form method="POST" action="">
        <h2><u><i>Compute CGPA</i></u></h2>
            <div id="subject-container">
                <div class="subject">
                    <input type="text" name="subject_name[]" placeholder="Subject Name" required>
                    <input type="number" name="credit[]" placeholder="Credit Hours" step="0.1" required>
                    <input type="number" name="grade_point[]" placeholder="Grade Point" step="0.1" required>
                </div>
            </div>

            <button type="button" onclick="addSubjectFields()">Add Subject</button>
            <button type="submit">Compute CGPA</button>
            <a class="exit" href="student_dashboard.php">Back</a>

            <!-- CGPA result displayed below the button -->
            <?php if ($gpa !== null) { ?>
                <p class="gpa-result">Computed CGPA: <strong><?php echo $gpa; ?></strong></p>
            <?php } ?>
        </form>

    </div>

<?php include 'footer.php'; ?>

<script>
    function addSubjectFields() {
        let container = document.getElementById('subject-container');
        let div = document.createElement('div');
        div.classList.add('subject');
        div.innerHTML = `
            <input type="text" name="subject_name[]" placeholder="Subject Name" required>
            <input type="number" name="credit[]" placeholder="Credit Hours" step="0.1" required>
            <input type="number" name="grade_point[]" placeholder="Grade Point" step="0.1" required>
        `;
        container.appendChild(div);
    }
</script>

</body>
</html>
