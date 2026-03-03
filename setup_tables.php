<?php
require_once 'config/db.php';

$sql = "
CREATE TABLE IF NOT EXISTS advance_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS banks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    code TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees_extended (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id TEXT UNIQUE NOT NULL,
    company TEXT,
    position TEXT,
    location TEXT,
    section TEXT
);

CREATE TABLE IF NOT EXISTS payroll_period_groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pay_type TEXT NOT NULL,
    group_name TEXT NOT NULL,
    description TEXT,
    status TEXT DEFAULT 'active',
    is_on INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payroll_periods (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_id INTEGER NOT NULL,
    cut_off TEXT,
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    cut_off_day INTEGER,
    cover_year INTEGER,
    cover_month TEXT,
    no_of_days INTEGER,
    pay_date DATE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id)
);

CREATE TABLE IF NOT EXISTS employee_group_assignments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id TEXT NOT NULL,
    group_id INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(employee_id, group_id),
    FOREIGN KEY(employee_id) REFERENCES employees(id_number),
    FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id)
);
";

try {
    $conn->exec($sql);
    echo "<div style='color: green; font-family: sans-serif; padding: 20px; border: 1px solid green; background: #e9f7ef;'>
            <h3>Success!</h3>
            <p>Database tables for Administrator modules have been created successfully.</p>
            <a href='index.php'>Go to Dashboard</a>
          </div>";
} catch(PDOException $e) {
    echo "<div style='color: red; font-family: sans-serif; padding: 20px; border: 1px solid red; background: #fdf2f2;'>
            <h3>Error!</h3>
            <p>" . $e->getMessage() . "</p>
          </div>";
}
?>