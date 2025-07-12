CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    telefono VARCHAR(20),
    fecha_registro DATE NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    pagado BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;