<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\MetaTags;
use App\Models\ProductImage;
use App\Models\Seller;
use App\Models\SizeCloth;
use App\Models\SocialNetwork;
use App\Models\TenantInfo;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class FrontendController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60; // Por ejemplo, 60 minutos
    }
    public function index()
    {
        $tenantinfo = TenantInfo::first();
        switch ($tenantinfo->kind_business) {
            case (1):
                return redirect('index/carsale');
                break;
            case (2):
                return redirect('spa/index/');
                break;
            default:
                break;
        }
        if ($tenantinfo->kind_business == 1) {
            return redirect('index/carsale');
        }
        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $tags = Cache::remember('meta_tags_inicio', $this->expirationTime, function () {
            return MetaTags::where('section', 'Inicio')->get();
        });
        $clothings = Cache::remember('clothings_trending', $this->expirationTime, function () {
            return ClothingCategory::where('clothing.trending', 1)
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $category = Cache::remember('categories', $this->expirationTime, function () {
            return Categories::where('departments.department', 'Default')
                ->where('categories.status', 0)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                )->orderBy('categories.name', 'asc')
                ->get();
        });

        // Obtener las primeras imágenes de las prendas obtenidas
        foreach ($clothings as $clothing) {
            $firstImage = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->first();
            // Asignar la imagen al objeto $clothing
            $clothing->image = $firstImage ? $firstImage->image : null;
        }

        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }
        //Promociones
        $clothings_offer = Cache::remember('clothings_offer', $this->expirationTime, function () {
            return ClothingCategory::where('categories.name', 'Sale')
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'categories.id',
                    'clothing.name',
                    'clothing.casa',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )
                ->orderBy('clothing.name', 'asc')
                ->take(8)
                ->get();
        });

        // Obtener las primeras imágenes de las prendas obtenidas
        foreach ($clothings_offer as $offer) {
            $firstTwoImages = ProductImage::where('clothing_id', $offer->id)
                ->orderBy('id')
                ->take(2) // Limitar a las primeras dos imágenes
                ->get();

            // Obtener las rutas de las imágenes
            $imagePaths = $firstTwoImages->pluck('image')->toArray();

            // Asegurarse de tener al menos un elemento en el array
            $offer->images = $imagePaths ?: [null];
        }

        $blogs = Blog::take(4)->get();

        return view('frontend.index', compact('clothings','blogs', 'social', 'clothings_offer', 'category'));
    }
    public function category($id = null)
    {
        if ($id == null) {
            $department = Department::where('department', 'Default')->first();
            $department_id = $department->id;
        } else {
            $department = Department::where('id', $id)->first();
            $department_id = $department->id;
        }
        $category = Categories::where('department_id', $department_id)
            ->orderBy('categories.name', 'asc')
            ->simplePaginate(8);
        $department_name = $department->department;

        $tags = Cache::remember('meta_tags', $this->expirationTime, function () {
            return MetaTags::where('section', 'Categorías')->get();
        });

        $tenantinfo = Cache::remember('tenant_info', $this->expirationTime, function () {
            return TenantInfo::first();
        });

        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });

        return view('frontend.category', compact('category', 'department_name', 'department_id'));
    }
    public function clothesByCategory($id, $department_id)
    {
        $category = Cache::remember('category_' . $id, $this->expirationTime, function () use ($id) {
            return Categories::find($id);
        });

        $category_name = $category->name;
        $category_id = $category->id;

        if ($department_id == null) {
            $department = Department::where('department', 'Default')->first();
            $department_id = $department->id;
        } else {
            $department = Department::where('id', $department_id)->first();
            $department_id = $department->id;
        }
        $department_name = $department->department;

        $clothings = Cache::remember('clothings_' . $id, $this->expirationTime, function () use ($id) {
            return ClothingCategory::where('clothing.category_id', $id)
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
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    'product_images.image as image', // Obtener la primera imagen del producto
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy('clothing.id', 'clothing.casa', 'clothing.mayor_price', 'categories.name', 'clothing.discount', 'clothing.name', 'clothing.description', 'clothing.price', 'product_images.image')
                ->orderBy('clothing.name', 'asc')
                ->simplePaginate(20);
        });

        $tags = Cache::remember('meta_tags_specific_category', $this->expirationTime, function () {
            return MetaTags::where('section', 'Categoría Específica')->get();
        });

        $tenantinfo = Cache::remember('tenant_info', $this->expirationTime, function () {
            return TenantInfo::first();
        });


        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        if (count($clothings) == 0 && $tenantinfo->kind_business != 2) {
            return redirect()->back()->with(['status' => 'No hay artículos en esta categoría', 'icon' => 'warning']);
        }
        switch ($tenantinfo->kind_business) {
            case (2):
                return view('frontend.website.clothes-category', compact('clothings', 'category_name', 'category_id', 'department_id', 'department_name', 'category'));
                break;
            default:
                return view('frontend.clothes-category', compact('clothings', 'category_name', 'category_id', 'department_id', 'department_name'));
        }
    }
    public function DetailClothingById($id, $category_id)
    {
        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $sellers = Cache::remember('sellers', $this->expirationTime, function () {
            return Seller::get();
        });
        $category = Cache::remember('categories', $this->expirationTime, function () {
            return Categories::where('departments.department', 'Default')
                ->where('categories.status', 0)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                )->orderBy('categories.name', 'asc')
                ->get();
        });
        $clothings = Cache::remember('clothings_trending', $this->expirationTime, function () {
            return ClothingCategory::where('clothing.trending', 1)
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $clothes = ClothingCategory::where('clothing.id', $id)
            ->where('clothing.status', 1)
            ->join('categories', 'clothing.category_id', 'categories.id')
            ->join('departments', 'categories.department_id', 'departments.id')
            ->join('stocks', 'clothing.id', 'stocks.clothing_id')
            ->join('sizes', 'stocks.size_id', 'sizes.id')
            ->leftJoin('product_images', 'clothing.id', '=', 'product_images.clothing_id')
            ->select(
                'categories.name as category',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.name as name',
                'clothing.casa as casa',
                'clothing.can_buy as can_buy',
                'departments.id as department_id',
                'departments.department as department_name',
                'clothing.discount as discount',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.mayor_price as mayor_price',
                'product_images.image as image', // columna de imagen
                DB::raw('GROUP_CONCAT(product_images.image ORDER BY product_images.id ASC) AS images'),
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.id) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                DB::raw('GROUP_CONCAT(stocks.price) AS price_per_size'),
                DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
            )
            ->groupBy('clothing.id','clothing.can_buy', 'clothing.casa', 'departments.id', 'departments.department', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
            ->orderBy('clothing.name', 'asc')
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
        $tenantinfo = TenantInfo::first();
        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $clothings_trending = ClothingCategory::where('clothing.trending', 1)
            ->where('clothing.id', '!=', $id)
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
                'categories.id as category_id',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.discount as discount',
                'clothing.name as name',
                'clothing.casa as casa',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.mayor_price as mayor_price',
                DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                DB::raw('SUM(stocks.stock) as total_stock'),
                DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                DB::raw('GROUP_CONCAT(stocks.price) AS price_per_size'),
                DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
            )
            ->groupBy('clothing.id', 'clothing.casa', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'categories.id', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
            ->orderBy('clothing.name', 'asc')
            ->inRandomOrder()
            ->take(8)
            ->get();

        switch ($tenantinfo->kind_business) {

            case (1):
                return view('frontend.carsale.detail-car', compact('clothes', 'category_id', 'clothings_trending'));
                break;
            case (2):
                return view('frontend.website.detail-clothing', compact('clothes', 'category_id', 'size_active', 'clothings_trending'));
                break;
            default:
                return view('frontend.detail-clothing', compact('clothes', 'category_id', 'size_active', 'clothings_trending'));
        }
    }

    public function departments()
    {
        $departments = Cache::remember('departments', $this->expirationTime, function () {
            return Department::where('department', '!=', 'Default')
                ->orderBy('departments.department', 'asc')
                ->simplePaginate(8);
        });

        return view('frontend.departments', compact('departments'));
    }

    public function indexCarSale()
    {
        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $sellers = Cache::remember('sellers', $this->expirationTime, function () {
            return Seller::get();
        });
        $tags = Cache::remember('meta_tags_inicio', $this->expirationTime, function () {
            return MetaTags::where('section', 'Inicio')->get();
        });
        $clothings = Cache::remember('clothings_trending', $this->expirationTime, function () {
            return ClothingCategory::where('clothing.trending', 1)
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $category = Cache::remember('categories', $this->expirationTime, function () {
            return Categories::where('departments.department', 'Default')
                ->where('categories.status', 0)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                )->orderBy('categories.name', 'asc')
                ->get();
        });

        // Obtener las primeras imágenes de las prendas obtenidas
        foreach ($clothings as $clothing) {
            $firstImage = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->first();
            // Asignar la imagen al objeto $clothing
            $clothing->image = $firstImage ? $firstImage->image : null;
        }

        $tenantinfo = TenantInfo::first();

        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $blogs = Blog::take(4)->get();

        return view('frontend.carsale.index', compact('clothings','blogs', 'social', 'category', 'sellers'));
    }  
    public function indexSpa()
    {
        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $sellers = Cache::remember('sellers', $this->expirationTime, function () {
            return Seller::get();
        });
        $tags = Cache::remember('meta_tags_inicio', $this->expirationTime, function () {
            return MetaTags::where('section', 'Inicio')->get();
        });
        $clothings = Cache::remember('clothings_trending', $this->expirationTime, function () {
            return ClothingCategory::where('clothing.trending', 1)
                ->join('categories', 'clothing.category_id', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->join('sizes', 'stocks.size_id', 'sizes.id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(sizes.size) AS available_sizes'), // Obtener tallas dinámicas
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $category = Cache::remember('categories', $this->expirationTime, function () {
            return Categories::where('departments.department', 'Default')
                ->where('categories.status', 0)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                )->orderBy('categories.name', 'asc')
                ->get();
        });

        // Obtener las primeras imágenes de las prendas obtenidas
        foreach ($clothings as $clothing) {
            $firstImage = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->first();
            // Asignar la imagen al objeto $clothing
            $clothing->image = $firstImage ? $firstImage->image : null;
        }

        $tenantinfo = TenantInfo::first();

        foreach ($tags as $tag) {
            SEOMeta::setTitle($tag->title . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($tag->meta_keywords);
            SEOMeta::setDescription($tag->meta_description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $blogs = Blog::take(4)->get();

        return view('frontend.website.index', compact('clothings','blogs', 'social', 'category', 'sellers'));
    }
}
