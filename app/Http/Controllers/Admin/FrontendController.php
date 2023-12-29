<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\MetaTags;
use App\Models\SizeCloth;
use App\Models\SocialNetwork;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class FrontendController extends Controller
{
    public function index()
    {
        $social = SocialNetwork::get();
        $tags = MetaTags::where('section', 'Inicio')->get();

        $clothings = ClothingCategory::where('clothing.trending', 1)            
            ->join('categories', 'clothing.category_id', 'categories.id')
            ->join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->join('sizes', 'stocks.size_id', 'sizes.id')
            ->select(
                'categories.name as category',
                'categories.id as category_id',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.name as name',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.image as image',
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size') // Obtener stock por talla
            )
            ->groupBy('clothing.id', 'categories.name','categories.id', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'clothing.image')
            ->take(15)->get();

        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.index', compact('clothings', 'social'));
    }
    public function category()
    {
        $category = Categories::where('status', 0)->get();
        $tags = MetaTags::where('section', 'Categorías')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.category', compact('category'));
    }
    public function clothesByCategory($id)
    {
        $category = Categories::find($id);
        $category_name = $category->name;
        $category_id = $category->id;
        $clothings = ClothingCategory::where('clothing.category_id', $id)
            ->where('clothing.status', 1)
            ->join('categories', 'clothing.category_id', 'categories.id')
            ->join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->join('sizes', 'stocks.size_id', 'sizes.id')
            ->select(
                'categories.name as category',
                'clothing.id as id',
                'clothing.name as name',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.image as image',
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size') // Obtener stock por talla
            )
            ->groupBy('clothing.id', 'categories.name', 'clothing.name', 'clothing.description', 'clothing.price', 'clothing.image')
            ->simplePaginate(3);
        if (count($clothings) == 0) {
            return redirect()->back()->with(['status' => 'No hay artículos en esta categoría', 'icon' => 'warning']);
        }
        $tags = MetaTags::where('section', 'Categoría Específica')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.clothes-category', compact('clothings', 'category_name', 'category_id'));
    }
    public function DetailClothingById($id, $category_id)
    {
        $clothes = ClothingCategory::where('clothing.id', $id)
            ->where('clothing.status', 1)
            ->join('categories', 'clothing.category_id', 'categories.id')
            ->join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->join('sizes', 'stocks.size_id', 'sizes.id')
            ->select(
                'categories.name as category',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.name as name',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.image as image',
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.id) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size') // Obtener stock por talla
            )
            ->groupBy('clothing.id', 'categories.name', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'clothing.image')
            ->get();
        //$clothes = ClothingCategory::where('id', $id)->get();
        $size_active = SizeCloth::where('clothing_id', $id)
            ->join('sizes', 'size_cloths.size_id', 'sizes.id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('stocks')
                    ->whereRaw('stocks.size_id = size_cloths.size_id')
                    ->whereRaw('stocks.clothing_id = size_cloths.clothing_id')
                    ->where('stocks.stock', 0);
            })
            ->select(
                'sizes.id as id',
                'sizes.size as size'
            )
            ->get();
        $tags = MetaTags::where('section', 'Categoría Específica')->get();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        return view('frontend.detail-clothing', compact('clothes', 'category_id', 'size_active'));
    }
}
