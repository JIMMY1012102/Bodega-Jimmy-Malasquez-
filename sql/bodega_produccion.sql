-- Importar en una base vacía seleccionada desde el panel.
CREATE TABLE producto (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(80) NOT NULL,
 precio DECIMAL(8,2) NOT NULL,
 stock INT NOT NULL,
 CHECK (precio >= 0), CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE venta (
 id INT AUTO_INCREMENT PRIMARY KEY,
 fecha DATETIME NOT NULL,
 cliente VARCHAR(80) NOT NULL,
 subtotal DECIMAL(10,2) NOT NULL,
 descuento DECIMAL(10,2) NOT NULL,
 total DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE detalle_venta (
 id INT AUTO_INCREMENT PRIMARY KEY,
 venta_id INT NOT NULL,
 producto_id INT NOT NULL,
 cantidad INT NOT NULL,
 precio_unitario DECIMAL(8,2) NOT NULL,
 importe DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (venta_id) REFERENCES venta(id),
 FOREIGN KEY (producto_id) REFERENCES producto(id),
 CHECK (cantidad > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO producto (nombre,precio,stock) VALUES
('Arroz 1 kg',4.00,50),('Aceite 1 L',12.00,20),('Leche',4.50,30),
('Azúcar 1 kg',3.50,40),('Atún',6.00,10),('Gaseosa',2.50,0);
