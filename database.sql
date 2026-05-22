CREATE DATABASE IF NOT EXISTS peersync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

       USE peersync;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('APPRENANT', 'ADMIN') DEFAULT 'APPRENANT',
                       points INT DEFAULT 0
);

CREATE TABLE tags (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE user_tags (
                           user_id INT NOT NULL,
                           tag_id INT NOT NULL,
                           type ENUM('maitrisee', 'a_travailler') NOT NULL,
                           PRIMARY KEY (user_id, tag_id),
                           FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                           FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

CREATE TABLE help_requests (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               title VARCHAR(255) NOT NULL,
                               description TEXT NOT NULL,
                               status ENUM('PENDING', 'ASSIGNED', 'RESOLVED') DEFAULT 'PENDING',
                               apprenant_id INT NOT NULL,
                               tuteur_id INT DEFAULT NULL,
                               tag_id INT NOT NULL,
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               FOREIGN KEY (apprenant_id) REFERENCES users(id) ON DELETE CASCADE,
                               FOREIGN KEY (tuteur_id) REFERENCES users(id) ON DELETE SET NULL,
                               FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);


CREATE TABLE reviews (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         help_request_id INT NOT NULL UNIQUE, -- Demande w7da 3ndha review w7da
                         rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
                         comment TEXT,
                         FOREIGN KEY (help_request_id) REFERENCES help_requests(id) ON DELETE CASCADE
);


CREATE TABLE badges (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(150) NOT NULL,
                        icon_path VARCHAR(255) NOT NULL,
                        min_points INT NOT NULL
);


CREATE TABLE user_badges (
                             user_id INT NOT NULL,
                             badge_id INT NOT NULL,
                             awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             PRIMARY KEY (user_id, badge_id),
                             FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                             FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);


INSERT INTO tags (name) VALUES ('PHP'), ('POO'), ('Tailwind CSS'), ('React.js'), ('MySQL');
INSERT INTO badges (title, icon_path, min_points) VALUES
                                                      ('Débutant', '/assets/badges/debutant.png', 10),
                                                      ('Expert POO', '/assets/badges/expert_poo.png', 50),
                                                      ('Sauveur de la semaine', '/assets/badges/sauveur.png', 100);

INSERT INTO users (id, name, email, password, role)
VALUES (1, 'Youssef', 'youssef@enaa.ma', '123456', 'APPRENANT');

INSERT INTO tags (id, name)
VALUES (1, 'PHP');