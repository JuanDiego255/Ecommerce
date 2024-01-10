<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\SizeCloth;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\URL;

class ClothingCategoryController extends Controller
{

    public function indexById($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $category_id = $category->id;
        $clothings = ClothingCategory::where('clothing.category_id', $id)
            ->where('clothing.status', 1)
            ->join('categories', 'clothing.category_id', 'categories.id')
            ->join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->join('sizes', 'stocks.size_id', 'sizes.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')
                    ->whereRaw('product_images.id = (
                        SELECT MIN(id) FROM product_images 
                        WHERE product_images.clothing_id = clothing.id
                    )');
            })
            ->select(
                'categories.name as category',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.name as name',
                'clothing.description as description',
                'clothing.price as price',
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                'product_images.image as image' // Obtener la imagen del producto
            )
            ->groupBy('clothing.id', 'categories.name', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
            ->simplePaginate(3);

        return view('admin.clothing.index', compact('clothings', 'category_name', 'category_id'));
    }

    public function add($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $sizes = Size::get();
        return view('admin.clothing.add', compact('id', 'category_name', 'sizes'));
    }
    public function edit($id, $category_id)
    {

        $clothing = ClothingCategory::join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')
                    ->whereRaw('product_images.id = (
                    SELECT MIN(id) FROM product_images 
                    WHERE product_images.clothing_id = clothing.id
                )');
            })
            ->where('clothing.id', $id)
            ->select(
                'clothing.id as id',
                'clothing.category_id as category_id',
                'clothing.name as name',
                'clothing.trending as trending',
                'clothing.description as description',
                'clothing.price as price',
                DB::raw('SUM(stocks.stock) as total_stock'),
                'product_images.image as image' // Obtener la primera imagen del producto
            )
            ->groupBy(
                'clothing.id',
                'clothing.name',
                'clothing.category_id',
                'clothing.description',
                'clothing.trending',
                'clothing.price',
                'product_images.image'
            )
            ->first();
        $size_active = SizeCloth::where('clothing_id', $id)->get();
        $sizes = Size::get();
        return view('admin.clothing.edit', compact('clothing', 'size_active', 'sizes', 'category_id'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $clothing =  new ClothingCategory();

            $clothing->category_id = $request->category_id;
            $clothing->name = $request->name;
            $clothing->description = $request->description;
            $clothing->price = $request->price;

            $sizes = $request->input('sizes_id');

            if ($sizes == null) {
                return redirect('/new-item/' . $request->category_id)->with(['status' => 'Debe seleccionar al menos una talla!', 'icon' => 'warning']);
            }

            $clothing->status = 1;

            if ($request->trending == 1) {
                $clothing->trending = 1;
            } else {
                $clothing->trending = 0;
            }

            $clothing->save();
            $clothingId = $clothing->id;



            if ($request->hasFile('images')) {
                $images = $request->file('images');

                foreach ($images as $image) {
                    $imageObj = new ProductImage();
                    $imageObj->clothing_id = $clothingId;
                    $imageObj->image = $image->store('uploads', 'public');
                    $imageObj->save();
                }
            }

            foreach ($sizes as $size) {
                $size_cloth =  new SizeCloth();
                $size_cloth->size_id = $size;
                $size_cloth->clothing_id = $clothingId;
                $size_cloth->save();

                $stock =  new Stock();
                $stock->size_id = $size;
                $stock->stock = $request->stock;
                $stock->clothing_id = $clothingId;
                $stock->save();
            }
            DB::commit();
            return redirect('/add-item/' . $request->category_id)->with(['status' => 'Prenda Agregada Exitosamente!', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollback();
            return redirect('/new-item/' . $request->category_id)->with(['status' => 'Ocurrió un error al agregar la prenda!', 'icon' => 'error']);
        }
    }
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('images')) {
                $clothing = ClothingCategory::findOrfail($id);            

                $clothing->category_id = $request->category_id;
                $clothing->name = $request->name;
                $clothing->description = $request->description;
                $clothing->price = $request->price;               

                $clothing->status = 1;

                if ($request->trending == 1) {
                    $clothing->trending = 1;
                } else {
                    $clothing->trending = 0;
                }

                $sizes = $request->input('sizes_id');

                if ($sizes == null) {
                    return redirect('/edit-clothing/' . $request->category_id)->with(['status' => 'Debe seleccionar al menos una talla!', 'icon' => 'warning']);
                }

                $clothing->update();
                SizeCloth::where('clothing_id', $id)->delete();
              
                $imagesProduct =  ProductImage::where('clothing_id', $id)->get();

                foreach($imagesProduct as $img){
                    Storage::delete('public/' . $img->image);
                }

                ProductImage::where('clothing_id', $id)->delete();

                if ($request->hasFile('images')) {
                    $images = $request->file('images');
    
                    foreach ($images as $image) {
                        $imageObj = new ProductImage();
                        $imageObj->clothing_id = $id;
                        $imageObj->image = $image->store('uploads', 'public');
                        $imageObj->save();
                    }
                }

               

                foreach ($sizes as $size) {
                    $size_cloth =  new SizeCloth();
                    $size_cloth->size_id = $size;
                    $size_cloth->clothing_id = $id;
                    $size_cloth->save();

                    $stock = Stock::where('clothing_id', $id)
                        ->where('size_id', $size)->first();
                    if ($stock === null) {
                        $stock =  new Stock();
                        $stock->size_id = $size;
                        $stock->stock = $request->stock;
                        $stock->clothing_id = $id;
                        $stock->save();
                    } else {
                        if ($stock->stock == 0) {
                            Stock::where('clothing_id', $id)
                                ->where('size_id', $size)->update(['stock' => $request->stock]);
                        }
                    }
                }
                DB::commit();
                return redirect('add-item/' . $request->category_id)->with(['status' => 'Prenda Editada Con Exito!', 'icon' => 'success']);
            }
        } catch (Exception $th) {
            DB::rollback();
            return redirect()->back()->with(['status' => 'Ocurrió un error al editar la prenda!', 'icon' => 'warning']);
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $clothing = ClothingCategory::findOrfail($id);

            $clothing_name = $clothing->name;
            if (
                Storage::delete('public/' . $clothing->image)

            ) {
                ClothingCategory::destroy($id);
            }
            Stock::where('clothing_id', $id)->delete();
            SizeCloth::where('clothing_id', $id)->delete();
            ClothingCategory::destroy($id);
            DB::commit();
            return redirect()->back()->with(['status' => $clothing_name . ' se ha borrado la prenda con éxito', 'icon' => 'success']);
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with(['status' => 'Ocurrió un error al eliminar la prenda!', 'icon' => 'error']);
        }
    }
}
