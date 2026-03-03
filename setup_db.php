<?php
require_once 'config/db.php';

// SQL to create tables
$sql = "
    -- Employee Table
    CREATE TABLE IF NOT EXISTS employees (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_number TEXT UNIQUE NOT NULL,
        firstname TEXT NOT NULL,
        lastname TEXT NOT NULL,
        department TEXT,
        employment_type TEXT, -- Regular, Probationary, etc.
        status TEXT DEFAULT 'Active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- Attendance Table
    CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        employee_id INTEGER,
        date DATE NOT NULL,
        time_in TIME,
        time_out TIME,
        absent INTEGER DEFAULT 0, -- 1 for true
        tardiness_mins INTEGER DEFAULT 0,
        overtime_hrs REAL DEFAULT 0.0,
        FOREIGN KEY (employee_id) REFERENCES employees(id)
    );

    -- Reminders Table
    CREATE TABLE IF NOT EXISTS reminders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        message TEXT NOT NULL,
        author TEXT,
        color_class TEXT DEFAULT 'card-warning',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
";

try {
    $conn->exec($sql);

    // Seed some data if table is empty
    $count = $conn->query("SELECT COUNT(*) FROM reminders")->fetchColumn();
    if ($count == 0) {
        $conn->exec("INSERT INTO reminders (message, author, color_class) VALUES 
            ('Good day team! Please be reminded to plot all the employees schedule every first day of the month.', 'SHERAME BALORIO', 'card-warning'),
            ('Good Day Team! Please be reminded to file TK at the end of the shift and the next day.', 'SHERAME BALORIO', 'bg-orange')
        ");
        echo "Database schema created and seeded successfully!<br>";
    } else {
        echo "Database schema already exists.<br>";
    }

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>
<a href="index.php">Go to Dashboard</a>
