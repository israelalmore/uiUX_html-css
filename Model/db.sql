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

-- Seed de prueba
-- Credenciales de acceso:
--   Email:    test@forever.com
--   Password: Test1234!
INSERT INTO usuarios
    (nombre, apellido1, apellido2, fecha_nacimiento, email, telefono,
     documento, password, tipo_usuario_id, tipo_documento_id)
VALUES
    ('Cristofer', 'Test', 'Demo', '1995-04-12',
     'test@forever.com', '600111222',
     '12345678Z',
     '$2b$12$LQcWMj5s.X/DzodFoaVFsu5jqOarn.tpfe3Px5Z9ON56pkAlnSI0e',
     1, 1);

INSERT INTO eventos
    (titulo, descripcion, categoria, fecha, hora,
     direccion, codigo_postal, ciudad, email,
     imagen_portada, organizador_id)
VALUES
    ('Concierto Forever Demo',
     'Evento de prueba creado automaticamente para validar la pagina de detalles. Incluye imagen de portada y datos de organizador.',
     'Arte y Cultura',
     '2026-06-15', '20:00:00',
     'Calle Ramon Llull, 67', '08018', 'Barcelona',
     'demo@forever.com',
     '../Assets/img/images/events.jpg',
     1);
