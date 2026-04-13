CREATE DATABASE gpa_system;
USE gpa_system;

CREATE TABLE calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100),
    semester_label VARCHAR(50),
    gpa DECIMAL(3, 2),
    calculation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
