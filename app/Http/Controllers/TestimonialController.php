<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexAdmin()
    {
        $comments = Testimonial::get();

        return view('admin.testimonial.index', compact('comments'));
    }
    /**

     *redirects to add blog view.

     */
    public function add()
    {
        return view('admin.testimonial.add');
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
                'description' => 'required|string|max:10000'           
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $comment = new Testimonial();
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('uploads', 'public');
                $comment->image = $image;
            }
            
            $comment->name = $request->name;
            $comment->stars = $request->rating;
            $comment->description = $request->description;
            $comment->approve = $request->approve;
            $comment->save();
            
            DB::commit();
            if($request->approve != 0){
                return redirect('comments')->with(['status' => 'Testimonio creado con éxito!', 'icon' => 'success']);
            }else{
                return redirect()->back()->with(['status' => 'Testimonio creado con éxito, una vez que se apruebe se mostrará!', 'icon' => 'success']);
            }
           
        } catch (\Exception $th) {
            DB::rollBack();
            if($request->approve != 0){
                return redirect('comments')->with(['status' => 'Error al crear el testimonio!', 'icon' => 'success']);
            }else{
                return redirect()->back()->with(['status' => 'Error al crear el testimonio', 'icon' => 'success']);
            }
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $comment = Testimonial::findOrfail($id);
        return view('admin.testimonial.edit', compact('comment'));
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
                'description' => 'required|string|max:10000'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $comment = Testimonial::findOrfail($id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $comment->image);
                $image = $request->file('image')->store('uploads', 'public');
                $comment->image = $image;
            }

            $comment->name = $request->name;
            $comment->description = $request->description;
            $comment->stars = $request->rating;
            $comment->approve = $request->approve;
            $comment->update();            
            db::commit();
            return redirect('comments')->with(['status' => 'Testimonio actualizado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/comments')->with(['status' => 'No se pudo actualizar la info', 'icon' => 'error']);
        }
    }

    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            
            $comment = Testimonial::findOrfail($id);     
            $comment->approve = $request->approve ? 1 : 0;
            $comment->update();            
            db::commit();
            return redirect('comments')->with(['status' => 'Estado actualizado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/comments')->with(['status' => 'No se pudo actualizar el estado', 'icon' => 'error']);
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
            $comment = Testimonial::findOrfail($id);
            if (
                Storage::delete('public/' . $comment->image)
            ) {
                Testimonial::destroy($id);
            }

            Testimonial::destroy($id);
            db::commit();
            return redirect()->back()->with(['status' => 'Testimonio  eliminado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo eliminar', 'icon' => 'error']);
        }
    }
}
