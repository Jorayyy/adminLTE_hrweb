<?php
require_once 'config/db.php';

// Check if we are on MariaDB/MySQL (Hostinger) or SQLite
$driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($driver === 'mysql' || $driver === 'mariadb') {
    // MySQL / MariaDB Syntax
    $queries = [
        "CREATE TABLE IF NOT EXISTS advance_types (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS announcements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS banks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_number VARCHAR(100) UNIQUE NOT NULL,
            firstname VARCHAR(100),
            lastname VARCHAR(100),
            department VARCHAR(100),
            employment_type VARCHAR(100),
            status VARCHAR(50) DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS employees_extended (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id VARCHAR(100) UNIQUE NOT NULL,
            company VARCHAR(100),
            position VARCHAR(100),
            location VARCHAR(100),
            section VARCHAR(100),
            FOREIGN KEY(employee_id) REFERENCES employees(id_number) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS payroll_period_groups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pay_type VARCHAR(50) NOT NULL,
            group_name VARCHAR(255) NOT NULL,
            description TEXT,
            status VARCHAR(20) DEFAULT 'active',
            is_on TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS payroll_periods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            group_id INT NOT NULL,
            cut_off VARCHAR(50),
            date_from DATE NOT NULL,
            date_to DATE NOT NULL,
            cut_off_day INT,
            cover_year INT,
            cover_month VARCHAR(50),
            no_of_days INT,
            pay_date DATE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS employee_group_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id VARCHAR(100) NOT NULL,
            group_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(employee_id, group_id),
            FOREIGN KEY(employee_id) REFERENCES employees(id_number) ON DELETE CASCADE,
            FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id) ON DELETE CASCADE
        )"
    ];
} else {
    // SQLite Syntax (Local fallback)
    $queries = [
        "CREATE TABLE IF NOT EXISTS advance_types (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, description TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
        "CREATE TABLE IF NOT EXISTS announcements (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, message TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
        "CREATE TABLE IF NOT EXISTS banks (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, code TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
        "CREATE TABLE IF NOT EXISTS employees (id INTEGER PRIMARY KEY AUTOINCREMENT, id_number TEXT UNIQUE NOT NULL, firstname TEXT, lastname TEXT, department TEXT, employment_type TEXT, status TEXT DEFAULT 'Active', created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
        "CREATE TABLE IF NOT EXISTS employees_extended (id INTEGER PRIMARY KEY AUTOINCREMENT, employee_id TEXT UNIQUE NOT NULL, company TEXT, position TEXT, location TEXT, section TEXT)",
        "CREATE TABLE IF NOT EXISTS payroll_period_groups (id INTEGER PRIMARY KEY AUTOINCREMENT, pay_type TEXT NOT NULL, group_name TEXT NOT NULL, description TEXT, status TEXT DEFAULT 'active', is_on INTEGER DEFAULT 1, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
        "CREATE TABLE IF NOT EXISTS payroll_periods (id INTEGER PRIMARY KEY AUTOINCREMENT, group_id INTEGER NOT NULL, cut_off TEXT, date_from DATE NOT NULL, date_to DATE NOT NULL, cut_off_day INTEGER, cover_year INTEGER, cover_month TEXT, no_of_days INTEGER, pay_date DATE, description TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id))",
        "CREATE TABLE IF NOT EXISTS employee_group_assignments (id INTEGER PRIMARY KEY AUTOINCREMENT, employee_id TEXT NOT NULL, group_id INTEGER NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE(employee_id, group_id), FOREIGN KEY(employee_id) REFERENCES employees(id_number), FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id))"
    ];
}

try {
    foreach ($queries as $query) {
        $conn->exec($query);
    }
    echo "<div style='color: green; font-family: sans-serif; padding: 20px; border: 1px solid green; background: #e9f7ef;'>
            <h3>Success!</h3>
            <p>Database tables have been created successfully on " . strtoupper($driver) . ".</p>
            <a href='index.php'>Go to Dashboard</a>
          </div>";
} catch(PDOException $e) {
    echo "<div style='color: red; font-family: sans-serif; padding: 20px; border: 1px solid red; background: #fdf2f2;'>
            <h3>Error!</h3>
            <p>" . $e->getMessage() . "</p>
          </div>";
}
?>