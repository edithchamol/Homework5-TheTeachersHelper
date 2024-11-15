<?php
function calculateFinalGrade(array $homework, array $quizzes, int $midterm, int $finalProject): int {
    $homeworkAvg = array_sum($homework) / count($homework) * 0.2;
    sort($quizzes);
    $quizAvg = array_sum(array_slice($quizzes, 1)) / 4 * 0.1;
    return round($homeworkAvg + $quizAvg + ($midterm * 0.3) + ($finalProject * 0.4));
}

function insertGrades(PDO $db, int $studentId, array $homework, array $quizzes, int $midterm, int $finalProject, int $finalGrade): void {
    // Check if grades already exist for the student
    $query = "SELECT COUNT(*) FROM grades WHERE student_id = :student_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':student_id' => $studentId]);
    if ($stmt->fetchColumn() > 0) {
        return; // Do nothing if grades already exist
    }

    $query = "
        INSERT INTO grades (
            student_id, 
            homework_1, homework_2, homework_3, homework_4, homework_5, 
            quiz_1, quiz_2, quiz_3, quiz_4, quiz_5, 
            midterm, final_project, final_grade
        ) VALUES (
            :student_id, 
            :hw1, :hw2, :hw3, :hw4, :hw5, 
            :qz1, :qz2, :qz3, :qz4, :qz5, 
            :midterm, :final_project, :final_grade
        )
    ";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':student_id' => $studentId,
        ':hw1' => $homework[0], ':hw2' => $homework[1], ':hw3' => $homework[2],
        ':hw4' => $homework[3], ':hw5' => $homework[4],
        ':qz1' => $quizzes[0], ':qz2' => $quizzes[1], ':qz3' => $quizzes[2],
        ':qz4' => $quizzes[3], ':qz5' => $quizzes[4],
        ':midterm' => $midterm, ':final_project' => $finalProject, ':final_grade' => $finalGrade
    ]);
}

function getStudents(PDO $db): array {
    return $db->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
}

function getGrades(PDO $db): array {
    return $db->query("
        SELECT students.student_name, grades.final_grade 
        FROM students 
        LEFT JOIN grades ON students.student_id = grades.student_id
    ")->fetchAll(PDO::FETCH_ASSOC);
}
