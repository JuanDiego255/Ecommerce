<?php

namespace App\Http\Controllers;
use App\Models\MedicineResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicineResultController extends Controller
{

    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexadmin($blog_id)
    {
        $results = MedicineResult::where('blog_id', $blog_id)->get();

        return view('admin.result.index', compact('results','blog_id'));
    }
    /**

     *redirects to add blog view.

     */
    public function addResult($blog_id)
    {
        return view('admin.result.add', compact('blog_id'));
    }

    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function storeResult(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'before_image' => 'required|max:10000|mimes:jpeg,png,jpg,ico',
                'after_image' => 'required|max:10000|mimes:jpeg,png,jpg,ico'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);

            $result = new MedicineResult();
            $result->blog_id = $id;
            if ($request->hasFile('before_image')) {
                $result->before_image = $request->file('before_image')->store('uploads', 'public');
            }
            if ($request->hasFile('after_image')) {
                $result->after_image = $request->file('after_image')->store('uploads', 'public');
            }
            $result->save();
            DB::commit();
            return redirect('results/' . $id)->with(['status' => 'Resultado creado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return redirect('results/' . $id)->with(['status' => 'Error al crear el resultado!', 'icon' => 'error']);
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function editResult($id, $blog_id)
    {
        $result = MedicineResult::findOrfail($id);
        return view('admin.result.edit', compact('result', 'blog_id'));
    }

    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateResult(Request $request, $id, $blog_id)
    {
        DB::beginTransaction();
        try {          
            
            $result = MedicineResult::findOrfail($id);
            
            $result->blog_id = $blog_id;
            if ($request->hasFile('before_image')) {
                Storage::delete('public/' . $result->before_image);
                $before_image_req = $request->file('before_image')->store('uploads', 'public');
                $result->before_image = $before_image_req;
            }
            if ($request->hasFile('after_image')) {
                Storage::delete('public/' . $result->after_image);
                $image = $request->file('after_image')->store('uploads', 'public');
                $result->after_image = $image;
            }
            $result->update();
            DB::commit();

            return redirect('results/' . $blog_id)->with(['status' => 'Se agregó el resultado!', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return redirect('results/' . $blog_id)->with(['status' => $th->getMessage(), 'icon' => 'error']);
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
            $result = MedicineResult::findOrfail($id);

            Storage::delete('public/' . $result->before_image);
            Storage::delete('public/' . $result->after_image);
            MedicineResult::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => 'Resultado eliminado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with(['status' => 'No se pudo eliminar el resultado!', 'icon' => 'success']);
        }
    }
}
