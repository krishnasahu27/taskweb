-- Add task priority level
ALTER TABLE tasks
ADD COLUMN priority ENUM('low', 'medium', 'high') DEFAULT 'medium';

-- Add task deadline
ALTER TABLE tasks
ADD COLUMN deadline DATE;

-- Add task comments table
CREATE TABLE task_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT,
    user_id INT,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Add task categories
CREATE TABLE task_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Add category relationship to tasks
ALTER TABLE tasks
ADD COLUMN category_id INT,
ADD FOREIGN KEY (category_id) REFERENCES task_categories(id) ON DELETE SET NULL;

-- Insert default task categories
INSERT INTO task_categories (name, description) VALUES
('Onboarding', 'Tasks related to new employee onboarding'),
('Training', 'Employee training and development tasks'),
('Project', 'Project-related tasks'),
('Administrative', 'Administrative and management tasks');

-- Add task completion tracking
ALTER TABLE tasks
ADD COLUMN completed_at TIMESTAMP NULL,
ADD COLUMN completion_notes TEXT;

-- Add user department
ALTER TABLE users
ADD COLUMN department VARCHAR(50),
ADD COLUMN join_date DATE DEFAULT CURRENT_DATE;

-- Create task history table
CREATE TABLE task_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT,
    user_id INT,
    action VARCHAR(50),
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Add indexes for better performance
CREATE INDEX idx_task_status ON tasks(status);
CREATE INDEX idx_task_assigned ON tasks(assigned_to);
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_task_category ON tasks(category_id);

-- Task Progress Updates table
CREATE TABLE task_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    progress_text TEXT NOT NULL,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Add updated_at and completed_at columns to tasks table
ALTER TABLE tasks ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE tasks ADD COLUMN completed_at TIMESTAMP NULL DEFAULT NULL;

-- Create task_history table if it doesn't exist
CREATE TABLE IF NOT EXISTS task_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_status VARCHAR(20),
    new_status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);