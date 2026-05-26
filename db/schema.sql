-- uLearn Database Schema
-- Aptech Final Project 2005

CREATE DATABASE IF NOT EXISTS ulearn;
USE ulearn;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    instructor VARCHAR(100) NOT NULL,
    duration_weeks INT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Chapters table
CREATE TABLE IF NOT EXISTS chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    chapter_order INT NOT NULL DEFAULT 1,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_enrollment (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Quizzes table
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Quiz questions table
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('a','b','c','d') NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Quiz results table
CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    total INT NOT NULL DEFAULT 0,
    taken_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Seed admin user (password: password)
INSERT INTO users (username, password, full_name, email, role)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@ulearn.local', 'admin')
ON DUPLICATE KEY UPDATE id = id;

-- Sample courses
INSERT INTO courses (title, description, instructor, duration_weeks) VALUES
('Introduction to PHP', 'Learn the basics of PHP scripting language. Covers variables, loops, functions and forms.', 'Mr. Ahmed Khan', 6),
('MySQL Database Fundamentals', 'Understand relational databases, SQL queries, joins and normalization using MySQL.', 'Ms. Sara Ali', 4),
('HTML & CSS Basics', 'Build your first web pages using HTML tags and style them with CSS.', 'Mr. Tariq Mehmood', 3),
('JavaScript for Beginners', 'Add interactivity to your web pages with JavaScript event handling and DOM manipulation.', 'Ms. Nadia Rashid', 5)
ON DUPLICATE KEY UPDATE id = id;

-- Sample chapters for PHP course
INSERT INTO chapters (course_id, title, content, chapter_order) VALUES
(1, 'Getting Started with PHP', 'PHP is a server-side scripting language designed for web development. Install XAMPP to get Apache, MySQL and PHP running on your local machine. Your first PHP script:\n\n<?php echo "Hello, World!"; ?>', 1),
(1, 'Variables and Data Types', 'PHP variables start with a $ sign. Common data types include strings, integers, floats and booleans. Example: $name = "uLearn"; $count = 10;', 2),
(1, 'Control Structures', 'Use if/else statements and loops (for, while, foreach) to control program flow. Example: for($i=1; $i<=10; $i++) { echo $i; }', 3),
(1, 'Functions and Forms', 'Define reusable code blocks with functions. Process HTML form data using $_GET and $_POST superglobals.', 4);

-- Sample quiz for PHP course
INSERT INTO quizzes (course_id, title) VALUES
(1, 'PHP Basics Quiz');

-- Sample questions
INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES
(1, 'Which symbol is used to declare a PHP variable?', '$', '#', '@', '&', 'a'),
(1, 'Which superglobal is used to collect form data sent with POST method?', '$_GET', '$_POST', '$_REQUEST', '$_FORM', 'b'),
(1, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Hypertext Protocol', 'Public Home Page', 'b'),
(1, 'Which function is used to output text in PHP?', 'print_text()', 'display()', 'echo', 'write()', 'c'),
(1, 'Which loop is used to iterate over an array in PHP?', 'for', 'while', 'do-while', 'foreach', 'd');
