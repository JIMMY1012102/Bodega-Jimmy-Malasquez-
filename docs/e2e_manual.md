# Pruebas manuales de extremo a extremo

Estado: PENDIENTE de ejecución en navegador por la pareja. No se han registrado resultados reales. Usar una BD local de laboratorio con los seis productos semilla; no reinicializar una base con datos reales.

URL local: http://localhost/bodega/ . URL pública: PENDIENTE de despliegue.
Driver: ______. Navigator: ______. Fecha y hora: ______.

| Escenario y paso | Acción en pantalla | Dato exacto | Resultado esperado visible | Resultado real |
| --- | --- | --- | --- | --- |
| A1 | Abrir index.php y anotar stock | Arroz 50; aceite 20 | Se observan los productos | Pendiente |
| A2 | Nueva venta; ingresar cliente | Cliente E2E | Campo completo | Pendiente |
| A3 | Elegir arroz, agregar otra línea con aceite | Arroz 15; aceite 5 | Dos líneas | Pendiente |
| A4 | Confirmar venta | Subtotal 120.00 | Descuento 6.00; total 114.00; número de venta | Pendiente |
| A5 | Ver stock actualizado | Arroz y aceite | Arroz 35; aceite 15 | Pendiente |
| B1 | Abrir nueva venta | Cliente E2E; arroz 36 después de A | Formulario completo | Pendiente |
| B2 | Confirmar | Cantidad 36, stock 35 | Mensaje stock insuficiente | Pendiente |
| B3 | Volver al inventario | Arroz y aceite | Continúan 35 y 15 | Pendiente |
| C1 | Nueva venta, cantidad cero | Arroz 0 | Navegador rechaza por min=1 | Pendiente |
| C2 | En herramientas del navegador desactivar validación del formulario y enviar cero | novalidate; cantidad 0; CSRF vigente | Servidor responde 422 con mensaje de entero positivo | Pendiente |
| C3 | Ver inventario | Arroz y aceite | Continúan 35 y 15 | Pendiente |

No marcar C como aprobado solo por la validación HTML: hay que confirmar el rechazo en el servidor. Si se ejecutan en otro orden, anotar stocks iniciales y esperados ajustados. Actualizar una confirmación no debe registrar otra venta.

## Umbral en la URL pública

Con semilla disponible, arroz 25 × 4.00 produce subtotal 100.00 y descuento 0.00. Los precios semilla son múltiplos de 0.50: no permiten una venta de 100.01. Para esa evidencia, en una base de demo exclusiva cambiar temporalmente el precio del arroz a 4.01 y vender arroz 1 + aceite 8 = 100.01; esperado descuento 5.00 y total 95.01. Restaurar precio 4.00 tras la prueba, con permiso del dueño de la demo. No modificar precios en un sistema real. Anotar resultados y URL aquí.
