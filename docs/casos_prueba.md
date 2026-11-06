# Casos de prueba

Borrador E1 para auditar. Los esperados son literales y los importes se expresan en soles.

| ID | Regla | Técnica | Entrada exacta | Resultado esperado | Nivel |
| --- | --- | --- | --- | --- | --- |
| C01 | R1 | Límite | cantidad 9; stock 10 | true | Unitaria |
| C02 | R1 | Límite | cantidad 10; stock 10 | true | Unitaria |
| C03 | R1 | Límite | cantidad 11; stock 10 | false | Unitaria |
| C04 | R1 | Límite | cantidad 1; stock 0 | false | Unitaria |
| C05 | R2 | Límite | subtotal 99.99 | descuento 0.00 | Unitaria |
| C06 | R2 | Límite | subtotal 100.00 | descuento 0.00; total 100.00 | Unitaria |
| C07 | R2 | Límite | subtotal 100.01 | descuento 5.00; total 95.01 | Unitaria |
| C08 | R3 | Límite | cantidad 0 | false | Unitaria |
| C09 | R3 | Límite | cantidad -1 | false | Unitaria |
| C10 | R3 | Límite | cantidad 1 | true | Unitaria |
| C11 | R3 | Partición | cantidad 1.5; POST "1.5" | false en ambos formatos | Unitaria |
| C12 | R2 | Partición | 15 × 4.00 y 5 × 12.00 | subtotal 120.00 | Unitaria |
| C13 | R4 | Decisión | producto 1, stock 10, precio 4.00; vender 3 | stock 7, 1 venta, 1 detalle, total 12.00 | Integración |
| C14 | R1/R4 | Decisión | producto 2, stock 0; vender 1 | excepción; 0 ventas y 0 detalles | Integración |
| C15 | R4 | Decisión | producto 1: vender 3; producto 2 agotado: vender 1 | stock 10; 0 ventas y 0 detalles | Integración |
| C16 | R1 | Partición | producto 1, stock 10; líneas 6 y 5 | excepción; stock 10; 0 ventas | Integración |
| C17 | R1/R4 | Límite | producto 1, stock 10; vender 10 | stock 0; venta registrada | Integración |
| C18 | R4 | Decisión | producto 1, vender 3; fallo SQL al actualizar stock | rollback: stock 10; 0 ventas y 0 detalles | Integración |

C13–C18 no se cubren en ServicioVentaTest: requieren persistencia en bodega_test. Los casos E2E están en e2e_manual.md. Las pruebas adicionales cubren entradas manipuladas, líneas vacías y agrupación de duplicados.
