<?php
declare(strict_types=1);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
function e(mixed $valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function soles(mixed $valor): string {
    return 'S/ ' . number_format((float) $valor, 2, '.', ',');
}
function iniciarPagina(string $titulo): void {
    ?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($titulo) ?> · Bodaga de Jimmy Malasquez</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/estilos.css" rel="stylesheet"></head><body><header><nav class="container d-flex justify-content-between align-items-center py-3" aria-label="Principal"><a class="marca" href="index.php">Bodaga de Jimmy Malasquez</a><a class="btn btn-verde" href="venta.php">Nueva venta</a></nav></header><main class="container py-4"><?php
}
function terminarPagina(): void {
    ?></main><footer class="container py-4 text-secondary">Laboratorio de desarrollo con IA · UNDC · Semana 11</footer></body></html><?php
}
