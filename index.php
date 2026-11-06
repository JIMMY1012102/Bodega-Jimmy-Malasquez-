<?php
declare(strict_types=1);
require_once __DIR__ . '/config/web.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/RepositorioMySQL.php';
$productos = []; $error = '';
try { $productos = (new RepositorioMySQL(conectar()))->listarProductos(); }
catch (Throwable $ex) { error_log('bodega.listar: ' . $ex->getMessage()); $error = 'No pudimos cargar los productos. Revisa la configuración de conexión.'; http_response_code(503); }
iniciarPagina('Productos');
?>
<p class="etiqueta">INVENTARIO DE LA BODEGA</p><h1>Todo listo para vender</h1><p class="text-secondary mb-4">Consulta los productos y su stock antes de registrar una venta.</p>
<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><?php endif ?>
<div class="row g-3">
<?php foreach ($productos as $p): ?>
<div class="col-sm-6 col-lg-4"><article class="producto h-100"><div class="d-flex justify-content-between align-items-start"><h2 class="h5"><?= e($p['nombre']) ?></h2><span class="badge <?= (int)$p['stock'] === 0 ? 'text-bg-secondary' : 'text-bg-success' ?>"><?= (int)$p['stock'] === 0 ? 'Agotado' : 'Disponible' ?></span></div><p class="precio"><?= e(soles($p['precio'])) ?></p><p class="mb-0 text-secondary">Stock: <strong><?= e($p['stock']) ?></strong> unidades</p></article></div>
<?php endforeach ?>
</div>
<?php terminarPagina(); ?>
