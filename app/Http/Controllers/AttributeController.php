<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    //
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function indexadmin()
    {
        $attributes = Attribute::where('name','!=','Stock')->get();

        return view('admin.attributes.index', compact('attributes'));
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
                'name' => 'required|string|max:100'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);

            $attr = new Attribute();
            $attr->name = $request->name;
            $attr->type = $request->type;
            $attr->save();

            DB::commit();
            return redirect('attributes')->with(['status' => 'Atributo creado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/attributes')->with(['status' => 'No se pudo guardar el atributo' . $th->getMessage(), 'icon' => 'error']);
        }
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $attr = Attribute::findOrfail($id);
        return view('admin.attributes.edit', compact('attr'));
    }
    public function mainAttribute($id, Request $request)
    {
        //
        DB::beginTransaction();
        try {

            if ($request->main == "1") {
                Attribute::where('id', $id)->update(['main' => 1]);
                Attribute::where('id', '!=', $id)->update(['main' => 0]);
            } else {
                return redirect()->back()->with(['status' => 'No puede deseleccionar el atributo', 'icon' => 'warning']);
            }

            DB::commit();
            return redirect()->back()->with(['status' => 'Se convirtió este atributo en principal', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
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
            $attr = Attribute::findOrfail($id);

            $attr->name = $request->name;
            $attr->type = $request->type;
            $attr->update();
            db::commit();
            return redirect('attributes')->with(['status' => 'Atributo actualizado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/attributes')->with(['status' => 'No se pudo actualizar el atributo', 'icon' => 'error']);
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
            Attribute::destroy($id);
            db::commit();
            return redirect()->back()->with(['status' => 'Atributo  eliminado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/attributes')->with(['status' => 'No se pudo eliminar', 'icon' => 'error']);
        }
    }
    //Metodos para valores de los atributos ------------------------>

    /**

     * Get all the articles of the blog.

     *

     * @param Request $request


     */
    public function values($id)
    {
        $values_attr = DB::table('attribute_values')
            ->where('attribute_id', $id)->join('attributes', 'attribute_values.attribute_id', 'attributes.id')
            ->select(
                'attributes.name as name',
                'attributes.id as attr_id',
                'attributes.type as type',
                'attribute_values.id as value_id',
                'attribute_values.value as value'
            )
            ->get();

        $attr = Attribute::where('id', $id)->first();

        return view('admin.attributes.values.index', compact('values_attr', 'id', 'attr'));
    }

    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function editValue($attr_id, $id)
    {
        $value = AttributeValue::findOrfail($id);
        return view('admin.attributes.values.edit', compact('value', 'attr_id'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateValue(Request $request, $id, $attr_id)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'value' => 'required|string|max:1000'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $value_attr = AttributeValue::findOrfail($id);
            $value_attr->attribute_id = $attr_id;
            $value_attr->value = $request->value;
            $value_attr->save();
            db::commit();

            return redirect('/attribute-values/' . $attr_id)->with(['status' => 'Valor editado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('/attribute-values/' . $attr_id)->with(['status' => 'No se pudo editar', 'icon' => 'error']);
        }
    }
    /**

     *Store the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function storeValue(Request $request, $attr_id)
    {
        DB::beginTransaction();
        try {
            $campos = [
                'value' => 'required|string|max:1000'
            ];
            $mensaje = ["required" => 'El :attribute es requerido'];
            $this->validate($request, $campos, $mensaje);
            $value_attr = new AttributeValue();
            $value_attr->attribute_id = $attr_id;
            $value_attr->value = $request->value;
            $value_attr->save();
            db::commit();

            return redirect('attribute-values/' . $attr_id)->with(['status' => 'Valor agregado con éxito!', 'icon' => 'success']);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect('attribute-values/' . $attr_id)->with(['status' => 'Valor no agregado!', 'icon' => 'error']);
        }
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroyValue($id)
    {
        AttributeValue::destroy($id);
        return redirect()->back()->with(['status' => 'Valor eliminado con éxito!', 'icon' => 'success']);
    }
    public function getValues($id)
    {
        $values = AttributeValue::where('attribute_id', $id)
            ->join('attributes', 'attribute_values.attribute_id', 'attributes.id')
            ->select(
                'attributes.name as name',
                'attributes.id as attr_id',
                'attributes.main as main',
                'attributes.type as type',
                'attribute_values.id as id',
                'attribute_values.value as value'
            )
            ->get();
        return response()->json($values);
    }
    public function getAttrId($id)
    {
        $attr_id = AttributeValue::where('id', $id)->first();
        return response()->json($attr_id);
    }
}
