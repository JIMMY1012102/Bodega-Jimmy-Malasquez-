# Requisitos de la bodega

Borrador asistido por IA basado directamente en la guía S11v2. Validar contra el D0 personal antes de entregar. Sin autenticación, reportes ni facturación electrónica. Los productos iniciales se registran por SQL; esta versión no incluye mantenimiento web del catálogo.

## HU1 Consultar productos
Como vendedor quiero consultar precio y stock para conocer qué puedo vender.

WHEN el vendedor abre index.php THE SYSTEM SHALL mostrar nombre, precio y stock de cada producto.

## HU2 Registrar una venta
Como vendedor quiero registrar el cliente y varias líneas para atender una compra.

WHEN se confirma una compra THE SYSTEM SHALL calcular sus importes con los precios almacenados en la base de datos.

WHEN la cantidad solicitada supera el stock THE SYSTEM SHALL rechazar la venta sin cambios persistidos (R1).
WHEN la cantidad solicitada iguala el stock THE SYSTEM SHALL aceptar la venta y dejar el stock en cero (R1).
WHEN el subtotal es mayor a S/ 100,00 THE SYSTEM SHALL aplicar 5 % de descuento (R2).
WHEN el subtotal es exactamente S/ 100,00 THE SYSTEM SHALL aplicar descuento cero (R2).
WHEN la cantidad es cero, negativa o decimal THE SYSTEM SHALL rechazar la venta (R3).
WHEN la cantidad es un entero positivo y existe stock THE SYSTEM SHALL permitir la venta (R3).
WHEN se confirma la venta THE SYSTEM SHALL guardar cabecera, detalles y reducción de stock en una única transacción (R4).
WHEN ocurre un error al persistir THE SYSTEM SHALL revertir toda la transacción (R4).

## HU3 Consultar confirmación
Como vendedor quiero ver subtotal, descuento y total para informar el monto al cliente.

WHEN se registra una venta THE SYSTEM SHALL mostrar su número, subtotal, descuento y total y ofrecer volver al inventario.

## Reglas literales de la guía
- R1 · Stock: No se puede vender una cantidad mayor al stock disponible del producto. Si cantidad == stock, la venta es válida y el stock queda en 0.
- R2 · Descuento: Si el subtotal de la venta es mayor a S/ 100,00 se aplica 5 % de descuento sobre el subtotal. Si es exactamente S/ 100,00 no hay descuento.
- R3 · Cantidad: La cantidad debe ser un entero mayor que cero. Cero, negativos y decimales se rechazan.
- R4 · Actualización: Al confirmar la venta, el stock de cada producto se reduce en la cantidad vendida, en la misma transacción.
