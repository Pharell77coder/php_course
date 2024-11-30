CREATE DATABASE IF NOT EXISTS database;

USE database;

DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion de données fictives
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', 'password'),
('JohnDoe', 'john@example.com', 'password1'),
('JaneDoe', 'jane@example.com', 'password2');
