<?php
declare(strict_types=1);

final class ServicioVenta
{
    public const UMBRAL_DESCUENTO = 100.00;
    public const TASA_DESCUENTO = 0.05;

    // Acepta el dato crudo: una firma int perdería información o lanzaría TypeError para 1.5.
    public static function validarCantidad(mixed $cantidad): bool
    {
        if (is_int($cantidad)) {
            return $cantidad > 0;
        }
        return is_string($cantidad)
            && preg_match('/^[1-9][0-9]*$/D', $cantidad) === 1
            && filter_var($cantidad, FILTER_VALIDATE_INT) !== false;
    }

    public static function hayStock(int $cantidad, int $stock): bool
    {
        return self::validarCantidad($cantidad) && $cantidad <= $stock;
    }

    public static function calcularSubtotal(array $items): float
    {
        $centimos = 0;
        foreach ($items as $item) {
            if (!self::validarCantidad($item['cantidad'] ?? null)) {
                throw new InvalidArgumentException('La cantidad debe ser un entero mayor que cero.');
            }
            $precio = $item['precio'] ?? null;
            if (!is_numeric($precio) || !is_finite((float) $precio) || (float) $precio < 0) {
                throw new InvalidArgumentException('Precio inválido.');
            }
            $centimos += (int) round((float) $precio * 100) * (int) $item['cantidad'];
            if ($centimos > 9999999999) {
                throw new InvalidArgumentException('El importe supera el máximo permitido.');
            }
        }
        return $centimos / 100;
    }

    public static function calcularDescuento(float $subtotal): float
    {
        return $subtotal > self::UMBRAL_DESCUENTO
            ? round($subtotal * self::TASA_DESCUENTO, 2) : 0.0;
    }

    public static function calcularTotal(float $subtotal): float
    {
        return round($subtotal - self::calcularDescuento($subtotal), 2);
    }

    public static function agruparItems(array $items): array
    {
        if ($items === [] || count($items) > 100) {
            throw new InvalidArgumentException('Agrega entre 1 y 100 líneas de productos.');
        }
        $agrupados = [];
        foreach ($items as $item) {
            if (!is_array($item) || !self::validarCantidad($item['producto_id'] ?? null)) {
                throw new InvalidArgumentException('Producto inválido.');
            }
            if (!self::validarCantidad($item['cantidad'] ?? null)) {
                throw new InvalidArgumentException('La cantidad debe ser un entero mayor que cero.');
            }
            $id = (int) $item['producto_id'];
            $cantidad = (int) $item['cantidad'];
            // Límite del tipo INT del modelo, no una nueva regla comercial.
            if ($cantidad > 2147483647 || ($agrupados[$id] ?? 0) > 2147483647 - $cantidad) {
                throw new InvalidArgumentException('Cantidad fuera de rango.');
            }
            $agrupados[$id] = ($agrupados[$id] ?? 0) + $cantidad;
        }
        ksort($agrupados, SORT_NUMERIC);
        return $agrupados;
    }
}
