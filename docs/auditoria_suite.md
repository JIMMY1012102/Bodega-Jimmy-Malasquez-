# Auditoría de la suite

Revisión estática asistida por IA; pendiente de validación y aplicación personal T4. No se atribuye una revisión humana. Los casos de excepción esperan una clase concreta; los casos de cálculo comparan contra valores literales.

| Método | Técnica | Esperado | Nombre descriptivo |
| --- | --- | --- | --- |
| testAceptaVentaIgualAlStock | Límite | Literal | Sí |
| testRechazaVentaMayorAlStock | Límite | Literal | Sí |
| testAceptaCantidadUnoMenosQueStock | Límite | Literal | Sí |
| testRechazaProductoAgotado | Límite | Literal | Sí |
| testDescuentoCeroCuandoSubtotalEsExactamente100 | Límite | Literal | Sí |
| testDescuentoCeroPorDebajoDelUmbral | Límite | Literal | Sí |
| testAplicaDescuentoSobreElUmbral | Límite | Literal | Sí |
| testCalculaTotalSobreElUmbral | Límite | Literal | Sí |
| testRechazaCantidadCero | Límite | Literal | Sí |
| testRechazaCantidadNegativa | Límite | Literal | Sí |
| testRechazaCantidadDecimal | Límite | Literal | Sí |
| testRechazaDecimalRecibidoDelFormulario | Partición o caso funcional | Literal | Sí |
| testAceptaCantidadUno | Límite | Literal | Sí |
| testAceptaEnteroRecibidoDelFormulario | Partición o caso funcional | Literal | Sí |
| testCalculaSubtotalDeDosLineas | Partición o caso funcional | Literal | Sí |
| testAgrupaLineasDelMismoProducto | Partición o caso funcional | Literal | Sí |
| testRechazaProductoConInyeccionSQL | Partición o caso funcional | Excepción concreta | Sí |
| testRechazaVentaSinProductos | Partición o caso funcional | Excepción concreta | Sí |
| testRechazaCantidadDecimalEnLasLineas | Límite | Excepción concreta | Sí |
| testCalculaTotalSinDescuentoEn100 | Límite | Literal | Sí |

## Decisiones pendientes del estudiante

No hay tests triviales assertTrue(true) ni esperados que repitan la fórmula. Las pruebas de cantidades del formulario complementan a las de números para detectar conversiones silenciosas. Los casos de cálculo con dos líneas son funcionales y no reemplazan las pruebas del umbral.

El estudiante debe confirmar cada fila, corregir los tests débiles que identifique, ejecutar y conservar un commit con su revisión. Las pruebas de R4 viven en integración, no en esta suite. No hay evidencia de concurrencia simultánea: el bloqueo se revisó estáticamente.
