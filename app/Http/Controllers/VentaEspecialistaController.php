<?php

namespace App\Http\Controllers;

use App\Models\ArqueoCaja;
use App\Models\ClothingCategory;
use App\Models\Especialista;
use App\Models\MatriculaEstudiante;
use App\Models\PagosMatricula;
use App\Models\PivotServiciosEspecialista;
use App\Models\TipoPago;
use App\Models\VentaEspecialista;
use Yajra\DataTables\Facades\DataTables;
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
            $especialista = VentaEspecialista::where('venta_especialistas.id', $id)
                ->leftJoin('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
                ->select(
                    'venta_especialistas.*',
                    'especialistas.aplica_porc_tarjeta as aplica_porc_tarjeta',
                    'especialistas.aplica_porc_113 as aplica_porc_113',
                    'especialistas.aplica_porc_prod as aplica_porc_prod',
                    'especialistas.set_campo_esp as set_campo_esp',
                    'especialistas.aplica_calc as aplica_calc'
                )
                ->first();
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
        $arqueos = ArqueoCaja::take(10);
        /*  $ventas = VentaEspecialista::leftJoin('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('tipo_pagos', 'venta_especialistas.tipo_pago_id', 'tipo_pagos.id')
            ->select(
                'especialistas.nombre as nombre',
                'tipo_pagos.tipo as tipo',
                DB::raw("(
                    SELECT GROUP_CONCAT(clothing.name ORDER BY clothing.name SEPARATOR ', ')
                    FROM clothing
                    WHERE FIND_IN_SET(clothing.id, venta_especialistas.clothing_id)
                ) as servicios"),
                'venta_especialistas.*'
            )
            ->orderBy('venta_especialistas.created_at', 'desc')
            ->get(); */
        return view('admin.ventas.list', compact('arqueos'));
    }
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexVentas($fecha = null, $fecha_fin = null, $id = null)
    {
        $fechaCostaRica = Carbon::now('America/Costa_Rica')->toDateString();
        $fechaInicio = $fecha ?? $fechaCostaRica;
        $fechaFin = $fecha_fin ?? $fechaInicio;
        $especialistas = Especialista::get();

        // Normalizar fechas
        if ($fechaInicio > $fechaFin) {
            $fechaFin = $fechaInicio;
        }

        // Función para aplicar rango de fechas y opcionalmente el id del especialista
        $applyFechaFilter = function ($query, $campoFecha = 'arqueo_cajas.fecha_ini', $applyId = false) use ($fechaInicio, $fechaFin, $id) {
            $query->whereDate($campoFecha, '>=', $fechaInicio)
                ->whereDate($campoFecha, '<=', $fechaFin);

            if ($applyId && $id != 0) {
                $query->where('venta_especialistas.especialista_id', $id);
            }

            return $query;
        };

        // Ventas de especialistas
        $ventasQuery = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->leftJoin('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('tipo_pagos', 'venta_especialistas.tipo_pago_id', 'tipo_pagos.id')
            ->where('venta_especialistas.estado', '!=', 'A')
            ->select(
                'venta_especialistas.*',
                'especialistas.nombre as nombre',
                'tipo_pagos.tipo as tipo',
                DB::raw("(
                    SELECT GROUP_CONCAT(clothing.name ORDER BY clothing.name SEPARATOR ', ')
                    FROM clothing
                    WHERE FIND_IN_SET(clothing.id, venta_especialistas.clothing_id)
                ) as servicios")
            );

        $ventas = $applyFechaFilter($ventasQuery, 'arqueo_cajas.fecha_ini', true)
            ->orderBy('especialistas.nombre', 'asc')
            ->orderBy('servicios', 'asc')
            ->get();

        // Pagos de estudiantes
        $ventasEstudiantesQuery = PagosMatricula::join('arqueo_cajas', 'pagos_matriculas.arqueo_id', 'arqueo_cajas.id')
            ->join('matricula_estudiantes', 'pagos_matriculas.matricula_id', 'matricula_estudiantes.id')
            ->join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->join('tipo_pagos', 'pagos_matriculas.tipo_pago_id', 'tipo_pagos.id')
            ->where('estudiantes.tipo_estudiante', 'C')
            ->select(
                'pagos_matriculas.*',
                'estudiantes.nombre as nombre',
                'matricula_estudiantes.curso as curso',
                'tipo_pagos.tipo as tipo'
            );

        $ventasEstudiantes = $applyFechaFilter($ventasEstudiantesQuery)
            ->orderBy('estudiantes.nombre', 'asc')
            ->orderBy('matricula_estudiantes.curso', 'asc')
            ->get();

        $ventasEstudiantesQueryYoga = PagosMatricula::join('arqueo_cajas', 'pagos_matriculas.arqueo_id', 'arqueo_cajas.id')
            ->join('matricula_estudiantes', 'pagos_matriculas.matricula_id', 'matricula_estudiantes.id')
            ->join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->join('tipo_pagos', 'pagos_matriculas.tipo_pago_id', 'tipo_pagos.id')
            ->where('estudiantes.tipo_estudiante', 'Y')
            ->select(
                'pagos_matriculas.*',
                'estudiantes.nombre as nombre',
                'matricula_estudiantes.curso as curso',
                'tipo_pagos.tipo as tipo'
            );

        $ventasEstudiantesYoga = $applyFechaFilter($ventasEstudiantesQueryYoga)
            ->orderBy('estudiantes.nombre', 'asc')
            ->orderBy('matricula_estudiantes.curso', 'asc')
            ->get();

        // Entrada de ventas (union)
        // Prepara la condición por id
        $condicionId = '';
        $bindings = [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin];

        $condicionId = '';

        if ($id != 0) {
            $condicionId = 'AND venta_especialistas.especialista_id = ?';
        }

        $ventasEntrada = DB::table(DB::raw("
            (
                SELECT tipo_pagos.tipo AS tipo_pago, SUM(venta_especialistas.monto_venta + venta_especialistas.monto_producto_venta) AS total_venta
                FROM venta_especialistas
                JOIN arqueo_cajas ON venta_especialistas.arqueo_id = arqueo_cajas.id
                JOIN tipo_pagos ON venta_especialistas.tipo_pago_id = tipo_pagos.id
                WHERE DATE(arqueo_cajas.fecha_ini) BETWEEN ? AND ?
                AND venta_especialistas.estado <> 'A'
                $condicionId
                GROUP BY tipo_pagos.tipo

                UNION ALL

                SELECT tipo_pagos.tipo AS tipo_pago, SUM(matricula_estudiantes.monto_pago) AS total_venta
                FROM matricula_estudiantes
                JOIN tipo_pagos ON matricula_estudiantes.tipo_pago_id = tipo_pagos.id
                JOIN arqueo_cajas ON matricula_estudiantes.arqueo_id = arqueo_cajas.id
                WHERE DATE(arqueo_cajas.fecha_ini) BETWEEN ? AND ?
                GROUP BY tipo_pagos.tipo

                UNION ALL

                SELECT tipo_pagos.tipo AS tipo_pago, SUM(pagos_matriculas.monto_pago) AS total_venta
                FROM pagos_matriculas
                JOIN tipo_pagos ON pagos_matriculas.tipo_pago_id = tipo_pagos.id
                JOIN arqueo_cajas ON pagos_matriculas.arqueo_id = arqueo_cajas.id
                WHERE DATE(arqueo_cajas.fecha_ini) BETWEEN ? AND ?
                GROUP BY tipo_pagos.tipo
            ) AS union_query
        "))
            ->select('tipo_pago', DB::raw('SUM(total_venta) as total_venta'))
            ->groupBy('tipo_pago')
            ->orderBy('tipo_pago', 'asc')
            ->setBindings([
                $fechaInicio, // 1er ?
                $fechaFin,    // 2do ?
                ...($id != 0 ? [$id] : []), // 3er ? (si aplica)
                $fechaInicio, // 4to ?
                $fechaFin,    // 5to ?
                $fechaInicio, // 6to ?
                $fechaFin     // 7mo ?
            ])
            ->get();


        // Sumatoria de pagos por estudiante
        $ventasEstudiantesSumQuery = PagosMatricula::join('arqueo_cajas', 'pagos_matriculas.arqueo_id', 'arqueo_cajas.id')
            ->join('matricula_estudiantes', 'pagos_matriculas.matricula_id', 'matricula_estudiantes.id')
            ->join('estudiantes', 'matricula_estudiantes.estudiante_id', 'estudiantes.id')
            ->select(
                'estudiantes.nombre as nombre',
                DB::raw('SUM(pagos_matriculas.monto_pago) as total_venta'),
                DB::raw('SUM(pagos_matriculas.descuento) as total_descuento')
            );

        $ventasEstudiantesSum = $applyFechaFilter($ventasEstudiantesSumQuery)
            ->groupBy('estudiantes.nombre')
            ->orderBy('estudiantes.nombre', 'asc')
            ->get();

        // Ventas agrupadas por especialista
        $ventasPorEspecialistaQuery = VentaEspecialista::join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->leftJoin('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->where('venta_especialistas.estado', '!=', 'A')
            ->select(
                'especialistas.nombre as especialista',
                DB::raw('SUM(venta_especialistas.monto_clinica) as total_clinica'),
                DB::raw('SUM(venta_especialistas.monto_producto_venta) as total_producto'),
                DB::raw('SUM(venta_especialistas.monto_venta) as total_venta'),
                DB::raw('SUM(venta_especialistas.monto_especialista) as total_especialista')
            );

        $ventasPorEspecialista = $applyFechaFilter($ventasPorEspecialistaQuery, 'arqueo_cajas.fecha_ini', true)
            ->groupBy('especialistas.nombre')
            ->orderBy('especialistas.nombre', 'asc')
            ->get();

        // Pagos por matrícula
        $ventasPorMatriculaQuery = MatriculaEstudiante::join('arqueo_cajas', 'matricula_estudiantes.arqueo_id', 'arqueo_cajas.id')
            ->select(
                DB::raw('SUM(matricula_estudiantes.monto_pago) as total_venta')
            );

        $ventasPorMatricula = $applyFechaFilter($ventasPorMatriculaQuery)
            ->groupBy('matricula_estudiantes.id')
            ->get();

        // Devolver vista
        return view('admin.ventas.index-ventas', compact(
            'ventas',
            'fechaCostaRica',
            'especialistas',
            'id',
            'fechaInicio',
            'fechaFin',
            'ventasPorMatricula',
            'ventasEstudiantesSum',
            'ventasEstudiantes',
            'ventasEstudiantesYoga',
            'ventasEntrada',
            'ventasPorEspecialista'
        ));
    }


    public function getServices(Request $request)
    {
        $especialistaId = $request->especialista_id;
        if ($especialistaId != null) {
            $servicios = PivotServiciosEspecialista::where('especialista_id', $especialistaId)
                ->join('clothing', 'pivot_servicios_especialistas.clothing_id', 'clothing.id')
                ->select(
                    'pivot_servicios_especialistas.*',
                    DB::raw("CONCAT(clothing.name, ' - ', pivot_servicios_especialistas.porcentaje,'%') AS servicio"),
                    'clothing.id as servicio_id',
                    'clothing.price as price',
                    'pivot_servicios_especialistas.porcentaje as porcentaje'
                )
                ->get();
        } else {
            $servicios = ClothingCategory::select(
                'clothing.*',
                DB::raw("CONCAT(clothing.name, ' - ', '0','%') AS servicio"),
                'clothing.id as servicio_id',
                'clothing.price as price',
                DB::raw('0 as porcentaje')
            )->get();
        }

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
        // ========= NUEVO: Prevalidaciones para fecha manual y motivo =========
        $today = Carbon::now('America/Costa_Rica')->toDateString();
        $customDate = (bool) $request->input('custom_date', false);
        $actualizarArqueo = false;
        $fechaVenta = $request->input('fecha_venta');
        if ($customDate) {
            // 1) Debe venir fecha_venta
            if (empty($fechaVenta)) {
                return redirect()->back()
                    ->with(['status' => 'Debes seleccionar la fecha de la venta.', 'icon' => 'warning'])
                    ->withInput();
            }
            // 1.1) No permitir fechas futuras (defensa del lado servidor)
            if ($fechaVenta > $today) {
                return redirect()->back()
                    ->with(['status' => 'No puedes seleccionar una fecha futura para la venta.', 'icon' => 'warning'])
                    ->withInput();
            }

            // 2) Si la fecha seleccionada es distinta a hoy, el motivo es obligatorio
            if ($fechaVenta !== $today && !filled($request->input('motivo_fecha'))) {
                return redirect()->back()
                    ->with(['status' => 'Debes ingresar un motivo para registrar una venta con fecha distinta a hoy.', 'icon' => 'warning'])
                    ->withInput();
            }

            // 1) Verificar que exista un arqueo abierto en la fecha seleccionada
            $cajaEnFecha = ArqueoCaja::cajaAbiertaHoy($fechaVenta)->first();
            if (!$cajaEnFecha) {
                return redirect()->back()
                    ->with(['status' => 'No hay ninguna caja abierta para la fecha seleccionada (' . $fechaVenta . ').', 'icon' => 'warning'])
                    ->withInput();
            }

            // Mantener tu código original intacto: alimentamos fecha_matricula para que lo use tu línea existente
            $request->merge(['fecha_matricula' => $fechaVenta]);
            $actualizarArqueo = true;
        } else {
            // Si no viene fecha manual, forzamos fecha_matricula a hoy para tu línea original
            $request->merge(['fecha_matricula' => $today]);
        }
        // ========= FIN NUEVO: Prevalidaciones =========

        //
        DB::beginTransaction();
        try {
            $cajaAbierta = ArqueoCaja::cajaAbiertaHoy($request->fecha_matricula)->first();
            $type = $request->type;
            if ($request->monto_venta <= 0 && $request->monto_producto_venta <= 0) {
                return redirect()->back()->with(['status' => 'Para realizar una venta debes ingresar el monto de la venta, o el monto del producto', 'icon' => 'warning'])->withInput();
            } else if ($request->monto_venta == null && $request->monto_producto_venta == null) {
                return redirect()->back()->with(['status' => 'Para realizar una venta debes ingresar el monto de la venta, o el monto del producto', 'icon' => 'warning'])->withInput();
            }
            if ($request->monto_clinica <= 0 && $request->monto_especialista <= 0) {
                return redirect()->back()->with(['status' => 'Debes presionar el botón de calcular para evitar inconsistencias en el informe', 'icon' => 'warning'])->withInput();
            }
            // Validar si no hay caja abierta y el tipo es "S"
            if (!$cajaAbierta && $type == "S") {
                return redirect()->back()->with(['status' => 'No hay ninguna caja abierta para el día de hoy', 'icon' => 'warning'])->withInput();
            }
            // Si viene un ID de venta, hacemos un update, si no, creamos una nueva venta
            if (!empty($request->venta_id)) {
                // Buscar la venta existente               
                $venta = VentaEspecialista::find($request->venta_id);
                if ($venta) {
                    // Actualizar los valores (sin modificar arqueo_id)
                    $venta->especialista_id = $request->especialista_id;
                    if ($actualizarArqueo) {
                        $venta->arqueo_id = $cajaAbierta->id;
                        $venta->justificacion_arqueo = $request->input('motivo_fecha');
                    }
                    $venta->clothing_id = $request->clothing_id;
                    $venta->monto_venta = $request->monto_venta;
                    $venta->tipo_pago_id = $request->tipo_pago;
                    $venta->monto_producto_venta = $request->monto_producto_venta;
                    $venta->porcentaje = $request->input_porcentaje;
                    $venta->is_gift_card = $request->is_gift_card;
                    $venta->descuento = $request->descuento;
                    $venta->monto_por_servicio_o_salario = $request->monto_por_servicio_o_salario;
                    $venta->monto_clinica = $request->monto_clinica;
                    $venta->monto_especialista = $request->monto_especialista;
                    $venta->nombre_cliente = $request->nombre_cliente;
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
                $venta->is_gift_card = $request->is_gift_card;
                $venta->justificacion_arqueo ??= $request->input('motivo_fecha');
                $venta->porcentaje = $request->input_porcentaje;
                $venta->descuento = $request->descuento;
                $venta->monto_por_servicio_o_salario = $request->monto_por_servicio_o_salario;
                $venta->monto_clinica = $request->monto_clinica;
                $venta->monto_especialista = $request->monto_especialista;
                $venta->nombre_cliente = $request->nombre_cliente;
                $venta->save();
            }
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado la venta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la venta', 'icon' => 'error'])->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $venta = VentaEspecialista::find($id);
            $venta->estado = 'A';
            $venta->nota_anulacion = $request->nota_anulacion;
            $venta->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha anulado la venta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo anular la venta', 'icon' => 'error'])->withInput();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateArqueo($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $venta = VentaEspecialista::find($id);
            $venta->justificacion_arqueo = $request->justificacion_arqueo;
            $venta->arqueo_id = $request->arqueo_id;
            $venta->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha cambiado el arqueo de la venta con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo cambiar el arqueo de la venta', 'icon' => 'error'])->withInput();
        }
    }
    public function ajaxVentas(Request $request)
    {
        $ventas = VentaEspecialista::leftJoin('especialistas', 'venta_especialistas.especialista_id', 'especialistas.id')
            ->join('tipo_pagos', 'venta_especialistas.tipo_pago_id', 'tipo_pagos.id')
            ->join('arqueo_cajas', 'venta_especialistas.arqueo_id', 'arqueo_cajas.id')
            ->select(
                'especialistas.nombre as nombre',
                'tipo_pagos.tipo as tipo',
                DB::raw("(
                SELECT GROUP_CONCAT(clothing.name ORDER BY clothing.name SEPARATOR ', ')
                FROM clothing
                WHERE FIND_IN_SET(clothing.id, venta_especialistas.clothing_id)
            ) as servicios"),
                'venta_especialistas.*'
            )
            ->orderBy('venta_especialistas.created_at', 'desc');

        return DataTables::of($ventas)
            ->addColumn('acciones', function ($item) {
                $buttons = '';
                if ($item->estado != 'A') {
                    $buttons .= '<button type="button" class="btn btn-link text-velvet border-0" onclick="abrirAnularModal(' . $item->id . ')"><i class="material-icons text-lg">delete</i></button>';
                }
                $buttons .= '<a href="' . url('/ventas/especialistas/' . $item->id) . '" class="btn btn-link text-velvet border-0" data-bs-toggle="tooltip" title="Editar"><i class="material-icons text-lg">edit</i></a>';
                $buttons .= '<button type="button" class="btn btn-link text-velvet border-0" onclick="abrirCambioArqueoModal(' . $item->id . ', \'' . $item->created_at . '\')"><i class="material-icons text-lg">autorenew</i></button>';
                return $buttons;
            })
            ->editColumn('servicios', function ($item) {
                $servicios = str_replace(',', '<br>', $item->servicios);
                if ($item->estado === 'A') {
                    $servicios .= '<br><span class="badge badge-pill ml-2 text-xxs badge-date text-white">Anulado</span>';
                }
                return $servicios;
            })

            ->editColumn('nombre', function ($item) {
                return $item->nombre ?? 'Paquetes';
            })
            ->editColumn('monto_venta', fn($item) => '₡' . number_format($item->monto_venta))
            ->editColumn('monto_clinica', fn($item) => '₡' . number_format($item->monto_clinica))
            ->editColumn('monto_especialista', fn($item) => '₡' . number_format($item->monto_especialista))
            ->editColumn('monto_producto_venta', fn($item) => '₡' . number_format($item->monto_producto_venta))
            ->editColumn('porcentaje', fn($item) => '%' . $item->porcentaje)
            ->editColumn('created_at', fn($item) => $item->created_at->format('d/m/Y'))
            ->rawColumns(['acciones', 'servicios'])
            ->make(true);
    }
    public function arqueosValidos(Request $request)
    {
        $fecha = $request->input('fecha');
        $arqueos = ArqueoCaja::where('estado', 0)
            ->whereDate('fecha_ini', '<', $fecha)
            ->orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'fecha_ini', 'fecha_fin']);

        return response()->json($arqueos);
    }
}
