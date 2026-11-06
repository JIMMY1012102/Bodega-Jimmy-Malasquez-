# Revisión de seguridad

Revisión estática asistida por IA. No sustituye la confirmación manual T8 ni una auditoría de producción. Las categorías siguen la clasificación OWASP 2021 solicitada por la guía.

| Categoría | Evidencia en código | Tratamiento y estado |
| --- | --- | --- |
| A03 Inyección SQL | RepositorioMySQL usa prepare y execute para las entradas | No se concatena entrada del usuario a SQL; falta ejecución de payloads HTTP |
| A03 Salida HTML | config/web.php define e con htmlspecialchars; vistas usan e | Escape de cliente/mensajes y datos de BD; falta prueba navegador |
| A01 CSRF | guardar_venta.php verifica hash_equals antes de registrar | Petición sin token devuelve 403; pendiente ejecución HTTP |
| A02 Secretos | .gitignore excluye env.php y env_test.php | Solo plantillas con valores locales; hosting debe usar HTTPS |
| A05 Configuración | web.php desactiva display_errors; .htaccess niega carpetas internas | Comprobar Apache del hosting: .htaccess no protege servidores Nginx o PHP integrado |
| A09 Registro | catch de infraestructura llama error_log | No muestra SQL ni rutas al cliente; revisar permisos y retención del log en hosting |

## Límites de acceso

La guía excluye autenticación. Una URL pública permite que cualquier visitante registre ventas. Usar únicamente datos ficticios de demo; no usar esta versión como sistema real de la bodega. Para explotación real se necesita acordar control de acceso, fuera del alcance del laboratorio.

## Prueba manual pendiente

Con un CSRF válido, enviar producto_id `1 OR 1=1` y luego `' OR '1'='1`. Esperado: HTTP 422, producto inválido y ninguna venta ni cambio de stock. Verificar antes y después en la BD. La prueba de unidad de entrada manipulada cubre solo el validador; no demuestra el flujo HTTP.

| Prueba | Resultado esperado | Resultado real |
| --- | --- | --- |
| producto_id 1 OR 1=1 | Rechazo sin persistencia | Pendiente |
| producto_id ' OR '1'='1 | Rechazo sin persistencia | Pendiente |
| POST sin CSRF | 403; sin venta | Pendiente |
| GET a guardar_venta.php | 405; sin venta | Pendiente |
| Abrir /config/env.php, /sql/bodega.sql, /.git/config en hosting | 403 o 404 sin contenido | Pendiente |

## Confirmación personal

Abrir las líneas citadas en docs/mapa_codigo.md, registrar hallazgos reales con severidad y evidencia. No se inventaron vulnerabilidades históricas ni se atribuyeron correcciones no realizadas. No publicar credenciales ni capturas con datos personales.
