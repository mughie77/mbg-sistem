<?php
// Database configuration
$host = 'localhost';
$db_name = 'mbg_db';
$username = 'root';
$password = '';

try {
    // Attempt to connect to MySQL
    $db = new PDO("mysql:host=$host", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $db->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    $db->exec("USE $db_name");

    // Create tables if they don't exist
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
        FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
    )");

    // Insert some initial data for testing if tables are empty
    $stmt = $db->prepare("SELECT COUNT(*) FROM classes");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $classes = [
            ['X RPL 1', 'Budiono', 36],
            ['X RPL 2', 'Siti Aminah', 32],
            ['XI RPL 1', 'Agus Santoso', 34],
            ['XI RPL 2', 'Dewi Lestari', 35],
            ['XII RPL 1', 'Bambang Sudarsono', 33],
            ['XII RPL 2', 'Siti Zubaidah', 31],
        ];
        $stmt = $db->prepare("INSERT INTO classes (name, homeroom_teacher, total_students) VALUES (?, ?, ?)");
        foreach ($classes as $class) {
            $stmt->execute($class);
        }
    }
} catch (PDOException $e) {
    // If MySQL is not available, fall back to SQLite for development/demo purposes
    $dbPath = (basename(getcwd()) == 'admin' ? '../' : '') . 'database/mbg.sqlite';
    if (!file_exists(dirname($dbPath))) {
        mkdir(dirname($dbPath), 0777, true);
    }

    try {
        $db = new PDO("sqlite:$dbPath");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("CREATE TABLE IF NOT EXISTS classes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            homeroom_teacher VARCHAR(255) NOT NULL,
            total_students INT NOT NULL
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS reports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            class_id INT NOT NULL,
            report_date DATE NOT NULL,
            students_present INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
        )");

        $stmt = $db->prepare("SELECT COUNT(*) FROM classes");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $classes = [
                ['X RPL 1', 'Budiono', 36],
                ['X RPL 2', 'Siti Aminah', 32],
                ['XI RPL 1', 'Agus Santoso', 34],
                ['XI RPL 2', 'Dewi Lestari', 35],
                ['XII RPL 1', 'Bambang Sudarsono', 33],
                ['XII RPL 2', 'Siti Zubaidah', 31],
            ];
            $stmt = $db->prepare("INSERT INTO classes (name, homeroom_teacher, total_students) VALUES (?, ?, ?)");
            foreach ($classes as $class) {
                $stmt->execute($class);
            }
        }
    } catch (PDOException $e2) {
        die("Fatal Error: Database connection failed. " . $e2->getMessage());
    }
}
