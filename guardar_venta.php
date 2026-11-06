<?php
declare(strict_types=1);
require_once __DIR__ . '/config/web.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/RepositorioMySQL.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST'); http_response_code(405);
    iniciarPagina('Método no permitido'); echo '<p>Usa el formulario para registrar una venta.</p>'; terminarPagina(); exit;
}
$error = ''; $venta = null;
if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    http_response_code(403); $error = 'La sesión expiró. Abre nuevamente el formulario.';
} elseif (!is_string($_POST['cliente'] ?? null) || !is_array($_POST['items'] ?? null)) {
    http_response_code(422); $error = 'Completa el cliente y los productos.';
} else {
    try {
        $venta = (new RepositorioMySQL(conectar()))->registrarVenta($_POST['cliente'], $_POST['items']);
        // Evita duplicar una venta al actualizar la página.
        $_SESSION['ultima_venta'] = $venta;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        header('Location: resultado.php', true, 303); exit;
    } catch (InvalidArgumentException $ex) {
        http_response_code(422); $error = $ex->getMessage();
    } catch (Throwable $ex) {
        error_log('bodega.registrar: ' . $ex->getMessage());
        http_response_code(500); $error = 'No se pudo registrar la venta. Inténtalo nuevamente.';
    }
}
iniciarPagina('Revisar venta');
?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><p>La venta no se registró.</p><a href="venta.php" class="btn btn-verde">Volver al formulario</a><?php terminarPagina(); ?>
