<?php
// Set correct working directory based on context
$dbPath = (basename(getcwd()) == 'admin' ? '../' : '') . 'database/mbg.sqlite';
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables if they don't exist
$db->exec("CREATE TABLE IF NOT EXISTS classes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    homeroom_teacher TEXT NOT NULL,
    total_students INTEGER NOT NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS reports (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    class_id INTEGER NOT NULL,
    report_date DATE NOT NULL,
    students_present INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id)
)");

// Insert some initial data for testing
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
