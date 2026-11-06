# Análisis del defecto del umbral

Estado: análisis preparado a partir del ejemplo de la guía. No es una traza de ejecución real ni evidencia del ejercicio completado.

## Esperado y obtenido

Al cambiar temporalmente > por >= en calcularDescuento, subtotal 100.00 recibe 5.00; el test espera 0.00. La regla R2 exige estrictamente mayor a 100.00.

## Hipótesis por probabilidad

1. Defecto del código: el operador >= incluye el borde. Verificación en un minuto: abrir ServicioVenta.php y comparar la condición con R2.
2. Defecto del test: podría usar esperado incorrecto. Verificación: contrastar el literal 0.0 con la regla de exactamente 100.00.
3. Defecto del dato: podría llegar 100.01 en vez de 100.00. Verificación: comprobar el argumento literal del test y la salida de PHPUnit.

## Cambio mínimo

Restaurar > en vez de >=. No cambiar el esperado del test. El código incluido ya utiliza >.

## Ejecución pendiente

1. Ejecutar unitarias antes de mutar y guardar salida.
2. Cambiar únicamente el operador en copia local.
3. Ejecutar el test del umbral y pegar la traza real aquí.
4. Decidir la hipótesis antes de corregir.
5. Restaurar >, ejecutar ambas suites y conservar el commit correspondiente.

Traza real: PENDIENTE. Decisión personal: PENDIENTE. Resultado tras restaurar: PENDIENTE.
