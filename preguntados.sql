CREATE DATABASE preguntados_db;
    USE preguntados_db;
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       nombre_completo VARCHAR(100) NOT NULL,
                       anio_nacimiento YEAR NOT NULL,
                       sexo ENUM('Masculino', 'Femenino', 'Prefiero no cargarlo') NOT NULL,
                       pais VARCHAR(100) NOT NULL,
                       ciudad VARCHAR(100) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
                       foto_perfil VARCHAR(255),
                       token_verificacion VARCHAR(255) NOT NULL,
                       verificado BOOLEAN DEFAULT 0,
                       fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);
