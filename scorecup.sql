CREATE DATABASE IF NOT EXISTS scorecup CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scorecup;

DROP TABLE IF EXISTS palpites;
DROP TABLE IF EXISTS jogos;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    time_a VARCHAR(100) NOT NULL,
    time_b VARCHAR(100) NOT NULL,
    data_jogo DATETIME NOT NULL,
    status ENUM('pendente', 'finalizado', 'cancelado') NOT NULL DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE palpites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jogo_id INT NOT NULL,
    placar_a INT NOT NULL DEFAULT 0,
    placar_b INT NOT NULL DEFAULT 0,
    pontos INT NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_palpites_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_palpites_jogo FOREIGN KEY (jogo_id) REFERENCES jogos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (nome, email, senha) VALUES
('Administrador', 'admin@scorecup.com', '123456'),
('Daniel', 'daniel@scorecup.com', '123456');

INSERT INTO jogos (time_a, time_b, data_jogo, status) VALUES
('Brasil', 'Argentina', '2026-06-25 16:00:00', 'pendente'),
('França', 'Alemanha', '2026-06-26 18:00:00', 'pendente'),
('Espanha', 'Portugal', '2026-06-27 15:30:00', 'pendente');

INSERT INTO palpites (user_id, jogo_id, placar_a, placar_b, pontos) VALUES
(1, 1, 2, 1, 3),
(2, 1, 1, 1, 1);
