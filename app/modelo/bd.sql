CREATE DATABASE wifi_management;

USE wifi_management;

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(32) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    mac_address VARCHAR(17) UNIQUE NOT NULL,
    tiempo_horas INT NOT NULL,
    estado ENUM('activo', 'bloqueado') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dispositivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    mac_address VARCHAR(17),
    ip_address VARCHAR(15),
    estado ENUM('conectado', 'desconectado'),
    ultima_conexion TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE conexiones_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    mac_address VARCHAR(17),
    fecha_conexion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_desconexion TIMESTAMP NULL,
    duracion_minutos INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Insertar admin por defecto (password: admin123)
INSERT INTO administradores (username, password) VALUES ('joseGZ', MD5('jose2025'));