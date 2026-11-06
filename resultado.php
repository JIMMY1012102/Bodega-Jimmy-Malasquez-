<?php
declare(strict_types=1);
require_once __DIR__ . '/config/web.php';
$venta = $_SESSION['ultima_venta'] ?? null;
if (!$venta) { header('Location: venta.php', true, 303); exit; }
iniciarPagina('Venta registrada');
?><section class="producto mx-auto" style="max-width:560px"><p class="etiqueta">VENTA N.º <?= e($venta['id']) ?></p><h1 class="h2">Venta registrada</h1><p class="text-secondary">El stock se actualizó correctamente.</p><dl class="row"><dt class="col-6">Subtotal</dt><dd class="col-6 text-end"><?= e(soles($venta['subtotal'])) ?></dd><dt class="col-6">Descuento</dt><dd class="col-6 text-end"><?= e(soles($venta['descuento'])) ?></dd><dt class="col-6 fs-4">Total</dt><dd class="col-6 text-end fs-4 fw-bold"><?= e(soles($venta['total'])) ?></dd></dl><a href="index.php" class="btn btn-verde">Ver stock actualizado</a></section><?php terminarPagina(); ?>
