-- Create the database
CREATE DATABASE IF NOT EXISTS grading_system;

-- Use the database
USE grading_system;

-- Create the students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL
);

-- Create the grades table
CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    homework_1 INT NOT NULL,
    homework_2 INT NOT NULL,
    homework_3 INT NOT NULL,
    homework_4 INT NOT NULL,
    homework_5 INT NOT NULL,
    quiz_1 INT NOT NULL,
    quiz_2 INT NOT NULL,
    quiz_3 INT NOT NULL,
    quiz_4 INT NOT NULL,
    quiz_5 INT NOT NULL,
    midterm INT NOT NULL,
    final_project INT NOT NULL,
    final_grade INT DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Insert sample student data
INSERT INTO students (student_name) VALUES
('Edith Chamol'),
('julie kang '),
('Analeah lucero'),
('xander hernandez'),
('sophia tacuri'),
('Dulce Estevez'),
('Kimberly Ramirez'),
('Stephanie Eumana'),
('Mari Estevez'),
('Alexandra Pineda');
