<?php
/**
 * Utility functions for uLearn
 */

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        redirect(SITE_URL . '/login.php');
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        redirect(SITE_URL . '/index.php');
    }
}

function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($data)));
}

function db_escape($data) {
    global $conn;
    return $conn->real_escape_string($data);
}

function get_user_by_id($id) {
    global $conn;
    $id = (int)$id;
    $result = $conn->query("SELECT * FROM users WHERE id = $id LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}

function get_all_courses() {
    global $conn;
    $result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    return $courses;
}

function get_course_by_id($id) {
    global $conn;
    $id = (int)$id;
    $result = $conn->query("SELECT * FROM courses WHERE id = $id LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}

function get_chapters_by_course($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $result = $conn->query("SELECT * FROM chapters WHERE course_id = $course_id ORDER BY chapter_order ASC");
    $chapters = [];
    while ($row = $result->fetch_assoc()) {
        $chapters[] = $row;
    }
    return $chapters;
}

function is_enrolled($user_id, $course_id) {
    global $conn;
    $user_id  = (int)$user_id;
    $course_id = (int)$course_id;
    $result = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id LIMIT 1");
    return $result && $result->num_rows > 0;
}

function get_quizzes_by_course($course_id) {
    global $conn;
    $course_id = (int)$course_id;
    $result = $conn->query("SELECT * FROM quizzes WHERE course_id = $course_id");
    $quizzes = [];
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
    return $quizzes;
}

function get_questions_by_quiz($quiz_id) {
    global $conn;
    $quiz_id = (int)$quiz_id;
    $result = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    return $questions;
}

function get_quiz_result($user_id, $quiz_id) {
    global $conn;
    $user_id = (int)$user_id;
    $quiz_id = (int)$quiz_id;
    $result = $conn->query("SELECT * FROM quiz_results WHERE user_id = $user_id AND quiz_id = $quiz_id ORDER BY taken_at DESC LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}

function count_students() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE role = 'student'");
    $row = $result->fetch_assoc();
    return $row['cnt'];
}

function count_courses() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM courses");
    $row = $result->fetch_assoc();
    return $row['cnt'];
}

function count_enrollments() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM enrollments");
    $row = $result->fetch_assoc();
    return $row['cnt'];
}
