<?php

namespace Tests\Unit;

use App\Services\CalculoVentaService;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitarios de CalculoVentaService.
 *
 * Cada escenario refleja un caso real de gestionarecr.
 * Los valores esperados se derivan de trazar el algoritmo JS calcularMontos()
 * paso a paso con los mismos inputs.
 */
class CalculoVentaServiceTest extends TestCase
{
    private CalculoVentaService $svc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->svc = new CalculoVentaService();
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function base(array $override = []): array
    {
        return array_merge([
            'aplica_calc'                  => false,
            'aplica_porc_tarjeta'          => false,
            'aplica_porc_113'              => false,
            'aplica_porc_prod'             => false,
            'set_campo_esp'                => false,
            'monto_venta'                  => 0,
            'porcentaje'                   => 0,
            'monto_producto_venta'         => 0,
            'monto_por_servicio_o_salario' => 0,
            'tipo_pago'                    => 'EFECTIVO',
            'is_paquete'                   => false,
            'set_clinica'                  => false,
        ], $override);
    }

    private function calcular(array $override = []): array
    {
        return $this->svc->calcular($this->base($override));
    }

    // ── Escenario 0: Guard — ambos montos en 0 ───────────────────────────────

    public function test_ambos_montos_cero_devuelve_cero(): void
    {
        $r = $this->calcular(['monto_venta' => 0, 'monto_producto_venta' => 0]);
        $this->assertEquals(0.0, $r['monto_clinica']);
        $this->assertEquals(0.0, $r['monto_especialista']);
    }

    // ── Escenario 1: set_clinica — todo a la clínica ─────────────────────────
    // set_clinica=1 → clinica = monto_venta + monto_producto_original, especialista = 0

    public function test_set_clinica_todo_a_clinica(): void
    {
        $r = $this->calcular([
            'monto_venta'          => 10000,
            'monto_producto_venta' => 3000,
            'set_clinica'          => true,
            'aplica_calc'          => true,
            'set_campo_esp'        => true,
            'porcentaje'           => 30,
        ]);
        $this->assertEquals(13000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    public function test_set_clinica_sin_producto(): void
    {
        $r = $this->calcular(['monto_venta' => 15000, 'set_clinica' => true]);
        $this->assertEquals(15000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    // ── Escenario 2: aplica_calc=1 + set_campo_esp=1 (caso normal clínica) ──
    // clinica = monto_venta * (porcentaje/100)
    // especialista = monto_venta * (1 - porcentaje/100)

    public function test_aplica_calc_con_set_campo_esp(): void
    {
        // Clínica 30 %, especialista 70 %
        $r = $this->calcular([
            'aplica_calc'   => true,
            'set_campo_esp' => true,
            'monto_venta'   => 10000,
            'porcentaje'    => 30,
        ]);
        $this->assertEquals(3000.0, $r['monto_clinica']);
        $this->assertEquals(7000.0, $r['monto_especialista']);
    }

    public function test_aplica_calc_porcentaje_cero(): void
    {
        $r = $this->calcular([
            'aplica_calc'   => true,
            'set_campo_esp' => true,
            'monto_venta'   => 8000,
            'porcentaje'    => 0,
        ]);
        // Con porcentaje 0: clínica=0, especialista=8000
        $this->assertEquals(0.0,    $r['monto_clinica']);
        $this->assertEquals(8000.0, $r['monto_especialista']);
    }

    // ── Escenario 3: aplica_calc=1 + set_campo_esp=0 ────────────────────────
    // La rama else del JS: montoClinica = monto_total_esp - monto_calc_prod + monto_producto
    // sin aplica_prod → monto_calc_prod=0, monto_producto=0
    // montoClinica = monto_venta - monto_venta_con_porc

    public function test_aplica_calc_sin_set_campo_esp(): void
    {
        // monto_total_esp = 10000 - 3000 = 7000
        // else: clinica = 7000 - 0 + 0 = 7000, especialista = monto_calc_prod = 0
        $r = $this->calcular([
            'aplica_calc'   => true,
            'set_campo_esp' => false,
            'monto_venta'   => 10000,
            'porcentaje'    => 30,
        ]);
        $this->assertEquals(7000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,    $r['monto_especialista']);
    }

    // ── Escenario 4: monto_por_servicio_o_salario (salario fijo) ────────────
    // clinica += (monto_venta - monto_serv_sal)
    // especialista += monto_serv_sal
    // Luego rama else: montoClinica = monto_total_esp (= monto_serv_sal)

    public function test_salario_fijo(): void
    {
        // aplica=false, set_campo_esp=false, monto_serv_sal=2500
        // montoTotalCli = 7500, montoTotalEsp = 2500
        // else: clinica = 2500 - 0 + 0 = 2500, especialista = 0
        $r = $this->calcular([
            'monto_venta'                  => 10000,
            'monto_por_servicio_o_salario' => 2500,
        ]);
        $this->assertEquals(2500.0, $r['monto_clinica']);
        $this->assertEquals(0.0,    $r['monto_especialista']);
    }

    public function test_salario_fijo_con_set_campo_esp(): void
    {
        // aplica=false, set_campo_esp=true
        // montoTotalCli = 7500, montoTotalEsp = 2500
        // set_campo_esp: clinica=7500, especialista=2500
        $r = $this->calcular([
            'monto_venta'                  => 10000,
            'monto_por_servicio_o_salario' => 2500,
            'set_campo_esp'                => true,
        ]);
        $this->assertEquals(7500.0, $r['monto_clinica']);
        $this->assertEquals(2500.0, $r['monto_especialista']);
    }

    // ── Escenario 5: Pago con Tarjeta + aplica_porc_113 (÷1.13) ─────────────

    public function test_tarjeta_aplica_113(): void
    {
        // monto_venta ajustado = 10000 / 1.13 ≈ 8849.56
        // aplica=true, set_campo_esp=true, porcentaje=30
        $montoAjustado = 10000 / 1.13;
        $r = $this->calcular([
            'aplica_calc'       => true,
            'aplica_porc_113'   => true,
            'set_campo_esp'     => true,
            'monto_venta'       => 10000,
            'porcentaje'        => 30,
            'tipo_pago'         => 'TARJETA',
        ]);
        $this->assertEqualsWithDelta($montoAjustado * 0.30, $r['monto_clinica'],      0.01);
        $this->assertEqualsWithDelta($montoAjustado * 0.70, $r['monto_especialista'], 0.01);
    }

    // ── Escenario 6: Pago con Tarjeta + aplica_porc_tarjeta (×1.13) ─────────

    public function test_tarjeta_aplica_porc_tarjeta(): void
    {
        // monto_venta ajustado = 10000 * 1.13 = 11300
        $montoAjustado = 10000 * 1.13;
        $r = $this->calcular([
            'aplica_calc'         => true,
            'aplica_porc_tarjeta' => true,
            'set_campo_esp'       => true,
            'monto_venta'         => 10000,
            'porcentaje'          => 30,
            'tipo_pago'           => 'TARJETA',
        ]);
        $this->assertEqualsWithDelta($montoAjustado * 0.30, $r['monto_clinica'],      0.01);
        $this->assertEqualsWithDelta($montoAjustado * 0.70, $r['monto_especialista'], 0.01);
    }

    // ── Escenario 7: Ajuste de tarjeta NO aplica cuando set_clinica=1 ────────

    public function test_tarjeta_no_ajusta_cuando_set_clinica(): void
    {
        // Aunque es TARJETA y aplica_113=1, si set_clinica=1 no hay ajuste
        $r = $this->calcular([
            'aplica_porc_113' => true,
            'monto_venta'     => 10000,
            'tipo_pago'       => 'TARJETA',
            'set_clinica'     => true,
        ]);
        // Con set_clinica: clinica = monto_venta + monto_prod_fijo = 10000
        $this->assertEquals(10000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    // ── Escenario 8: aplica_porc_prod — comisión 10 % sobre producto ─────────

    public function test_aplica_porc_prod_con_aplica_calc(): void
    {
        // monto_producto ajustado = 5000 / 1.13 ≈ 4424.78
        // monto_calc_prod = 4424.78 * 0.10 ≈ 442.48
        // set_campo_esp=false, aplica_prod=1:
        //   montoTotalCli = 4424.78 - 442.48 = 3982.30
        //   montoTotalEsp = 442.48
        // aplica=true:
        //   montoVentaConPorc = 10000 * 0.30 = 3000
        //   montoTotalCli += 3000 → 6982.30
        //   montoTotalEsp += 7000 → 7442.48
        // else (set_campo_esp=false):
        //   clinica = 7442.48 - 442.48 + 4424.78 = 11424.78
        //   especialista = monto_calc_prod = 442.48
        $prodAjust    = 5000 / 1.13;
        $calcProd     = $prodAjust * 0.10;
        $montoTotalEsp = $calcProd + (10000 - 10000 * 0.30);
        $clinicaEsperada     = $montoTotalEsp - $calcProd + $prodAjust;
        $especialistaEsperada = $calcProd;

        $r = $this->calcular([
            'aplica_calc'          => true,
            'aplica_porc_prod'     => true,
            'set_campo_esp'        => false,
            'monto_venta'          => 10000,
            'monto_producto_venta' => 5000,
            'porcentaje'           => 30,
        ]);
        $this->assertEqualsWithDelta($clinicaEsperada,      $r['monto_clinica'],      0.02);
        $this->assertEqualsWithDelta($especialistaEsperada, $r['monto_especialista'], 0.02);
    }

    public function test_aplica_porc_prod_solo_producto_sin_venta(): void
    {
        // monto_venta=0, monto_producto=5000, aplica_prod=1
        // monto_producto ajustado = 5000/1.13
        // monto_calc_prod = ajustado * 0.10
        // Rama del JS: (monto_venta == 0 && monto_producto > 0 && aplica_prod)
        //   → clinica = monto_producto_ajustado - monto_calc_prod
        //   → especialista = 0 (is_paquete=false, monto_calc_prod → no entra en if !chkPackage)
        $prodAjust = 5000 / 1.13;
        $calcProd  = $prodAjust * 0.10;
        $r = $this->calcular([
            'aplica_porc_prod'     => true,
            'monto_venta'          => 0,
            'monto_producto_venta' => 5000,
        ]);
        // clinica = monto_producto_ajustado - monto_calc_prod = 90% del ajustado
        $this->assertEqualsWithDelta($prodAjust - $calcProd, $r['monto_clinica'],      0.02);
        $this->assertEqualsWithDelta($calcProd,              $r['monto_especialista'], 0.02);
    }

    // ── Escenario 9: is_paquete (sin especialista) ───────────────────────────
    // En paquete: aplica || isPaquete → monto_total_esp = monto_venta (porcentaje=0)
    // else branch: clinica = monto_total_esp + monto_producto = monto_venta + monto_producto
    // especialista = 0 (no entra en if !chkPackage)

    public function test_paquete_sin_producto(): void
    {
        $r = $this->calcular([
            'is_paquete'  => true,
            'monto_venta' => 12000,
            'porcentaje'  => 0,
        ]);
        $this->assertEquals(12000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    public function test_paquete_con_producto(): void
    {
        $r = $this->calcular([
            'is_paquete'           => true,
            'monto_venta'          => 10000,
            'monto_producto_venta' => 2500,
            'porcentaje'           => 0,
        ]);
        $this->assertEquals(12500.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    // ── Escenario 10: aplica_porc_prod NO aplica en modo paquete ────────────
    // is_paquete=true → condición "!chkPackage" del paso 2 no se cumple → no se divide

    public function test_aplica_porc_prod_no_aplica_en_paquete(): void
    {
        // Aunque aplica_prod=1, en modo paquete el producto no se divide entre 1.13
        $r = $this->calcular([
            'is_paquete'           => true,
            'aplica_porc_prod'     => true,
            'monto_venta'          => 10000,
            'monto_producto_venta' => 5000,
            'porcentaje'           => 0,
        ]);
        // monto_producto NO se ajusta → clinica = 10000 + 5000 = 15000, especialista = 0
        $this->assertEquals(15000.0, $r['monto_clinica']);
        $this->assertEquals(0.0,     $r['monto_especialista']);
    }

    // ── Escenario 11: Tipo pago NO tarjeta — ajuste NO se aplica ────────────

    public function test_efectivo_no_aplica_ajuste_tarjeta(): void
    {
        $r1 = $this->calcular([
            'aplica_calc'       => true,
            'aplica_porc_113'   => true,
            'set_campo_esp'     => true,
            'monto_venta'       => 10000,
            'porcentaje'        => 30,
            'tipo_pago'         => 'EFECTIVO',  // NO tarjeta
        ]);
        $r2 = $this->calcular([
            'aplica_calc'       => true,
            'aplica_porc_113'   => false,
            'set_campo_esp'     => true,
            'monto_venta'       => 10000,
            'porcentaje'        => 30,
            'tipo_pago'         => 'EFECTIVO',
        ]);
        // Sin tarjeta, aplica_113 no cambia nada
        $this->assertEquals($r2['monto_clinica'],      $r1['monto_clinica']);
        $this->assertEquals($r2['monto_especialista'], $r1['monto_especialista']);
    }

    // ── Escenario 12: Combinación máxima ────────────────────────────────────
    // aplica_calc + set_campo_esp + aplica_113 + tipo TARJETA

    public function test_combinacion_tarjeta_aplica_113_y_porcentaje(): void
    {
        $montoAjustado = 20000 / 1.13;
        $r = $this->calcular([
            'aplica_calc'     => true,
            'aplica_porc_113' => true,
            'set_campo_esp'   => true,
            'monto_venta'     => 20000,
            'porcentaje'      => 40,
            'tipo_pago'       => 'TARJETA',
        ]);
        $this->assertEqualsWithDelta($montoAjustado * 0.40, $r['monto_clinica'],      0.01);
        $this->assertEqualsWithDelta($montoAjustado * 0.60, $r['monto_especialista'], 0.01);
    }

    // ── Escenario 13: aplica_calc=0, set_campo_esp=0, sin salario ni producto
    // No se cumple ninguna condición de distribución → ambos 0

    public function test_sin_configuracion_activa_devuelve_cero(): void
    {
        $r = $this->calcular(['monto_venta' => 10000]);
        $this->assertEquals(0.0, $r['monto_clinica']);
        $this->assertEquals(0.0, $r['monto_especialista']);
    }
}
