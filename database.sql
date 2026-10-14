CREATE DATABASE IF NOT EXISTS finance_tracker;
USE finance_tracker;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    category VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (full_name, email, password, profile_pic)
SELECT 'Demo User', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'demo@example.com'
);

-- Demo password is: password
