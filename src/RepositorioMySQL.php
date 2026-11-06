<?php
declare(strict_types=1);
require_once __DIR__ . '/ServicioVenta.php';

final class RepositorioMySQL
{
    public function __construct(private PDO $pdo) {}

    public function listarProductos(): array
    {
        return $this->pdo->query('SELECT id, nombre, precio, stock FROM producto ORDER BY id')->fetchAll();
    }

    public function obtenerProducto(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nombre, precio, stock FROM producto WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function registrarVenta(string $cliente, array $items): array
    {
        $cliente = trim($cliente);
        if ($cliente === '' || preg_match('//u', $cliente) !== 1
            || preg_match_all('/./us', $cliente) > 80) {
            throw new InvalidArgumentException('Ingresa un cliente de 1 a 80 caracteres.');
        }
        $agrupados = ServicioVenta::agruparItems($items);
        $this->pdo->beginTransaction();
        try {
            $consulta = $this->pdo->prepare('SELECT id, precio, stock FROM producto WHERE id = ? FOR UPDATE');
            $lineas = [];
            foreach ($agrupados as $id => $cantidad) {
                $consulta->execute([$id]);
                $producto = $consulta->fetch();
                if (!$producto) {
                    throw new InvalidArgumentException('El producto seleccionado no existe.');
                }
                if (!ServicioVenta::hayStock($cantidad, (int) $producto['stock'])) {
                    throw new InvalidArgumentException('Stock insuficiente para el producto ' . $id . '.');
                }
                $lineas[] = ['producto_id' => $id, 'cantidad' => $cantidad, 'precio' => $producto['precio']];
            }
            // Los precios se toman de la BD, nunca del formulario.
            $subtotal = ServicioVenta::calcularSubtotal($lineas);
            $descuento = ServicioVenta::calcularDescuento($subtotal);
            $total = ServicioVenta::calcularTotal($subtotal);
            $stmt = $this->pdo->prepare('INSERT INTO venta (fecha, cliente, subtotal, descuento, total) VALUES (NOW(), ?, ?, ?, ?)');
            $stmt->execute([$cliente, $subtotal, $descuento, $total]);
            $ventaId = (int) $this->pdo->lastInsertId();
            $detalle = $this->pdo->prepare('INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, importe) VALUES (?, ?, ?, ?, ?)');
            $stock = $this->pdo->prepare('UPDATE producto SET stock = stock - ? WHERE id = ?');
            foreach ($lineas as $linea) {
                $importe = ServicioVenta::calcularSubtotal([$linea]);
                $detalle->execute([$ventaId, $linea['producto_id'], $linea['cantidad'], $linea['precio'], $importe]);
                $stock->execute([$linea['cantidad'], $linea['producto_id']]);
            }
            $this->pdo->commit();
            return ['id' => $ventaId, 'subtotal' => $subtotal, 'descuento' => $descuento, 'total' => $total];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
