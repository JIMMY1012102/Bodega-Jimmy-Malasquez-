<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../src/RepositorioMySQL.php';

final class RegistrarVentaTest extends TestCase
{
    private ?PDO $pdo = null;
    private bool $preparado = false;

    protected function setUp(): void
    {
        $archivo = __DIR__ . '/../../config/env_test.php';
        if (!is_file($archivo)) {
            self::fail('Crea config/env_test.php desde la plantilla antes de ejecutar integración.');
        }
        $env = require $archivo;
        if (($env['base'] ?? '') !== 'bodega_test') {
            self::fail('Protección: solo se permite la base bodega_test.');
        }
        $this->pdo = new PDO('mysql:host=' . $env['host'] . ';port=' . ($env['puerto'] ?? '3306') . ';dbname=bodega_test;charset=utf8mb4', $env['usuario'], $env['clave'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]);
        if ($this->pdo->query('SELECT DATABASE()')->fetchColumn() !== 'bodega_test') {
            self::fail('Se rechazó una conexión a una base distinta de bodega_test.');
        }
        $this->limpiar();
        $this->pdo->exec("INSERT INTO producto (id,nombre,precio,stock) VALUES (1,'Arroz',4.00,10),(2,'Gaseosa',2.50,0)");
        $this->preparado = true;
    }

    private function limpiar(): void
    {
        $this->pdo->exec('DELETE FROM detalle_venta');
        $this->pdo->exec('DELETE FROM venta');
        $this->pdo->exec('DELETE FROM producto');
    }

    protected function tearDown(): void
    {
        if ($this->pdo !== null && $this->preparado) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->limpiar();
        }
        $this->pdo = null;
    }

    public function testRegistraVentaYReduceStockEnLaMismaTransaccion(): void
    {
        $venta = (new RepositorioMySQL($this->pdo))->registrarVenta('Cliente prueba', [['producto_id'=>1,'cantidad'=>3]]);
        self::assertSame(7, (int) $this->pdo->query('SELECT stock FROM producto WHERE id=1')->fetchColumn());
        self::assertSame(1, (int) $this->pdo->query('SELECT COUNT(*) FROM venta')->fetchColumn());
        self::assertSame(1, (int) $this->pdo->query('SELECT COUNT(*) FROM detalle_venta')->fetchColumn());
        self::assertEqualsWithDelta(12.0, $venta['total'], 0.001);
    }

    public function testRechazaProductoAgotadoSinInsertarVenta(): void
    {
        try {
            (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>2,'cantidad'=>1]]);
            self::fail('Debió rechazar el producto agotado.');
        } catch (InvalidArgumentException $e) {
            self::assertSame('Stock insuficiente para el producto 2.', $e->getMessage());
        }
        $this->verificarSinVenta();
    }

    public function testRevierteVentaConUnaLineaValidaYOtraSinStock(): void
    {
        try {
            (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>1,'cantidad'=>3],['producto_id'=>2,'cantidad'=>1]]);
            self::fail('Debió revertir toda la venta.');
        } catch (InvalidArgumentException $e) {
            self::assertSame('Stock insuficiente para el producto 2.', $e->getMessage());
        }
        $this->verificarSinVenta();
    }

    public function testRechazaLineasDuplicadasQueSuperanStock(): void
    {
        try {
            (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>1,'cantidad'=>6],['producto_id'=>1,'cantidad'=>5]]);
            self::fail('Debió validar la suma de cantidades.');
        } catch (InvalidArgumentException $e) {
            self::assertSame('Stock insuficiente para el producto 1.', $e->getMessage());
        }
        $this->verificarSinVenta();
    }

    public function testVendeTodoElStockYLoDejaEnCero(): void
    {
        (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>1,'cantidad'=>10]]);
        self::assertSame(0, (int) $this->pdo->query('SELECT stock FROM producto WHERE id=1')->fetchColumn());
    }

    public function testIgnoraPrecioManipuladoEnElFormulario(): void
    {
        $venta = (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>1,'cantidad'=>1,'precio'=>0.01]]);
        self::assertEqualsWithDelta(4.0, $venta['total'], 0.001);
    }

    public function testRevierteSiFallaEscrituraDespuesDeInsertarCabecera(): void
    {
        // Trigger exclusivo de bodega_test: fuerza error real tras insertar venta y detalle.
        $this->pdo->exec("CREATE TRIGGER fallo_stock BEFORE UPDATE ON producto FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'fallo controlado'");
        try {
            try {
                (new RepositorioMySQL($this->pdo))->registrarVenta('Prueba', [['producto_id'=>1,'cantidad'=>3]]);
                self::fail('La escritura debía fallar.');
            } catch (PDOException $e) {
                self::assertSame('45000', (string) $e->getCode());
            }
            $this->verificarSinVenta();
        } finally {
            $this->pdo->exec('DROP TRIGGER IF EXISTS fallo_stock');
        }
    }

    private function verificarSinVenta(): void
    {
        self::assertSame(10, (int) $this->pdo->query('SELECT stock FROM producto WHERE id=1')->fetchColumn());
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM venta')->fetchColumn());
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM detalle_venta')->fetchColumn());
    }
}
