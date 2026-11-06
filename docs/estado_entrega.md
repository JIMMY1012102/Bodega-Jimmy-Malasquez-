# Estado de entrega

Fecha de preparación: 6 de noviembre de 2026, Perú. Código y documentación generados con IA desde la guía S11v2 proporcionada por el usuario.

| Evidencia | Preparado | Pendiente |
| --- | --- | --- |
| D0 | Plantilla y reglas | Redacción personal sin IA |
| E1 30 % individual | Plan, 18 casos, auditoría de suite asistida | Revisión y decisiones de cada estudiante |
| E2 40 % pareja | 20 pruebas unitarias, 7 de integración, guion E2E y análisis de ejemplo | E2E manual, traza de mutación y evidencias de pareja |
| E3 30 % pareja | Protecciones de código, revisión estática, SQL de producción y README | Confirmación OWASP, publicación y prueba desde celular |
| GitHub | Proyecto preparado para el repositorio público JIMMY1012102/Bodega-Jimmy-Malasquez- | CI aprobado; ver resultados_ci.md |

El entorno local de preparación no dispone de PHP/MySQL. Las suites se ejecutaron después en GitHub Actions: 27 pruebas, 42 aserciones, todas aprobadas sobre PHP 8.3.36 y MySQL 8.0.46. Ver resultados_ci.md. No hay URL pública todavía. No se afirma una nota, una aprobación docente ni cumplimiento completo de las evidencias personales.

La tarea individual de semana 12 con n8n se entrega en documento aparte y no se implementa dentro de esta aplicación PHP.

## Comprobaciones realizadas en la preparación

Se verificaron la sintaxis de JavaScript con node --check, la lectura de phpunit.xml y del workflow YAML, la presencia de 20 métodos unitarios y 7 de integración, los destinos de enlaces Markdown y require PHP, la ausencia de configuraciones privadas y que SQL de producción no incluye CREATE DATABASE ni USE. Estas comprobaciones estáticas no equivalen a ejecutar PHPUnit o MySQL.
