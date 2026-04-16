CREATE DATABASE forever_events;
USE forever_events;
 
CREATE TABLE tipos_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);
INSERT INTO tipos_usuario (nombre) VALUES
('Gestor'),
('Usuario'),
('Administrador'),
('Moderador'),
('Invitado');
 
CREATE TABLE idiomas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);
INSERT INTO idiomas (nombre) VALUES
('Español'),
('Inglés'),
('Catalán'),
('Francés'),
('Alemán');
 
CREATE TABLE tipos_documento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL
);
INSERT INTO tipos_documento (nombre) VALUES
('DNI'),
('NIE'),
('NIF'),
('Pasaporte'),
('Otro');
 
CREATE TABLE direcciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(10),
    provincia VARCHAR(100)
);
INSERT INTO direcciones (ciudad, codigo_postal, provincia) VALUES
('Madrid', '28013', 'Madrid'),
('Barcelona', '08001', 'Barcelona'),
('Valencia', '46001', 'Valencia'),
('Sevilla', '41001', 'Sevilla'),
('Bilbao', '48001', 'Vizcaya');
 
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido1 VARCHAR(100) NOT NULL,
    apellido2 VARCHAR(100),
    fecha_nacimiento DATE NOT NULL,
 
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
 
    documento VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
 
    tipo_usuario_id INT NOT NULL,
    idioma_id INT NOT NULL,
    tipo_documento_id INT NOT NULL,
    direccion_id INT,
 
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 
    FOREIGN KEY (tipo_usuario_id) REFERENCES tipos_usuario(id),
    FOREIGN KEY (idioma_id) REFERENCES idiomas(id),
    FOREIGN KEY (tipo_documento_id) REFERENCES tipos_documento(id),
    FOREIGN KEY (direccion_id) REFERENCES direcciones(id)
);
INSERT INTO usuarios (nombre, apellido1, apellido2, fecha_nacimiento, email, telefono, documento, password, tipo_usuario_id, idioma_id, tipo_documento_id, direccion_id) VALUES
('Alejandro', 'García', 'López', '1990-03-15', 'alejandro.garcia@example.com', '612345678', '12345678A', 'password1', 1, 1, 1, 1),
('Sofía', 'Martínez', 'Pérez', '1985-06-22', 'sofia.martinez@example.com', '622345679', 'X1234567B', 'password2', 2, 2, 2, 2),
('María', 'Hernández', 'Soler', '1992-11-03', 'maria.hernandez@example.com', '632345680', 'Y2345678C', 'password3', 3, 3, 3, 3),
('David', 'Ruiz', 'Fernández', '1988-02-10', 'david.ruiz@example.com', '642345681', 'Z3456789D', 'password4', 4, 4, 4, 4),
('Lucía', 'Santos', 'Gómez', '1995-09-29', 'lucia.santos@example.com', '652345682', 'A4567890E', 'password5', 5, 5, 5, 5);