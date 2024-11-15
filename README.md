# Grading Tool for Teachers (Refactored for Testing)

## Purpose
This branch contains the refactored version of Homework #5 for Homework #6. The code has been modularized to make it easier to test using PHPUnit.

## Changes Made
- Refactored `index.php` to separate backend logic into `functions.php`.
- Added the `calculateFinalGrade` function for grade calculation.
- Added functions for database interactions:
  - `insertGrades`
  - `getStudents`
  - `getGrades`
- Implemented the Post/Redirect/Get (PRG) pattern to prevent duplicate submissions.

## Files
- `index.php`: Main frontend for grade entry and display.
- `functions.php`: Contains backend functions for calculations and database interactions.
- `TheTeachersHelper.sql`: SQL file to set up the database.

## Instructions
1. Import `TheTeachersHelper.sql` into your MySQL database.
2. Place `index.php` and `functions.php` in your local server’s `htdocs` directory.
3. Access the tool via `http://localhost/`.

## Notes
This branch is ready for unit testing.


## Contact
Creator : Edith Chamol

