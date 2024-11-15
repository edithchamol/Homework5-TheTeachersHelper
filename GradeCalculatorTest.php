<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../functions/functions.php'; // Correct path to functions.php

class GradeCalculatorTest extends TestCase {

    // Test with average grades
    public function testCalculateFinalGradeWithValidData() {
        $homework = [85, 80, 90, 87, 88];
        $quizzes = [70, 75, 80, 85, 90];
        $midterm = 82;
        $finalProject = 89;

        $expected = 86; // Corrected expected value
        $this->assertEquals($expected, calculateFinalGrade($homework, $quizzes, $midterm, $finalProject));
    }

    // Test with all grades at zero
    public function testCalculateFinalGradeWithZeroGrades() {
        $homework = [0, 0, 0, 0, 0];
        $quizzes = [0, 0, 0, 0, 0];
        $midterm = 0;
        $finalProject = 0;

        $expected = 0; // All zero grades
        $this->assertEquals($expected, calculateFinalGrade($homework, $quizzes, $midterm, $finalProject));
    }

    // Test with perfect grades
    public function testCalculateFinalGradeWithPerfectGrades() {
        $homework = [100, 100, 100, 100, 100];
        $quizzes = [100, 100, 100, 100, 100];
        $midterm = 100;
        $finalProject = 100;

        $expected = 100; // Perfect grades
        $this->assertEquals($expected, calculateFinalGrade($homework, $quizzes, $midterm, $finalProject));
    }

    // Test with uneven grades
    public function testCalculateFinalGradeWithUnevenGrades() {
        $homework = [70, 80, 75, 90, 85];
        $quizzes = [60, 50, 100, 70, 80];
        $midterm = 85;
        $finalProject = 90;

        $expected = 85; // Corrected expected value after manual calculation
        $this->assertEquals($expected, calculateFinalGrade($homework, $quizzes, $midterm, $finalProject));
    }
}
