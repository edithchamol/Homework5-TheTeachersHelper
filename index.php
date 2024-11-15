<?php
// Database Connection
try {
    $db = new PDO('mysql:host=localhost;dbname=grading_system;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $grades = array_map('intval', $_POST['grades']); // Convert all grades to integers
    $midterm = intval($_POST['midterm']);
    $final_project = intval($_POST['final_project']);

    // Insert Grades
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
            background-color: #ffc0cb; /* Match the pink chart color */
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #ff91a4; /* Darker pink on hover */
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 500px;
            margin-top: 20px;
            background: #ffc0cb; /* Pink background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #ff91a4; /* Darker pink for header */
            color: white;
        }
        td {
            background-color: #ffe4e6; /* Light pink for rows */
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
