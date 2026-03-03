CREATE TABLE IF NOT EXISTS advance_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS banks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_number VARCHAR(100) UNIQUE NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    department VARCHAR(100),
    employment_type VARCHAR(100),
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees_extended (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(100) UNIQUE NOT NULL,
    company VARCHAR(100),
    position VARCHAR(100),
    location VARCHAR(100),
    section VARCHAR(100),
    FOREIGN KEY(employee_id) REFERENCES employees(id_number) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS payroll_period_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pay_type VARCHAR(50) NOT NULL,
    group_name VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'active',
    is_on TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payroll_periods (
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
);

CREATE TABLE IF NOT EXISTS employee_group_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(100) NOT NULL,
    group_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(employee_id, group_id),
    FOREIGN KEY(employee_id) REFERENCES employees(id_number) ON DELETE CASCADE,
    FOREIGN KEY(group_id) REFERENCES payroll_period_groups(id) ON DELETE CASCADE
);
