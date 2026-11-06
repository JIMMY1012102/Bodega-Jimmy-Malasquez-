# Plan de pruebas

Borrador asistido por IA para revisión individual E1. Objetivo: comprobar R1–R4 antes de publicar la bodega. Se prueban ServicioVenta, RepositorioMySQL y el flujo de venta; no se prueban usuarios, reportes ni facturación, fuera del alcance.

| Regla | Nivel | Técnica | Datos | Criterio de aceptación |
| --- | --- | --- | --- | --- |
| R1 | Unitaria e integración; E2E | Valores límite | stock 10; cantidad 9, 10, 11; stock 0 y cantidad 1 | Acepta 9 y 10; rechaza 11 y agotado sin cambios |
| R2 | Unitaria; E2E | Valores límite | subtotal 99.99, 100.00, 100.01 | Descuento 0, 0, 5.00 respectivamente |
| R3 | Unitaria; E2E | Valores límite y particiones | -1, 0, 1, 1.5 y POST "1.5" | Solo el entero positivo es válido |
| R4 | Integración; E2E | Tabla de decisión | stock 10, venta 3; segundo producto agotado; fallo SQL durante actualización | Stock 7 y una venta/detalle; cualquier error revierte cabecera, detalles y stock |

## Entorno

PHP 8.3, PHPUnit 11, MySQL 8 o MariaDB compatible. La integración solo se conecta a bodega_test y borra sus datos antes y después de cada caso. No usa env.php. Una conexión ausente o incorrecta falla; no se omite silenciosamente.

## Criterio de salida

100 % de unitarias y de integración en verde, tres escenarios E2E ejecutados y anotados, cero defectos críticos o altos confirmados sin resolver, configuración privada fuera de Git y aceptación desde un celular en la URL pública.

## Responsables y evidencias

Cada integrante debe revisar y entregar su plan individual y su auditoría T4. Las suites y la seguridad/despliegue son de pareja. Completar nombres, fecha, salida de PHPUnit y resultados E2E. No confundir los archivos generados con evidencias ejecutadas.
