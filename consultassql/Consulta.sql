DROP TABLE user;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_registro DATETIME NOT NULL,  -- ✅ Ahora guarda fecha y hora
    email VARCHAR(150) NOT NULL UNIQUE,
    pagado BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

-- 1
INSERT INTO user (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
VALUES ('Ana', 'Gómez', 'López', 28, '5544332211', '2025-07-12 14:30:00', 'ana.gomez@example.com', TRUE);

-- 2
INSERT INTO user (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
VALUES ('Carlos', 'Martínez', 'Ramírez', 35, '5588776655', '2025-07-12 15:45:00', 'carlos.martinez@example.com', FALSE);

-- 3
INSERT INTO user (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
VALUES ('Luisa', 'Hernández', 'Castillo', 22, '5511223344', '2025-07-12 09:20:00', 'luisa.h@example.com', TRUE);

-- 4
INSERT INTO user (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
VALUES ('Miguel', 'Reyes', 'Fernández', 41, '+34677067350', '2025-07-11 18:00:00', 'miguel.reyes@example.com', FALSE);

-- 5
INSERT INTO user (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
VALUES ('Sara', 'Domínguez', 'Ortiz', 29, '5599887766', '2025-07-12 12:15:00', 'sara.dominguez@example.com', TRUE);
