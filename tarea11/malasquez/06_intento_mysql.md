# Bonificación · intento MySQL
Estado: no ejecutado; esta plantilla no acredita la bonificación.

- Fecha y versión de n8n: PENDIENTE.
- Nodo: MySQL Tool; operación Execute Query.
- Credencial: crear en Credentials con los datos del panel; no pegarlos aquí.
- Consulta de la consigna: SELECT nombre, precio, stock FROM producto;
- Antes de ejecutar, verificar el nombre y las columnas reales de la tabla en phpMyAdmin.
- Resultado real (conectó / falló): PENDIENTE.
- Mensaje exacto de error, ocultando contraseñas y datos sensibles: PENDIENTE.
- Evidencia o ID de ejecución: PENDIENTE.

## Pieza intermedia · orientación para redactar tres líneas propias
Una API HTTPS alojada junto a la base puede exponer operaciones de lectura específicas para el agente.
La API valida la autenticación y los parámetros y usa un usuario MySQL de solo lectura, sin revelar sus credenciales.
n8n consulta ese endpoint con HTTP Request Tool; la base permanece protegida y no se admiten consultas SQL arbitrarias del modelo.

No registrar ETIMEDOUT como resultado si no fue observado realmente. Si la conexión directa funciona, documentar ese resultado.
