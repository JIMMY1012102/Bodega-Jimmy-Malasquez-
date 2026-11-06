<?php
declare(strict_types=1);
require_once __DIR__ . '/config/web.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/RepositorioMySQL.php';
$productos = []; $error = '';
try { $productos = (new RepositorioMySQL(conectar()))->listarProductos(); }
catch (Throwable $ex) { error_log('bodega.formulario: ' . $ex->getMessage()); $error = 'No pudimos cargar el formulario. Revisa la conexión.'; http_response_code(503); }
iniciarPagina('Nueva venta');
?>
<a href="index.php" class="text-success">← Ver productos</a><p class="etiqueta mt-4">REGISTRO DE VENTA</p><h1>Una venta, todo en orden</h1><p class="text-secondary">Se aplica 5 % de descuento cuando el subtotal supera S/ 100.00.</p>
<?php if ($error): ?><div role="alert" class="alert alert-danger"><?= e($error) ?></div><?php else: ?>
<form action="guardar_venta.php" method="post" class="producto mt-4" id="venta-form">
<input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
<label for="cliente" class="form-label">Nombre del cliente</label><input id="cliente" name="cliente" class="form-control mb-4" maxlength="80" required autocomplete="name">
<div id="lineas"><fieldset class="linea row g-2 mb-3"><legend class="h6">Producto <span class="numero">1</span></legend><div class="col-md-8"><label class="form-label" for="producto-0">Producto</label><select id="producto-0" name="items[0][producto_id]" class="form-select" required><option value="">Selecciona un producto</option><?php foreach ($productos as $p): ?><option value="<?= e($p['id']) ?>"><?= e($p['nombre']) ?> · <?= e(soles($p['precio'])) ?> · stock <?= e($p['stock']) ?></option><?php endforeach ?></select></div><div class="col-8 col-md-3"><label for="cantidad-0" class="form-label">Cantidad</label><input id="cantidad-0" name="items[0][cantidad]" type="number" min="1" step="1" value="1" class="form-control" required></div><div class="col-4 col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger quitar" aria-label="Quitar producto 1" disabled>×</button></div></fieldset></div>
<button id="agregar" type="button" class="btn btn-outline-success">+ Agregar producto</button><hr><div class="d-flex flex-wrap gap-2 justify-content-end"><a href="index.php" class="btn btn-light">Cancelar</a><button class="btn btn-verde" type="submit">Confirmar venta</button></div>
</form><script src="assets/venta.js" defer></script>
<?php endif; terminarPagina(); ?>
