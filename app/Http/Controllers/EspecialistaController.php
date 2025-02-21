<?php

namespace App\Http\Controllers;

use App\Models\ClothingCategory;
use App\Models\Especialista;
use App\Models\PivotServiciosEspecialista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables; // Importación correcta

class EspecialistaController extends Controller
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
        $especialistas = Especialista::get();
        return view('admin.especialistas.index', compact('especialistas'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexServices($id)
    {
        //
        $especialista = Especialista::where('id', $id)->first();
        $services = PivotServiciosEspecialista::where('especialista_id', $id)
            ->join('clothing', 'pivot_servicios_especialistas.clothing_id', 'clothing.id')
            ->select(
                'clothing.name as nombre',
                'clothing.id as service_id'
            )
            ->get();
        return view('admin.especialistas.index-services', compact('services', 'especialista'));
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
            $especialista =  new  Especialista();
            $especialista->nombre = $request->nombre;
            $especialista->salario_base = $request->salario_base;
            $especialista->monto_por_servicio = $request->monto_por_servicio;
            $especialista->save();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha guardado el especialista con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo guardar la caja', 'icon' => 'error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logos  $logos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try {
            $especialista = Especialista::findOrfail($id);
            $especialista->nombre = $request->nombre;
            $especialista->salario_base = $request->salario_base;
            $especialista->monto_por_servicio = $request->monto_por_servicio;
            $especialista->update();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha editado el especialista con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo editar el especialista', 'icon' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logos  $logos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            Especialista::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el especialista con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function getProductsToSelect($id, Request $request)
    {
        $search = $request->input('search'); // Captura el término de búsqueda

        $clothings = ClothingCategory::leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
            ->leftJoin('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
            ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
            ->select(
                'clothing.id as service_id',
                DB::raw('CONCAT(clothing.name, " (", categories.name, ")") as name'),
                DB::raw('CONCAT("/", clothing.id, "/", categories.id, "/") as url')
            )
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('clothing.name', 'like', "%{$search}%");
                }
            })
            ->where('clothing.status', 1)
            ->whereNotExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('pivot_servicios_especialistas')
                    ->whereRaw('pivot_servicios_especialistas.clothing_id = clothing.id')
                    ->whereRaw('pivot_servicios_especialistas.especialista_id = ?', [$id]); // Filtra por especialista_id
            })
            ->groupBy(
                'clothing.id',
                'categories.id',
                'clothing.name',
                'categories.name'
            )->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
            ->orderBy('clothing.casa', 'asc')
            ->orderBy('clothing.name', 'asc')
            ->get();

        return response()->json($clothings);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeService(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $service =  new  PivotServiciosEspecialista();
            $service->clothing_id = $request->clothing_id;
            $service->especialista_id = $request->especialista_id;
            $service->porcentaje = $request->porcentaje;
            $service->save();
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['status' => false]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logos  $logos
     * @return \Illuminate\Http\Response
     */
    public function destroyService($id, $esp_id)
    {
        //
        DB::beginTransaction();
        try {
            PivotServiciosEspecialista::where('clothing_id', $id)->where('especialista_id', $esp_id)->delete();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se ha eliminado el servicio con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function listServices($id)
    {
        $services = PivotServiciosEspecialista::where('especialista_id', $id)
            ->join('clothing', 'pivot_servicios_especialistas.clothing_id', 'clothing.id')
            ->select(
                'clothing.name as nombre',
                DB::raw("CONCAT(pivot_servicios_especialistas.porcentaje, '%') as porcentaje"),
                'clothing.id as service_id'
            )
            ->get();

        return DataTables::of($services)
            ->addColumn('acciones', function ($item) use ($id) {
                return '<form method="post" action="/especialistas/destroy/service/' . $item->service_id . '/' . $id . '" style="display:inline">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" onclick="return confirm(\'Deseas borrar este servicio?\')" class="btn btn-admin-delete">Borrar</button>
                    </form>';
            })
            ->rawColumns(['acciones']) // Para renderizar el HTML en la columna "acciones"
            ->toJson();
    }
}
