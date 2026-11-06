# Bodega Jimmy Malasquez

Aplicación de ventas del laboratorio de Desarrollo con IA, semana 11, UNDC. PHP sin framework, PDO y MySQL. Catálogo de seis productos, ventas con varias líneas, descuento de 5 % solo cuando el subtotal supera S/ 100.00 y actualización transaccional del stock.

Repositorio público: https://github.com/JIMMY1012102/Bodega-Jimmy-Malasquez-

**Estado:** código publicado; 27 pruebas y 42 aserciones aprobadas en GitHub Actions con PHP 8.3.36, PHPUnit 11.5.57 y MySQL 8.0.46. La revisión de sintaxis PHP también pasó. Navegador y despliegue aún pendientes de ejecución. Ver [resultado de CI](docs/resultados_ci.md) y [estado de entrega](docs/estado_entrega.md). URL pública: **pendiente**.

## Requisitos

PHP 8.3 o superior para PHPUnit 11; extensiones pdo_mysql, dom, mbstring, xml y xmlwriter. MySQL 8 o MariaDB compatible. Apache mediante XAMPP o instalación equivalente. Git y PHPUnit 11 como herramienta de desarrollo; no se usa Composer ni framework. Bootstrap se carga por CDN y requiere conexión para sus estilos; la lógica funciona sin él.

## Instalación con XAMPP en Windows

1. Copiar o clonar el proyecto en `C:\xampp\htdocs\bodega`.
2. Iniciar Apache y MySQL en XAMPP.
3. Abrir phpMyAdmin e importar `sql/bodega.sql` **una sola vez en una base vacía**. Alternativa en CMD:

```bat
cd C:\xampp\htdocs\bodega
C:\xampp\mysql\bin\mysql.exe -u root < sql\bodega.sql
copy config\env.ejemplo.php config\env.php
```

4. Editar `config/env.php` con los datos locales. La plantilla prioriza DB_HOST, DB_PORT, DB_NAME, DB_USER y DB_PASS si están definidos.
5. Abrir http://localhost/bodega/ . Debe mostrar seis productos; gaseosa tiene stock cero.
6. Registrar una venta de arroz 3: subtotal 12.00, descuento cero y stock de arroz 47.

No ejecutar el SQL de nuevo sobre una base que ya tiene tablas. No hay scripts de borrado o reinicialización de la base de ventas.

## Instalación local en Linux

Colocar el proyecto en la carpeta servida por Apache, por ejemplo `/var/www/html/bodega`. Crear la base desde phpMyAdmin o con un usuario autorizado:

```bash
mysql -u TU_USUARIO -p < sql/bodega.sql
cp config/env.ejemplo.php config/env.php
```

Editar env.php y abrir http://localhost/bodega/ . En Lubuntu no necesitas XAMPP si ya tienes Apache, PHP y MariaDB. Ajusta permisos para que Apache lea el proyecto, sin dar escritura a todos ni usar chmod 777. Conservar .htaccess y habilitar AllowOverride en Apache.

## Pruebas con PHPUnit 11

Descargar PHPUnit 11 PHAR desde https://phar.phpunit.de/phpunit-11.phar y guardarlo como `phpunit.phar` en la raíz; verificar su integridad siguiendo las instrucciones oficiales de PHPUnit. No subir el PHAR a Git.

```bash
php phpunit.phar --testsuite unitarias
mysql -u TU_USUARIO -p < sql/bodega_test.sql
cp config/env_test.ejemplo.php config/env_test.php
# Editar únicamente con credenciales de la base de laboratorio bodega_test.
php phpunit.phar --testsuite integracion
php phpunit.phar
```

En Windows usar `C:\xampp\php\php.exe` en lugar de `php`, `copy` en lugar de `cp` y el cliente MySQL de XAMPP para importar el SQL. La base bodega_test debe estar vacía antes de importar el esquema por primera vez.

Integración borra los datos de **bodega_test** al preparar/limpiar cada test. El usuario de prueba necesita SELECT, INSERT, UPDATE, DELETE y TRIGGER en esa base. El trigger se crea y elimina únicamente en una prueba para provocar un fallo de escritura y demostrar rollback. La aplicación no utiliza triggers. La suite rechaza cualquier nombre de base distinto de bodega_test y nunca lee env.php.

Se prepararon 20 unitarias y 7 pruebas de integración. La ejecución continua configurada en `.github/workflows/pruebas.yml` importa MySQL de prueba y ejecuta PHPUnit en cada push/PR. Consultar su resultado en la pestaña Actions del repositorio después de cada subida.

Ejecutar también [el guion E2E](docs/e2e_manual.md), [la seguridad](docs/seguridad.md) y [el ejercicio de fallo](docs/analisis_fallo.md); completar con resultados reales.

## Despliegue en InfinityFree

Procedimiento de la guía; no se ha ejecutado en una cuenta real desde este proyecto.

1. Crear cuenta en https://www.infinityfree.com y un hosting con subdominio. Esperar activación.
2. En el panel MySQL Databases crear una base; anotar host, nombre, usuario y contraseña. No asumir localhost.
3. Entrar a phpMyAdmin del hosting, seleccionar esa base vacía e importar `sql/bodega_produccion.sql`, que no contiene CREATE DATABASE ni USE.
4. Subir a htdocs los archivos web de raíz, assets/, src/ y config/. Conservar los .htaccess, incluidos los de src/ y config/. No subir .git/, .github/, docs/, sql/, tests/, phpunit.phar, phpunit.xml ni archivos Markdown.
5. Copiar el contenido de config/env.ejemplo.php al nuevo config/env.php en el hosting y establecer los cuatro datos del panel; ese archivo nunca se versiona. Usar puerto 3306 salvo indicación del panel.
6. Confirmar PHP compatible, habilitar HTTPS y probar el catálogo. Revisar que /config/env.php y /.git/config no entreguen contenido. Los .htaccess requieren Apache compatible; no asumir que funcionan en otro servidor.
7. Desde un celular con datos móviles, registrar una venta ficticia y verificar el stock, descuento y total. Anotar URL y resultado en este README y en E2E.

Contingencia prevista por la guía: `ngrok http 80` sobre el Apache local. Es temporal y depende de que la laptop y Apache estén encendidos. La activación del hosting, las cuentas y la prueba del celular requieren intervención del estudiante.

## Mapa del proyecto

- `D0.md`: plantilla personal; completar sin IA según P1.
- `requirements.md`, `design.md`, `prompts.md`: requisitos, diseño y registro transparente de asistencia.
- `src/ServicioVenta.php`: reglas y cálculos independientes de la BD.
- `src/RepositorioMySQL.php`: consultas preparadas, bloqueo de stock y transacción.
- `tests/`: unitarias e integración aislada.
- `docs/`: plan, casos, auditoría, E2E, análisis de fallo, seguridad y estado.

La firma validarCantidad acepta mixed en vez de int para rechazar realmente los decimales del POST; se justifica en design.md. La atomicidad R4 permanece en el repositorio para mantener ServicioVenta sin PDO. Esta es una demo académica sin autenticación, conforme al alcance. Usar datos ficticios.
