<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/ServicioVenta.php';

final class ServicioVentaTest extends TestCase
{
    public function testAceptaVentaIgualAlStock(): void
    {
        self::assertSame(true, ServicioVenta::hayStock(10, 10));
    }

    public function testRechazaVentaMayorAlStock(): void
    {
        self::assertSame(false, ServicioVenta::hayStock(11, 10));
    }

    public function testAceptaCantidadUnoMenosQueStock(): void
    {
        self::assertSame(true, ServicioVenta::hayStock(9, 10));
    }

    public function testRechazaProductoAgotado(): void
    {
        self::assertSame(false, ServicioVenta::hayStock(1, 0));
    }

    public function testDescuentoCeroCuandoSubtotalEsExactamente100(): void
    {
        self::assertEqualsWithDelta(0.0, ServicioVenta::calcularDescuento(100.00), 0.001);
    }

    public function testDescuentoCeroPorDebajoDelUmbral(): void
    {
        self::assertEqualsWithDelta(0.0, ServicioVenta::calcularDescuento(99.99), 0.001);
    }

    public function testAplicaDescuentoSobreElUmbral(): void
    {
        self::assertEqualsWithDelta(5.0, ServicioVenta::calcularDescuento(100.01), 0.001);
    }

    public function testCalculaTotalSobreElUmbral(): void
    {
        self::assertEqualsWithDelta(95.01, ServicioVenta::calcularTotal(100.01), 0.001);
    }

    public function testRechazaCantidadCero(): void
    {
        self::assertSame(false, ServicioVenta::validarCantidad(0));
    }

    public function testRechazaCantidadNegativa(): void
    {
        self::assertSame(false, ServicioVenta::validarCantidad(-1));
    }

    public function testRechazaCantidadDecimal(): void
    {
        self::assertSame(false, ServicioVenta::validarCantidad(1.5));
    }

    public function testRechazaDecimalRecibidoDelFormulario(): void
    {
        self::assertSame(false, ServicioVenta::validarCantidad('1.5'));
    }

    public function testAceptaCantidadUno(): void
    {
        self::assertSame(true, ServicioVenta::validarCantidad(1));
    }

    public function testAceptaEnteroRecibidoDelFormulario(): void
    {
        self::assertSame(true, ServicioVenta::validarCantidad('1'));
    }

    public function testCalculaSubtotalDeDosLineas(): void
    {
        self::assertEqualsWithDelta(120.0, ServicioVenta::calcularSubtotal([['cantidad'=>15,'precio'=>'4.00'],['cantidad'=>5,'precio'=>'12.00']]), 0.001);
    }

    public function testAgrupaLineasDelMismoProducto(): void
    {
        self::assertSame([1=>11], ServicioVenta::agruparItems([['producto_id'=>1,'cantidad'=>6],['producto_id'=>1,'cantidad'=>5]]));
    }

    public function testRechazaProductoConInyeccionSQL(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ServicioVenta::agruparItems([['producto_id'=>'1 OR 1=1','cantidad'=>1]]);
    }

    public function testRechazaVentaSinProductos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ServicioVenta::agruparItems([]);
    }

    public function testRechazaCantidadDecimalEnLasLineas(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ServicioVenta::agruparItems([['producto_id'=>1,'cantidad'=>'1.5']]);
    }

    public function testCalculaTotalSinDescuentoEn100(): void
    {
        self::assertEqualsWithDelta(100.0, ServicioVenta::calcularTotal(100.00), 0.001);
    }
}
