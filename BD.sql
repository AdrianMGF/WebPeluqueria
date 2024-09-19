CREATE DATABASE IF NOT EXISTS peluqueria;

USE peluqueria;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    rol ENUM('Administrador', 'Peluquero', 'Cliente') NOT NULL
);

CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    duracion INT NOT NULL,  -- Duración en minutos
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    peluquero_id INT,
    servicio_id INT,
    fecha_hora DATETIME NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Completada', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (peluquero_id) REFERENCES usuarios(id),
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peluquero_id INT,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (peluquero_id) REFERENCES usuarios(id)
);

-- Insertar servicios de ejemplo
INSERT INTO servicios (nombre, duracion, precio) VALUES 
('Corte de Cabello', 30, 15.00),
('Tinte de Cabello', 90, 50.00),
('Peinado', 45, 25.00),
('Manicura', 60, 20.00),
('Pedicura', 60, 30.00),
('Afeitado Clásico', 30, 12.00),
('Tratamiento Capilar', 60, 40.00);

-- Opcional: Insertar un administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Admin', 'admin@peluqueria.com', MD5('admin123'), 'Administrador');
