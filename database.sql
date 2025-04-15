-- Create the database
CREATE DATABASE IF NOT EXISTS taskweb;
USE taskweb;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'staff') NOT NULL,
    manager_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tasks table
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'flagged', 'completed') DEFAULT 'pending',
    assigned_to INT,
    assigned_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin123)
INSERT INTO users (email, password, role) VALUES 
('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample manager
INSERT INTO users (email, password, role) VALUES 
('manager@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager');

-- Insert sample staff
INSERT INTO users (email, password, role, manager_id) VALUES 
('staff1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 2);

-- Sample tasks
INSERT INTO tasks (title, description, assigned_to, assigned_by) VALUES
('Intro to QA and QA Website', 'Check if they have explored the website. Nudge them to give it a thorough read', 3, 1),
('Profile creation on Zoho', 'Guide them to create their profile on Zoho and provide a walk-through of the platform', 3, 1),
('Interdependencies with other teams at QA', 'Involve new team member in meetings to give an orientation to common collaborations', 3, 2),
('Handover of team management responsibilities', 'Gradually hand over Team Management responsibilities:\nShare details about the team members including their JD, RFS Goals, Current progress on RFS goals, History of promotion/ growth, Any disciplinary or behaviour issues, Their contribution to org spaces.', 3, 2);