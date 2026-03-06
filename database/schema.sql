CREATE DATABASE IF NOT EXISTS mbg_db;
USE mbg_db;

CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    homeroom_teacher VARCHAR(255) NOT NULL,
    total_students INT NOT NULL
);

CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    report_date DATE NOT NULL,
    students_present INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role ENUM('Siswa', 'Guru', 'Tendik') NOT NULL,
    class_id INT DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL
);

INSERT INTO classes (name, homeroom_teacher, total_students) VALUES
('X RPL 1', 'Budiono', 36),
('X RPL 2', 'Siti Aminah', 32),
('XI RPL 1', 'Agus Santoso', 34),
('XI RPL 2', 'Dewi Lestari', 35),
('XII RPL 1', 'Bambang Sudarsono', 33),
('XII RPL 2', 'Siti Zubaidah', 31);
