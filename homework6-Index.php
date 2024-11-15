<?php
// Include the helper functions
require_once 'functions.php';

// Database Connection
try {
    $db = new PDO('mysql:host=localhost;dbname=grading_system;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $grades = array_map('intval', $_POST['grades']);
    $midterm = intval($_POST['midterm']);
    $final_project = intval($_POST['final_project']);

    // Calculate Final Grade
    $final_grade = calculateFinalGrade(
        array_slice($grades, 0, 5), // Homework
        array_slice($grades, 5, 5), // Quizzes
        $midterm,
        $final_project
    );

    // Insert grades into the database
    insertGrades($db, $student_id, array_slice($grades, 0, 5), array_slice($grades, 5, 5), $midterm, $final_project, $final_grade);

    // Redirect to the same page to prevent duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch Students and Grades
$students = getStudents($db);
$grades = getGrades($db);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grading Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ffc0cb;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #ff91a4;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 500px;
            margin-top: 20px;
            background: #ffc0cb;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #ff91a4;
            color: white;
        }
        td {
            background-color: #ffe4e6;
        }
    </style>
</head>
<body>
<h1>Grading Tool</h1>
<form method="POST">
    <label for="student">Select Student:</label>
    <select name="student_id">
        <?php foreach ($students as $student): ?>
            <option value="<?= htmlspecialchars($student['student_id']) ?>"><?= htmlspecialchars($student['student_name']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <label>Homework <?= $i ?>: <input type="number" name="grades[]" required></label>
    <?php endfor; ?>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <label>Quiz <?= $i ?>: <input type="number" name="grades[]" required></label>
    <?php endfor; ?>
    <label>Midterm: <input type="number" name="midterm" required></label>
    <label>Final Project: <input type="number" name="final_project" required></label>
    <button type="submit">Submit Grades</button>
</form>

<h1>Final Grades</h1>
<table>
    <tr>
        <th>Student Name</th>
        <th>Final Grade</th>
    </tr>
    <?php foreach ($grades as $grade): ?>
        <tr>
            <td><?= htmlspecialchars($grade['student_name']) ?></td>
            <td><?= htmlspecialchars($grade['final_grade'] ?? 'Not Graded') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
