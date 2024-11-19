<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    //
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function index()
    {
        $roles = Cache::remember('roles', $this->expirationTime, function () {
            return Roles::get();
        });

        return view('admin.roles.index', compact('roles'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rol_add =  new Roles();            
            $rol_add->rol = $request->rol;
            $rol_add->status = 1;            
            $rol_add->save();
            DB::commit();
            return redirect('/roles')->with(['status' => 'Rol Agregado Exitosamente!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/roles')->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $rol_edit = Roles::findOrfail($id);
            $rol_edit->rol = $request->rol;   
            $rol_edit->status = $request->status == 1 ? 1 : 0;   
            $rol_edit->update();
            DB::commit();
            return redirect('roles')->with(['status' => 'Rol Editado Exitosamente!', 'icon' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('/roles')->with(['status' => 'Ocurrió un error al editar el rol!', 'icon' => 'error']);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $rol = Roles::findOrfail($id);
            $rol_name = $rol->rol;
            Roles::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status'  =>  '(' . $rol_name . ') se ha borrado el rol con éxito', 'icon' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('/roles')->with(['status' => 'Ocurrió un error al eliminar el departamento!', 'icon' => 'error']);
        }
    }
}
