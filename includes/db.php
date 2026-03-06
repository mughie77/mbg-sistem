<?php
// Database configuration
$host = 'localhost';
$db_name = 'mbg_db';
$username = 'root';
$password = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token validation failed.');
        }
    }
}

// Strictly MySQL
try {
    $dsn = "mysql:host=$host;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $db = new PDO($dsn, $username, $password, $options);
    $db->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    $db->exec("USE $db_name");

} catch (PDOException $e) {
    // Note: User requested MySQL exclusively. In some environments, we might need to handle connection errors.
    die("Database connection failed: " . $e->getMessage());
}

// Create tables if they don't exist with appropriate MySQL syntax
$db->exec("CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    homeroom_teacher VARCHAR(255) NOT NULL,
    total_students INT NOT NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    report_date DATE NOT NULL,
    students_present INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    INDEX idx_reports_date (report_date)
)");

$db->exec("CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    class_id INT DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
    INDEX idx_complaints_date (created_at)
)");
