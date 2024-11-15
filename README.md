# Grading Tool for Teachers

## Description
This project is a Grading Tool designed for teachers to calculate and store final grades for students based on the provided rubric. Teachers can input grades for homework, quizzes, a midterm, and a final project. The tool calculates the final grade and stores all data in a database.

## Setup Instructions
1. Import the `TheTeachersHelper.sql` file into your MySQL database.
2. Ensure the database name is `grading_system` and it includes the `students` and `grades` tables.
3. Place the `index.php` file in your local server's `htdocs` directory.
4. Open your browser and navigate to `http://localhost/Homework5/`.

## Usage
1. Select a student from the dropdown menu.
2. Input grades for homework, quizzes, midterm, and final project.
3. Click "Submit Grades" to calculate and store the final grade.
4. View all final grades in the table displayed below the form.

## Features
- Preloaded student names in the database.
- Drop the lowest quiz score before averaging.
- Weighted grading system:
  - Homework: 20%
  - Quizzes: 10% (lowest score dropped)
  - Midterm: 30%
  - Final Project: 40%
- Persistent grade storage in the database.

## Contact
Creator : Edith Chamol

