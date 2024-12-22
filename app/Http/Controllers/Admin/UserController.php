<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralCategory;
use App\Models\Log;
use App\Models\Roles;
use App\Models\Routine;
use App\Models\RoutineDays;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::join('roles','users.role_as','roles.id')->get();
        return view('admin.users.index', compact('users'));
    }
    public function mayor($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            if ($request->status == "1") {
                User::where('id', $id)->update(['mayor' => 1]);
            } else {
                User::where('id', $id)->update(['mayor' => 0]);
            }

            DB::commit();
            return redirect('/users')->with(['status' => 'Se cambio el estado (Al por mayor) para este usuario', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $user = User::findOrfail($id);
        $roles = Roles::where('status', 1)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'name' => 'required|string|max:100'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $user = User::findOrfail($id);

            $user->name = $request->name;
            $user->telephone = $request->telephone;
            $user->email = $request->email;
            $user->role_as = $request->role_id;
            $user->update();
            db::commit();
            return redirect('users')->with(['status' => 'Usuario actualizado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/users')->with(['status' => 'No se pudo actualizar el atributo', 'icon' => 'error']);
        }
    }

    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            User::destroy($id);
            db::commit();
            return redirect()->back()->with(['status' => 'Usuario  eliminado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/users')->with(['status' => 'No se pudo eliminar el usuario', 'icon' => 'error']);
        }
    }
    public function reportLogs($type)
    {
        $logs = Log::join('users', 'logs.user_id', 'users.id')
            ->select('users.name','logs.*')
            ->where('logs.type', $type)->get();
        return view('admin.reports.logs', compact('logs', 'type'));
    }
}
