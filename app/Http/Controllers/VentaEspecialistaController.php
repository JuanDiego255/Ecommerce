<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\Especialista;
use App\Models\PivotServiciosEspecialista;
use App\Models\TipoPago;
use App\Models\VentaEspecialista;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaEspecialistaController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tipos = TipoPago::get();
        $especialistas = Especialista::get();
        return view('admin.ventas.index', compact('tipos', 'especialistas'));
    }
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexVentas($fecha = null)
    {
        $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString();
        $fechaCostaRica = $fecha ?? $fechaCostaRica;
        $ventas = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->join('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('clothing', 'venta_especialistas.clothing_id', 'clothing.id')
            ->join('tipo_pagos', 'venta_especialistas.tipo_pago_id', 'tipo_pagos.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'venta_especialistas.*',
                'especialistas.nombre as nombre',
                'clothing.name as servicio',
                'tipo_pagos.tipo as tipo'
            )
            ->orderBy('especialistas.nombre', 'asc')
            ->orderBy('clothing.name', 'asc')
            ->get();
        $ventasEntrada = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->join('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('clothing', 'venta_especialistas.clothing_id', 'clothing.id')
            ->join('tipo_pagos', 'venta_especialistas.tipo_pago_id', 'tipo_pagos.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'especialistas.nombre as especialista',
                'tipo_pagos.tipo as tipo_pago',
                DB::raw('SUM(venta_especialistas.monto_venta) as total_venta'),
                DB::raw('SUM(venta_especialistas.monto_especialista) as total_especialista')
            )
            ->groupBy('especialistas.nombre', 'tipo_pagos.tipo')
            ->orderBy('especialistas.nombre', 'asc')
            ->orderBy('tipo_pagos.tipo', 'asc')
            ->get();

        $ventasPorEspecialista = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->join('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'especialistas.nombre as especialista',
                DB::raw('SUM(venta_especialistas.monto_venta) as total_venta'),
                DB::raw('SUM(venta_especialistas.monto_especialista) as total_especialista')
            )
            ->groupBy('especialistas.nombre')
            ->orderBy('especialistas.nombre', 'asc')
            ->get();


        return view('admin.ventas.index-ventas', compact('ventas', 'fechaCostaRica', 'ventasEntrada','ventasPorEspecialista'));
    }
    public function getServices(Request $request)
    {
        $especialistaId = $request->especialista_id;
        $servicios = PivotServiciosEspecialista::where('especialista_id', $especialistaId)
            ->join('clothing', 'pivot_servicios_especialistas.clothing_id', 'clothing.id')
            ->select(
                'pivot_servicios_especialistas.*',
                DB::raw("CONCAT(clothing.name, ' - ', pivot_servicios_especialistas.porcentaje,'%') AS servicio"),
                'clothing.id as servicio_id',
                'pivot_servicios_especialistas.porcentaje as porcentaje'
            )
            ->get();
        return response()->json($servicios);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $cajaAbierta = ArqueoCaja::cajaAbiertaHoy($request->fecha_matricula)->first();

            if (!$cajaAbierta) {
                return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning']);
            }
            $venta =  new  VentaEspecialista();
            $venta->especialista_id = $request->especialista_id;
            $venta->arqueo_id = $cajaAbierta->id;
            $venta->clothing_id = $request->clothing_id;
            $venta->monto_venta = $request->monto_venta;
            $venta->tipo_pago_id = $request->tipo_pago;
            $venta->monto_producto_venta = $request->monto_producto_venta;
            $venta->porcentaje = $request->input_porcentaje;
            $venta->descuento = $request->descuento;
            $venta->monto_por_servicio_o_salario = $request->monto_por_servicio_o_salario;
            $venta->monto_clinica = $request->monto_clinica;
            $venta->monto_especialista = $request->monto_especialista;
            $venta->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado la venta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la venta', 'icon' => 'error']);
        }
    }
}
