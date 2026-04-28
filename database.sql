-- Task Manager Database Schema
-- Purpose: Full DB schema with users, tasks tables and seed data

CREATE DATABASE IF NOT EXISTS task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE task_manager;

-- Users table for authentication
-- Stores hashed passwords only - never plaintext
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- bcrypt hash via password_hash()
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tasks table
-- ON DELETE CASCADE: when a user is deleted, all their tasks are automatically
-- removed too - avoids orphaned rows without needing a manual cleanup query.
-- user_id scopes every task to its owner so cross-user data leaks are
-- impossible even if the auth layer is bypassed accidentally.
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                            -- owner; FK enforces referential integrity
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed data: demo user (password: "password123")
INSERT INTO users (username, email, password) VALUES
('demo_user', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Seed tasks for the demo user (user_id = 1)
INSERT INTO tasks (user_id, title, description, due_date, status) VALUES
(1, 'Set up development environment', 'Install PHP, MySQL, and Apache on local machine.', '2025-05-01', 'completed'),
(1, 'Design database schema', 'Create ERD and write migration SQL files.', '2025-05-05', 'completed'),
(1, 'Build authentication system', 'Implement login, register, and logout flows with session management.', '2025-05-10', 'pending'),
(1, 'Create task CRUD endpoints', 'Implement create, read, update, delete for tasks with validation.', '2025-05-15', 'pending'),
(1, 'Add search and filter functionality', 'Allow filtering tasks by status and searching by title.', '2025-05-20', 'pending'),
(1, 'Implement pagination', 'Limit tasks per page with prev/next navigation.', '2025-05-22', 'pending'),
(1, 'AJAX status toggle', 'Mark task complete/pending without full page reload.', '2025-05-25', 'pending'),
(1, 'Write unit tests', 'Cover auth and task model methods with PHPUnit.', '2025-06-01', 'pending'),
(1, 'Security audit', 'Review for XSS, CSRF, SQL injection, session fixation vulnerabilities.', '2025-06-05', 'pending'),
(1, 'Deploy to production', 'Configure Apache vhost, set env variables, run migrations.', '2025-06-10', 'pending');
