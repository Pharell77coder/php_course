CREATE DATABASE IF NOT EXISTS database;

USE database;

SET FOREIGN_KEY_CHECKS=0; -- désactiver la sécurité des clé étrangeres

-- Table des utilisateurs
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des voitures
DROP TABLE IF EXISTS cars;
CREATE TABLE IF NOT EXISTS cars (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NOT NULL,
    price DECIMAL(8,2) CHECK (price >= 0),
    build_at DATE
);

-- Table de jointure
DROP TABLE IF EXISTS cars_users;
CREATE TABLE IF NOT EXISTS cars_users (
    id_user INT NOT NULL,
    id_car INT NOT NULL,
    assigned_at DATE,
    PRIMARY KEY (id_user, id_car),
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_car) REFERENCES cars(id)
);


ALTER TABLE cars_users
DROP FOREIGN KEY cars_users_ibfk_2;

ALTER TABLE cars_users
ADD CONSTRAINT cars_users_ibfk_2
FOREIGN KEY (id_car) REFERENCES cars(id) ON DELETE CASCADE;

-- Insérer un utilisateur administrateur avec un mot de passe haché (hachage simulé ici)
INSERT INTO users (username, email, password) VALUES
('administrateur', 'admin@example.com', '$2y$10$somehash...');

-- Insérer des voitures
INSERT INTO cars (model, brand, price, build_at) VALUES
('Model S', 'Tesla', 79999.99, '2022-01-01'),
('Civic', 'Honda', 24999.99, '2021-05-15');

-- Associer des utilisateurs à des voitures
INSERT INTO cars_users (id_user, id_car, assigned_at) VALUES
(1, 1, '2023-11-01'),
(1, 2, '2023-11-15');

ALTER TABLE cars ADD plate CHAR(10);
UPDATE cars SET plate = CONCAT('AA-', LPAD(id, 3, '0'), '-ZZ');

SET FOREIGN_KEY_CHECKS=1;  -- réactiver la sécurité des clé étrangeres