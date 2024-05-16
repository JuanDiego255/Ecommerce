<?php

namespace App\Http\Controllers;

use App\Models\PersonalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PersonalUserController extends Controller
{
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexadmin()
    {
        $users = PersonalUser::get();

        return view('admin.user-info.index', compact('users'));
    }
    /**

     *redirects to add blog view.

     */
    public function add()
    {
        return view('admin.user-info.add');
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'name' => 'required|string|max:100',
                'body' => 'required|string|max:10000',
                'image' => 'required|max:10000|mimes:jpeg,png,jpg,ico'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $user =  request()->except('_token');
            if ($request->hasFile('image')) {
                $user['image'] = $request->file('image')->store('uploads', 'public');
            }
            $name = $request->name;
            $user['name'] = $name;

            PersonalUser::insert($user);
            DB::commit();
            return redirect('user-info')->with(['status' => 'Profesional creado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/user-info')->with(['status' => 'No se pudo guardar el profesional', 'icon' => 'error']);
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $user = PersonalUser::findOrfail($id);
        return view('admin.user-info.edit', compact('user'));
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
                'name' => 'required|string|max:100',
                'body' => 'required|string|max:10000'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $blog =  request()->except(['_token', '_method']);
            $user = PersonalUser::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $user->image);
                $image = $request->file('image')->store('uploads', 'public');
                $user->image = $image;
            }

            $user->name = $request->name;
            $user->body = $request->body;
            $user->update();
            return redirect('user-info')->with(['status' => 'Profesional actualizado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/user-info')->with(['status' => 'No se pudo actualizar la info', 'icon' => 'error']);
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
            $user = PersonalUser::findOrfail($id);
            if (
                Storage::delete('public/' . $user->image)
            ) {
                PersonalUser::destroy($id);
            }

            PersonalUser::destroy($id);
            return redirect()->back()->with(['status' => 'Profesional  eliminado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/user-info')->with(['status' => 'No se pudo eliminar', 'icon' => 'error']);
        }
    }
}
