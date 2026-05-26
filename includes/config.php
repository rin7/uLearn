<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ulearn');

define('SITE_NAME', 'uLearn');
define('SITE_URL', 'http://localhost/ulearn');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
