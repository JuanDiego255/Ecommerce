<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function index()
    {
        $departments = Cache::remember('departments', $this->expirationTime, function () {
            return Department::where('department','!=','Default')
            ->orderBy('departments.department','asc')
            ->get();
        });

        return view('admin.departments.index', compact('departments'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $department =  new Department();
            
            if ($request->hasFile('image')) {
                $department->image = $request->file('image')->store('uploads', 'public');
            }
            
            $department->department = $request->department;
            
            $department->save();
            DB::commit();
            return redirect('/departments')->with(['status' => 'Departamento Agregado Exitosamente!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/departments')->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $department = Department::findOrfail($id);
            $department->department = $request->department;            
            if ($request->hasFile('image')) {

                Storage::delete('public/' . $department->image);
                $image = $request->file('image')->store('uploads', 'public');
                $department->image = $image;
            }
            $department->update();
            DB::commit();
            return redirect('departments')->with(['status', 'Departamento Editado Exitosamente!', 'icon' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('/departments')->with(['status' => 'Ocurrió un error al editar la categoría!', 'icon' => 'error']);
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $department = Department::findOrfail($id);
            $department_name = $department->department;
            if (
                Storage::delete('public/' . $department->image)

            ) {
                Department::destroy($id);
            }
            Department::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status'  =>  '(' . $department_name . ') se ha borrado la categoría con éxito', 'icon' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('/categories')->with(['status' => 'Ocurrió un error al eliminar el departamento!', 'icon' => 'error']);
        }
    }
}
