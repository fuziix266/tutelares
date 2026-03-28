CREATE TABLE IF NOT EXISTS configuracion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(255) UNIQUE NOT NULL,
  valor TEXT
);

INSERT INTO configuracion (clave, valor) VALUES ('portal_status', 'activo') ON DUPLICATE KEY UPDATE valor = 'activo';

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol VARCHAR(50),
  nombre VARCHAR(255),
  imagen VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS noticias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  categoria INT,
  resumen TEXT,
  contenido LONGTEXT,
  imagen VARCHAR(255),
  autor INT,
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
