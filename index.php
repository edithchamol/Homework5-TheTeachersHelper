<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

// Database Connection
try {
    $db = new PDO('mysql:host=localhost;dbname=grading_system;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_grades'])) {
        // Reset Grades
        $db->exec("DELETE FROM grades");
        echo "<p>All grades have been reset!</p>";
    } else {
        // Insert Grades
        $student_id = $_POST['student_id'];
        $grades = array_map('intval', $_POST['grades']); // Convert all grades to integers
        $midterm = intval($_POST['midterm']);
        $final_project = intval($_POST['final_project']);

        $query = "INSERT INTO grades (student_id, homework_1, homework_2, homework_3, homework_4, homework_5, quiz_1, quiz_2, quiz_3, quiz_4, quiz_5, midterm, final_project)
                  VALUES (:student_id, :hw1, :hw2, :hw3, :hw4, :hw5, :qz1, :qz2, :qz3, :qz4, :qz5, :midterm, :final_project)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':student_id' => $student_id,
            ':hw1' => $grades[0], ':hw2' => $grades[1], ':hw3' => $grades[2], ':hw4' => $grades[3], ':hw5' => $grades[4],
            ':qz1' => $grades[5], ':qz2' => $grades[6], ':qz3' => $grades[7], ':qz4' => $grades[8], ':qz5' => $grades[9],
            ':midterm' => $midterm, ':final_project' => $final_project
        ]);

        // Calculate Final Grade
        $homework_avg = array_sum(array_slice($grades, 0, 5)) / 5 * 0.2;
        $quizzes = array_slice($grades, 5, 5);
        sort($quizzes);
        $quiz_avg = array_sum(array_slice($quizzes, 1)) / 4 * 0.1; // Drop lowest
        $final_grade = round($homework_avg + $quiz_avg + ($midterm * 0.3) + ($final_project * 0.4));

        // Update Final Grade
        $db->prepare("UPDATE grades SET final_grade = :final_grade WHERE student_id = :student_id")
           ->execute([':final_grade' => $final_grade, ':student_id' => $student_id]);

        echo "<p>Final Grade for Student ID $student_id: $final_grade</p>";
    }
}

// Fetch Students and Grades
$students = $db->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
$grades = $db->query("SELECT students.student_name, grades.final_grade FROM students 
                      LEFT JOIN grades ON students.student_id = grades.student_id")->fetchAll(PDO::FETCH_ASSOC);
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
            flex-direction: column;
            align-items: center;
            background-color: #f4f4f4;
        }
        h1 {
            margin-top: 20px;
        }
        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
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
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .reset-button {
            background-color: #FF6347;
        }
        .reset-button:hover {
            background-color: #FF4500;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            background: #ffffff;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<h1>Grading Tool</h1>
<form method="POST">
    <label for="student">Select Student:</label>
    <select name="student_id">
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['student_id'] ?>"><?= htmlspecialchars($student['student_name']) ?></option>
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

<form method="POST">
    <button type="submit" name="reset_grades" class="reset-button">Reset All Grades</button>
</form>
<?php
echo "Debug: File is loading correctly.";
// Rest of your PHP code here...
?>

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
