<?php

namespace App\Services;

/**
 * Port PHP de la función calcularMontos() de admin/ventas/index.blade.php.
 *
 * Reproduce el algoritmo exacto del cliente para que el servidor sea
 * la fuente de verdad en el cálculo de distribución clínica/especialista.
 *
 * Entradas (array $datos):
 *   aplica_calc          bool  – distribuir por porcentaje (aplica_calc del especialista)
 *   aplica_porc_tarjeta  bool  – multiplicar monto×1.13 cuando el pago es TARJETA
 *   aplica_porc_113      bool  – dividir monto÷1.13 cuando el pago es TARJETA
 *   aplica_porc_prod     bool  – aplicar comisión 10 % sobre producto (÷1.13)
 *   set_campo_esp        bool  – escribir monto_total_esp directo al especialista
 *   monto_venta          float
 *   porcentaje           float (0-100) – porcentaje del servicio configurado en el pivot
 *   monto_producto_venta float
 *   monto_por_servicio_o_salario  float
 *   tipo_pago            string – nombre del tipo de pago (ej: "Tarjeta")
 *   is_paquete           bool  – true cuando no hay especialista asignado
 *   set_clinica          bool  – todo el monto va a la clínica (especialista = 0)
 *
 * Salidas: ['monto_clinica' => float, 'monto_especialista' => float]
 */
class CalculoVentaService
{
    public function calcular(array $datos): array
    {
        $aplica        = (bool)  ($datos['aplica_calc']          ?? false);
        $aplica113     = (bool)  ($datos['aplica_porc_113']      ?? false);
        $setCampoEsp   = (bool)  ($datos['set_campo_esp']        ?? false);
        $aplicaProd    = (bool)  ($datos['aplica_porc_prod']     ?? false);
        $aplicaTarjeta = (bool)  ($datos['aplica_porc_tarjeta']  ?? false);
        $montoVenta    = (float) ($datos['monto_venta']                        ?? 0);
        $porcentaje    = (float) ($datos['porcentaje']                         ?? 0);
        $montoProducto = (float) ($datos['monto_producto_venta']               ?? 0);
        $montoServSal  = (float) ($datos['monto_por_servicio_o_salario']       ?? 0);
        $tipoPago      = strtoupper(trim($datos['tipo_pago'] ?? ''));
        $isPaquete     = (bool)  ($datos['is_paquete']  ?? false);
        $setClinica    = (bool)  ($datos['set_clinica'] ?? false);

        // Guard: igual que el JS, no calcula si ambos montos son ≤ 0
        if ($montoVenta <= 0 && $montoProducto <= 0) {
            return ['monto_clinica' => 0.0, 'monto_especialista' => 0.0];
        }

        // Guarda el monto_producto original ANTES de cualquier ajuste (≡ var monto_prod_fijo)
        $montoProdFijo = $montoProducto;

        // ── Paso 1: Ajuste por tarjeta ────────────────────────────────────────
        // if (tipo_pago.trim().toUpperCase() === "TARJETA" && !chkSetClinica)
        if ($tipoPago === 'TARJETA' && !$setClinica) {
            if ($aplica113)     $montoVenta /= 1.13;
            if ($aplicaTarjeta) $montoVenta *= 1.13;
        }

        // ── Paso 2: Ajuste de producto ────────────────────────────────────────
        // if (monto_producto > 0 && !chkPackage && aplica_prod == 1)
        $porcProd = 0.0;
        if ($montoProducto > 0 && !$isPaquete && $aplicaProd) {
            $montoProducto /= 1.13;
            $porcProd = 0.10;
        }

        // ── Variables de distribución ─────────────────────────────────────────
        $montoCalcProd     = $aplicaProd ? ($montoProducto * $porcProd) : 0.0;
        $montoVentaConPorc = $porcentaje >= 0 ? ($montoVenta * ($porcentaje / 100)) : 0.0;

        $montoTotalCli = 0.0;
        $montoTotalEsp = 0.0;

        // ── Paso 3: Distribución de producto (aplica_prod) ────────────────────
        if ($aplicaProd && $montoProducto > 0) {
            $montoTotalCli = $montoProducto - $montoCalcProd;
            $montoTotalEsp = $montoCalcProd;
        }

        // ── Paso 4: Distribución por porcentaje (aplica_calc) / paquete ──────
        if ($aplica || $isPaquete) {
            $montoTotalCli += $montoVentaConPorc;
            $montoTotalEsp += ($montoVenta - $montoVentaConPorc);
        }

        // ── Paso 5: Distribución por monto fijo (salario / servicio) ─────────
        if ($montoServSal > 0) {
            $montoTotalCli += ($montoVenta - $montoServSal);
            $montoTotalEsp += $montoServSal;
        }

        // ── Paso 6: Asignación final ──────────────────────────────────────────
        if (!$setClinica) {
            // Rama principal: $monto_total_cli se asigna primero, luego puede sobreescribirse
            $montoClinica      = $montoTotalCli;
            $montoEspecialista = 0.0;

            if ($setCampoEsp && !$isPaquete) {
                // set_campo_esp == 1: escribe monto_total_esp directo
                $montoEspecialista = $montoTotalEsp;
            } else {
                // Rama else: sobreescribe monto_clinica con el ternario del JS
                if ($montoVenta == 0 && $montoProducto > 0 && $aplicaProd) {
                    $montoClinica = $montoProducto - $montoCalcProd;
                } else {
                    $montoClinica = !$isPaquete
                        ? ($montoTotalEsp - $montoCalcProd + $montoProducto)
                        : ($montoTotalEsp + $montoProducto);
                }

                // if (!chkPackage) $('#monto_especialista').val(monto_calc_prod)
                $montoEspecialista = !$isPaquete ? $montoCalcProd : 0.0;
            }
        } else {
            // Todo a la clínica
            $montoClinica      = $montoVenta + $montoProdFijo;
            $montoEspecialista = 0.0;
        }

        return [
            'monto_clinica'      => round($montoClinica, 2),
            'monto_especialista' => round($montoEspecialista, 2),
        ];
    }
}
