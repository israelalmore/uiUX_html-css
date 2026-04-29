DROP DATABASE IF EXISTS forever_events;
CREATE DATABASE forever_events;
USE forever_events;

CREATE TABLE tipos_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

INSERT INTO tipos_usuario (nombre) VALUES
('Gestor'),
('Usuario');

CREATE TABLE tipos_documento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL
);

INSERT INTO tipos_documento (nombre) VALUES
('DNI'),
('NIE'),
('Pasaporte'),
('Otro');

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
    tipo_documento_id INT NOT NULL,

    avatar VARCHAR(255) DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (tipo_usuario_id) REFERENCES tipos_usuario(id),
    FOREIGN KEY (tipo_documento_id) REFERENCES tipos_documento(id)
);

CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL,

    fecha DATE NOT NULL,
    hora TIME NOT NULL,

    direccion VARCHAR(255) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL,

    imagen_portada VARCHAR(255) DEFAULT NULL,
    imagen_ubicacion VARCHAR(255) DEFAULT NULL,

    organizador_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (organizador_id) REFERENCES usuarios(id),
    UNIQUE KEY unq_titulo_fecha (titulo, fecha)
);

