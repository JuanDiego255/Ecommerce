<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\Especialista;
use App\Models\MatriculaEstudiante;
use App\Models\PagosMatricula;
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
    public function index($id)
    {
        //
        $tipos = TipoPago::get();
        $especialista = null;
        if ($id != 0) {
            $especialista = VentaEspecialista::where('id', $id)->first();
        }
        $especialistas = Especialista::get();
        return view('admin.ventas.index', compact('tipos', 'especialistas', 'id', 'especialista'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listVentas()
    {
        //
        $ventas = VentaEspecialista::join('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('clothing', 'venta_especialistas.clothing_id', 'clothing.id')
            ->select(
                'especialistas.nombre as nombre',
                'clothing.name as name',
                'venta_especialistas.*'
            )
            ->orderBy('venta_especialistas.created_at','desc')
            ->get();
        return view('admin.ventas.list', compact('ventas'));
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

        $ventasEstudiantes = PagosMatricula::join('arqueo_cajas', 'pagos_matriculas.arqueo_id', 'arqueo_cajas.id')
            ->join('matricula_estudiantes', 'pagos_matriculas.matricula_id', 'matricula_estudiantes.id')
            ->join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->join('tipo_pagos', 'pagos_matriculas.tipo_pago_id', 'tipo_pagos.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'pagos_matriculas.*',
                'estudiantes.nombre as nombre',
                'matricula_estudiantes.curso as curso',
                'tipo_pagos.tipo as tipo'
            )
            ->orderBy('estudiantes.nombre', 'asc')
            ->orderBy('matricula_estudiantes.curso', 'asc')
            ->get();
        $ventasEntrada = DB::table(DB::raw("(
                SELECT tipo_pagos.tipo AS tipo_pago, SUM(venta_especialistas.monto_venta) AS total_venta
                FROM venta_especialistas
                JOIN arqueo_cajas ON venta_especialistas.arqueo_id = arqueo_cajas.id
                JOIN tipo_pagos ON venta_especialistas.tipo_pago_id = tipo_pagos.id
                WHERE DATE(arqueo_cajas.fecha_ini) = ?
                GROUP BY tipo_pagos.tipo
                
                UNION ALL
                
                SELECT tipo_pagos.tipo AS tipo_pago, SUM(matricula_estudiantes.monto_pago) AS total_venta
                FROM matricula_estudiantes
                JOIN tipo_pagos ON matricula_estudiantes.tipo_pago_id = tipo_pagos.id
                JOIN arqueo_cajas ON matricula_estudiantes.arqueo_id = arqueo_cajas.id
                WHERE DATE(arqueo_cajas.fecha_ini) = ?
                GROUP BY tipo_pagos.tipo
                
                UNION ALL
                
                SELECT tipo_pagos.tipo AS tipo_pago, SUM(pagos_matriculas.monto_pago) AS total_venta
                FROM pagos_matriculas
                JOIN tipo_pagos ON pagos_matriculas.tipo_pago_id = tipo_pagos.id
                JOIN arqueo_cajas ON pagos_matriculas.arqueo_id = arqueo_cajas.id
                WHERE DATE(arqueo_cajas.fecha_ini) = ?
                GROUP BY tipo_pagos.tipo
            ) AS union_query"))
            ->select('tipo_pago', DB::raw('SUM(total_venta) as total_venta'))
            ->groupBy('tipo_pago')
            ->orderBy('tipo_pago', 'asc')
            ->setBindings([$fechaCostaRica, $fechaCostaRica, $fechaCostaRica])
            ->get();

        $ventasEstudiantesSum = PagosMatricula::join('arqueo_cajas', 'pagos_matriculas.arqueo_id', 'arqueo_cajas.id')
            ->join('matricula_estudiantes', 'pagos_matriculas.matricula_id', 'matricula_estudiantes.id')
            ->join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'estudiantes.nombre as nombre',
                DB::raw('SUM(pagos_matriculas.monto_pago) as total_venta'),
                DB::raw('SUM(pagos_matriculas.descuento) as total_descuento')
            )
            ->groupBy('estudiantes.nombre') // Agrupación necesaria
            ->orderBy('estudiantes.nombre', 'asc')
            ->get();
        $ventasPorEspecialista = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->join('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                'especialistas.nombre as especialista',
                DB::raw('SUM(venta_especialistas.monto_clinica) as total_clinica'),
                DB::raw('SUM(venta_especialistas.monto_producto_venta) as total_producto'),
                DB::raw('SUM(venta_especialistas.monto_venta) as total_venta'),
                DB::raw('SUM(venta_especialistas.monto_especialista) as total_especialista')
            )
            ->groupBy('especialistas.nombre')
            ->orderBy('especialistas.nombre', 'asc')
            ->get();
        $ventasPorMatricula = MatriculaEstudiante::join('arqueo_cajas', 'matricula_estudiantes.arqueo_id', 'arqueo_cajas.id')
            ->whereDate('arqueo_cajas.fecha_ini', $fechaCostaRica)
            ->select(
                DB::raw('SUM(matricula_estudiantes.monto_pago) as total_venta')
            )
            ->groupBy('matricula_estudiantes.id')
            ->get();


        return view('admin.ventas.index-ventas', compact('ventas', 'fechaCostaRica', 'ventasPorMatricula', 'ventasEstudiantesSum', 'ventasEstudiantes', 'ventasEntrada', 'ventasPorEspecialista'));
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
            $type = $request->type;
            // Validar si no hay caja abierta y el tipo es "S"
            if (!$cajaAbierta && $type == "S") {
                return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning']);
            }
            // Si viene un ID de venta, hacemos un update, si no, creamos una nueva venta
            if (!empty($request->venta_id)) {
                // Buscar la venta existente               
                $venta = VentaEspecialista::find($request->venta_id);
                if ($venta) {
                    // Actualizar los valores (sin modificar arqueo_id)
                    $venta->especialista_id = $request->especialista_id;
                    $venta->clothing_id = $request->clothing_id;
                    $venta->monto_venta = $request->monto_venta;
                    $venta->tipo_pago_id = $request->tipo_pago;
                    $venta->monto_producto_venta = $request->monto_producto_venta;
                    $venta->porcentaje = $request->input_porcentaje;
                    $venta->descuento = $request->descuento;
                    $venta->monto_por_servicio_o_salario = $request->monto_por_servicio_o_salario;
                    $venta->monto_clinica = $request->monto_clinica;
                    $venta->monto_especialista = $request->monto_especialista;
                    $venta->update();
                }
            } else {
                // Crear una nueva venta
                $venta = new VentaEspecialista();
                $venta->especialista_id = $request->especialista_id;
                $venta->arqueo_id = $cajaAbierta->id; // Solo se asigna en la creación
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
            }
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado la venta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la venta', 'icon' => 'error']);
        }
    }
}
