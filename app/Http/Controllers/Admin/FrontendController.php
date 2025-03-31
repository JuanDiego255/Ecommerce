<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\ClothingDetails;
use App\Models\Department;
use App\Models\Logos;
use App\Models\MetaTags;
use App\Models\Metrica;
use App\Models\ProductImage;
use App\Models\Seller;
use App\Models\SocialNetwork;
use App\Models\Stock;
use App\Models\TenantInfo;
use App\Models\Testimonial;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
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
    public function index($showModal = null)
    {
        $tenantinfo = TenantInfo::first();

        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $tags = Cache::remember('meta_tags_inicio', $this->expirationTime, function () {
            return MetaTags::where('section', 'Inicio')->get();
        });
        $clothings = Cache::remember('clothings_trending', $this->expirationTime, function () use ($tenantinfo) {
            return ClothingCategory::where('clothing.trending', 1)
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
                ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.main_image as main_image',
                    'clothing.created_at as created_at',
                    'clothing_details.modelo as model',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->when($tenantinfo->tenant === 'sakura318', function ($query) {
                    return $query->where('categories.slug', 'LIKE', '%especial%'); // Ajusta la condición según necesidad
                })
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.main_image',
                    'clothing.created_at',
                    'clothing_details.modelo',
                    'clothing.casa',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                ->orderBy('clothing.casa', 'asc')
                ->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $category = Cache::remember('categories', $this->expirationTime, function () use ($tenantinfo) {
            $query = Categories::join('departments', 'categories.department_id', 'departments.id')
                ->where('categories.status', 0)
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                    'categories.meta_title as meta_title',
                    'categories.black_friday as black_friday'
                );
        
            if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1) {                
                $query->where('departments.department', 'Default');
            }
        
            return $query->orderBy('categories.name', 'asc')
                ->take(7)
                ->get();
        });        

        $sellers = Cache::remember('sellers', $this->expirationTime, function () {
            return Seller::get();
        });

        // Obtener las primeras imágenes de las prendas obtenidas
        foreach ($clothings as $clothing) {
            // Obtener la primera imagen
            $firstImage = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->first();
            $clothing->image = $firstImage ? $firstImage->image : null;
            $clothing->all_images = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->pluck('image')
                ->toArray();

            // Obtener atributos
            $result = DB::table('stocks as s')->where('s.clothing_id', $clothing->id)
                ->join('attributes as a', 's.attr_id', '=', 'a.id')
                ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
                ->select(
                    'a.name as columna_atributo',
                    'a.id as attr_id',
                    DB::raw('GROUP_CONCAT(v.value ORDER BY s.order ASC SEPARATOR "/") as valores'),
                    DB::raw('GROUP_CONCAT(v.id ORDER BY s.order ASC SEPARATOR "/") as ids'),
                    DB::raw('GROUP_CONCAT(s.stock ORDER BY s.order ASC SEPARATOR "/") as stock'),
                )
                ->groupBy('a.name', 'a.id')
                ->orderBy('a.name', 'asc')
                ->get();
            $clothing->atributos = $result->toArray();
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
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.discount as discount',
                    'clothing.main_image as main_image',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'categories.id',
                    'clothing.main_image',
                    'clothing.name',
                    'clothing.casa',
                    'clothing.discount',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )
                ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                ->orderBy('clothing.casa', 'asc')
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

        $blogs = Blog::inRandomOrder()->orderBy('title', 'asc')
            ->take(4)->get();

        $comments = Testimonial::where('approve', 1)->inRandomOrder()->orderBy('name', 'asc')
            ->get();
        $advert = Advert::where('section', 'inicio')->latest()->first();
        $car_count = ClothingCategory::where('status', 1)->count();
        $comment_count = Testimonial::where('approve', 1)->count();
        switch ($tenantinfo->kind_business) {
            case (1):
                $clothings = Cache::remember('clothings_cars', $this->expirationTime, function () {
                    return ClothingCategory::leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                        ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                        ->leftJoin('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
                        ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                        ->select(
                            'categories.name as category',
                            'categories.id as category_id',
                            'clothing.id as id',
                            'clothing.trending as trending',
                            'clothing.created_at as created_at',
                            'clothing_details.modelo as model',
                            'clothing.discount as discount',
                            'clothing.main_image as main_image',
                            'clothing.name as name',
                            'clothing.casa as casa',
                            'clothing.description as description',
                            'clothing.price as price',
                            'clothing.mayor_price as mayor_price',
                            DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                            DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                            DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                        )
                        ->groupBy(
                            'clothing.id',
                            'categories.name',
                            'clothing.created_at',
                            'clothing.main_image',
                            'clothing_details.modelo',
                            'clothing.casa',
                            'categories.id',
                            'clothing.name',
                            'clothing.discount',
                            'clothing.trending',
                            'clothing.description',
                            'clothing.price',
                            'clothing.mayor_price',
                        )->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                        ->orderBy('clothing.casa', 'asc')
                        ->orderBy('clothing.name', 'asc')
                        ->take(15)
                        ->get();
                });
                foreach ($clothings as $clothing) {
                    $firstImage = ProductImage::where('clothing_id', $clothing->id)
                        ->orderBy('id')
                        ->first();
                    // Asignar la imagen al objeto $clothing
                    $clothing->image = $firstImage ? $firstImage->image : null;
                }

                return view('frontend.carsale.index', compact('clothings', 'car_count', 'comment_count', 'showModal', 'advert', 'blogs', 'social', 'clothings_offer', 'category', 'sellers', 'comments'));
                break;
            case (2):
            case (3):
                return view('frontend.website.index', compact('clothings', 'showModal', 'advert', 'blogs', 'social', 'clothings_offer', 'category', 'sellers', 'comments'));
                break;
            case (5):
                return view('frontend.barber.index', compact('clothings', 'showModal', 'advert', 'blogs', 'social', 'clothings_offer', 'category', 'sellers', 'comments'));
                break;
            case (6):
            case (7):
                $logos = Logos::all();
                $metricas = Metrica::all();
                return view('frontend.av.index', compact('clothings', 'metricas', 'logos', 'showModal', 'advert', 'blogs', 'social', 'clothings_offer', 'category', 'sellers', 'comments'));
                break;
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.index', compact('clothings', 'advert', 'showModal', 'blogs', 'social', 'clothings_offer', 'category', 'comments'));
                }
                return view('frontend.index', compact('clothings', 'advert', 'showModal', 'blogs', 'social', 'clothings_offer', 'category', 'comments'));
                break;
        }
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
        switch ($tenantinfo->kind_business) {
            case (1):
                $clothings = Cache::remember('clothings_cars', $this->expirationTime, function () {
                    return ClothingCategory::leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                        ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                        ->leftJoin('clothing_details', 'clothing.id', 'clothing_details.clothing_id')
                        ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
                        ->select(
                            'categories.name as category',
                            'categories.id as category_id',
                            'clothing.id as id',
                            'clothing.trending as trending',
                            'clothing.created_at as created_at',
                            'clothing_details.modelo as model',
                            'clothing.discount as discount',
                            'clothing.main_image as main_image',
                            'clothing.name as name',
                            'clothing.casa as casa',
                            'clothing.description as description',
                            'clothing.price as price',
                            'clothing.mayor_price as mayor_price',
                            DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                            DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                            DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                        )
                        ->groupBy(
                            'clothing.id',
                            'categories.name',
                            'clothing.created_at',
                            'clothing.main_image',
                            'clothing_details.modelo',
                            'clothing.casa',
                            'categories.id',
                            'clothing.name',
                            'clothing.discount',
                            'clothing.trending',
                            'clothing.description',
                            'clothing.price',
                            'clothing.mayor_price',
                        )->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                        ->orderBy('clothing.casa', 'asc')
                        ->orderBy('clothing.name', 'asc')
                        ->take(15)
                        ->get();
                });
                foreach ($clothings as $clothing) {
                    $firstImage = ProductImage::where('clothing_id', $clothing->id)
                        ->orderBy('id')
                        ->first();
                    // Asignar la imagen al objeto $clothing
                    $clothing->image = $firstImage ? $firstImage->image : null;
                }

                return view('frontend.carsale.category', compact('clothings', 'department_name', 'department_id'));
                break;
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.category', compact('category', 'department_name', 'department_id'));
                }
                return view('frontend.category', compact('category', 'department_name', 'department_id'));
                break;
        }
    }
    public function clothesByCategory($id, $department_id)
    {
        $category = Cache::remember('category_' . $id, $this->expirationTime, function () use ($id) {
            return Categories::find($id);
        });
        $categories = Cache::remember('categories', $this->expirationTime, function () {
            return Categories::where('departments.department', 'Default')
                ->where('categories.status', 0)
                ->join('departments', 'categories.department_id', 'departments.id')
                ->select(
                    'departments.id as department_id',
                    'categories.id as category_id',
                    'categories.image as image',
                    'categories.name as name',
                    'categories.meta_title as meta_title',
                    'categories.black_friday as black_friday'
                )->orderBy('categories.name', 'asc')
                ->take(7)
                ->get();
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
            return ClothingCategory::where('pivot_clothing_categories.category_id', $id)
                ->where('clothing.status', 1)
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
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
                    'clothing.can_buy as can_buy',
                    'clothing.discount as discount',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    'product_images.image as image', // Obtener la primera imagen del producto
                    DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy('clothing.id', 'clothing.can_buy', 'clothing.casa', 'clothing.mayor_price', 'categories.name', 'clothing.discount', 'clothing.name', 'clothing.description', 'clothing.price', 'product_images.image')
                ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                ->orderBy('clothing.casa', 'asc')
                ->orderBy('clothing.name', 'asc')
                ->simplePaginate(20);
        });

        $tags = Cache::remember('meta_tags_specific_category', $this->expirationTime, function () {
            return MetaTags::where('section', 'Categoría Específica')->get();
        });

        $tenantinfo = Cache::remember('tenant_info', $this->expirationTime, function () {
            return TenantInfo::first();
        });
        foreach ($clothings as $clothing) {
            // Obtener la primera imagen
            $firstImage = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->first();
            $clothing->image = $firstImage ? $firstImage->image : null;
            $clothing->all_images = ProductImage::where('clothing_id', $clothing->id)
                ->orderBy('id')
                ->pluck('image')
                ->toArray();

            // Obtener atributos
            $result = DB::table('stocks as s')->where('s.clothing_id', $clothing->id)
                ->join('attributes as a', 's.attr_id', '=', 'a.id')
                ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
                ->select(
                    'a.name as columna_atributo',
                    'a.id as attr_id',
                    DB::raw('GROUP_CONCAT(v.value ORDER BY s.order ASC SEPARATOR "/") as valores'),
                    DB::raw('GROUP_CONCAT(v.id ORDER BY s.order ASC SEPARATOR "/") as ids'),
                    DB::raw('GROUP_CONCAT(s.stock ORDER BY s.order ASC SEPARATOR "/") as stock'),
                )
                ->groupBy('a.name', 'a.id')
                ->orderBy('a.name', 'asc')
                ->get();
            $clothing->atributos = $result->toArray();
        }

        foreach ($tags as $tag) {
            SEOMeta::setTitle($category_name . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($category->meta_keywords);
            SEOMeta::setDescription($category->description);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        if (count($clothings) == 0 && ($tenantinfo->kind_business != 2 && $tenantinfo->kind_business != 3 && $tenantinfo->kind_business != 6)) {
            return redirect()->back()->with(['status' => 'No hay artículos en esta categoría', 'icon' => 'warning']);
        }
        switch ($tenantinfo->kind_business) {
            case (1):
                return view('frontend.carsale.clothes-category', compact('clothings', 'category_name', 'category_id', 'department_id', 'department_name', 'category'));
                break;
            case (2):
            case (3):
                return view('frontend.website.clothes-category', compact('clothings', 'category_name', 'category_id', 'department_id', 'department_name', 'category'));
                break;
            case (6):
                return view('frontend.av.clothes-category', compact('clothings', 'categories', 'category_name', 'category_id', 'department_id', 'department_name', 'category'));
                break;
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.clothes-category', compact('clothings', 'category_name', 'category_id', 'department_id', 'department_name'));
                }
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
                ->where('clothing.status', 1)
                ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
                ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
                ->join('stocks', 'clothing.id', 'stocks.clothing_id')
                ->select(
                    'categories.name as category',
                    'categories.id as category_id',
                    'clothing.id as id',
                    'clothing.trending as trending',
                    'clothing.created_at as created_at',
                    'clothing.manage_stock as manage_stock',
                    'clothing.discount as discount',
                    'clothing.name as name',
                    'clothing.casa as casa',
                    'clothing.description as description',
                    'clothing.price as price',
                    'clothing.mayor_price as mayor_price',
                    DB::raw('SUM(stocks.stock) as total_stock'),
                    DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'),
                    DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
                )
                ->groupBy(
                    'clothing.id',
                    'categories.name',
                    'clothing.casa',
                    'clothing.created_at',
                    'categories.id',
                    'clothing.name',
                    'clothing.discount',
                    'clothing.manage_stock',
                    'clothing.trending',
                    'clothing.description',
                    'clothing.price',
                    'clothing.mayor_price',
                )
                ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
                ->orderBy('clothing.casa', 'asc')
                ->orderBy('clothing.name', 'asc')
                ->take(15)
                ->get();
        });

        $clothes = ClothingCategory::where('clothing.id', $id)
            ->where('pivot_clothing_categories.category_id', $category_id)
            ->where('clothing.status', 1)
            ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
            ->join('departments', 'categories.department_id', 'departments.id')
            ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
            ->leftJoin('product_images', 'clothing.id', '=', 'product_images.clothing_id')
            ->select(
                'categories.name as category',
                'categories.id as category_id',
                'clothing.id as id',
                'clothing.trending as trending',
                'clothing.name as name',
                'clothing.meta_keywords as meta_keywords',
                'clothing.casa as casa',
                'clothing.code as code',
                'clothing.manage_stock as manage_stock',
                'clothing.can_buy as can_buy',
                'departments.id as department_id',
                'departments.department as department_name',
                'clothing.discount as discount',
                'clothing.horizontal_image as horizontal_image',
                'clothing.main_image as main_image',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.mayor_price as mayor_price',
                'product_images.image as image', // columna de imagen
                DB::raw('GROUP_CONCAT(product_images.image ORDER BY product_images.id ASC) AS images'),
                DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                DB::raw('GROUP_CONCAT(stocks.price) AS price_per_size'),
                DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
            )
            ->groupBy('clothing.id', 'categories.id', 'clothing.code', 'clothing.meta_keywords', 'clothing.main_image', 'clothing.manage_stock', 'clothing.horizontal_image', 'clothing.can_buy', 'clothing.casa', 'departments.id', 'departments.department', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
            ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
            ->orderBy('clothing.casa', 'asc')
            ->orderBy('clothing.name', 'asc')
            ->get();
        $result = DB::table('stocks as s')->where('s.clothing_id', $id)
            ->join('attributes as a', 's.attr_id', '=', 'a.id')
            ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
            ->select(
                'a.name as columna_atributo',
                'a.id as attr_id',
                DB::raw('GROUP_CONCAT(v.value ORDER BY s.order ASC SEPARATOR "/") as valores'),
                DB::raw('GROUP_CONCAT(v.id ORDER BY s.order ASC SEPARATOR "/") as ids'),
                DB::raw('GROUP_CONCAT(s.stock ORDER BY s.order ASC SEPARATOR "/") as stock'),
            )
            ->groupBy('a.name', 'a.id')
            ->orderBy('a.name', 'asc')
            ->get();
        $tags = MetaTags::where('section', 'Categoría Específica')->get();
        $tenantinfo = TenantInfo::first();

        foreach ($clothes as $item) {
            $meta_keywords_cloth = $item->meta_keywords;
            $name_cloth = $item->name;
            $description_cloth = $item->description;
        }

        foreach ($tags as $tag) {
            SEOMeta::setTitle($name_cloth . " - " . $tenantinfo->title);
            SEOMeta::setKeywords($meta_keywords_cloth);
            SEOMeta::setDescription($description_cloth);
            //Opengraph
            OpenGraph::addImage(URL::to($tag->url_image_og));
            OpenGraph::setTitle($tag->title);
            OpenGraph::setDescription($tag->meta_og_description);
        }

        $clothings_trending = ClothingCategory::where('clothing.trending', 1)
            ->where('clothing.id', '!=', $id)
            ->where('clothing.status', 1)
            ->where('categories.id', $category_id)
            ->leftJoin('pivot_clothing_categories', 'clothing.id', '=', 'pivot_clothing_categories.clothing_id')
            ->leftJoin('categories', 'pivot_clothing_categories.category_id', '=', 'categories.id')
            ->leftJoin('stocks', 'clothing.id', 'stocks.clothing_id')
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
                'clothing.main_image as main_image',
                'clothing.casa as casa',
                'clothing.description as description',
                'clothing.price as price',
                'clothing.mayor_price as mayor_price',
                DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
                DB::raw('SUM(CASE WHEN stocks.price != 0 THEN stocks.stock ELSE clothing.stock END) as total_stock'),
                DB::raw('GROUP_CONCAT(stocks.stock) AS stock_per_size'), // Obtener stock por talla
                DB::raw('GROUP_CONCAT(stocks.price) AS price_per_size'),
                DB::raw('(SELECT price FROM stocks WHERE clothing.id = stocks.clothing_id ORDER BY id ASC LIMIT 1) AS first_price')
            )
            ->groupBy('clothing.id', 'clothing.casa', 'clothing.main_image', 'clothing.mayor_price', 'clothing.discount', 'categories.name', 'categories.id', 'clothing.name', 'clothing.trending', 'clothing.description', 'clothing.price', 'product_images.image')
            ->orderByRaw('CASE WHEN clothing.casa IS NOT NULL AND clothing.casa != "" THEN 0 ELSE 1 END')
            ->orderBy('clothing.casa', 'asc')
            ->orderBy('clothing.name', 'asc')
            ->inRandomOrder()
            ->take(8)
            ->get();
        foreach ($clothings_trending as $cloth) {
            // Obtener la primera imagen
            $firstImage = ProductImage::where('clothing_id', $cloth->id)
                ->orderBy('id')
                ->first();
            $cloth->image = $firstImage ? $firstImage->image : null;
            $cloth->all_images = ProductImage::where('clothing_id', $cloth->id)
                ->orderBy('id')
                ->pluck('image')
                ->toArray();

            // Obtener atributos
            $result_trend = DB::table('stocks as s')->where('s.clothing_id', $cloth->id)
                ->join('attributes as a', 's.attr_id', '=', 'a.id')
                ->join('attribute_values as v', 's.value_attr', '=', 'v.id')
                ->select(
                    'a.name as columna_atributo',
                    'a.id as attr_id',
                    DB::raw('GROUP_CONCAT(v.value ORDER BY s.order ASC SEPARATOR "/") as valores'),
                    DB::raw('GROUP_CONCAT(v.id ORDER BY s.order ASC SEPARATOR "/") as ids'),
                    DB::raw('GROUP_CONCAT(s.stock ORDER BY s.order ASC SEPARATOR "/") as stock'),
                )
                ->groupBy('a.name', 'a.id')
                ->orderBy('a.name', 'asc')
                ->get();
            $cloth->atributos = $result_trend->toArray();
        }

        switch ($tenantinfo->kind_business) {
            case (1):
                $details = ClothingDetails::where('clothing_id', $id)->first();
                return view('frontend.carsale.detail-car', compact('clothes', 'details', 'result', 'category_id', 'clothings_trending'));
                break;
            case (2):
            case (3):
                return view('frontend.website.detail-clothing', compact('clothes', 'result', 'category_id', 'clothings_trending'));
                break;
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.detail-clothing', compact('clothes', 'result', 'category_id', 'clothings_trending'));
                }
                return view('frontend.detail-clothing', compact('clothes', 'result', 'category_id', 'clothings_trending'));
        }
    }
    public function departments()
    {
        $tenantinfo = TenantInfo::first();
        $departments = Cache::remember('departments', $this->expirationTime, function () {
            return Department::where('department', '!=', 'Default')
                ->orderBy('departments.department', 'asc')
                ->simplePaginate(8);
        });
        switch ($tenantinfo->kind_business) {
            case (6):
            case (7):
                return view('frontend.departments', compact('departments'));
                break;
            default:
                if ($tenantinfo->kind_of_features == 1) {
                    return view('frontend.design_ecommerce.departments', compact('departments'));
                }
                return view('frontend.departments', compact('departments'));
                break;
        }
    }
    public function getStock($cloth_id, $attr_id, $value_attr)
    {
        $stock = Stock::where('clothing_id', $cloth_id)
            ->where('attr_id', $attr_id)
            ->where('value_attr', $value_attr)
            ->first();
        return response()->json($stock);
    }
    public function compareIndex()
    {

        $tenantinfo = Cache::remember('tenant_info', $this->expirationTime, function () {
            return TenantInfo::first();
        });

        SEOMeta::setTitle("Comparar Vehículos" . " - " . $tenantinfo->title);
        SEOMeta::setKeywords("comparar vehiculos");
        SEOMeta::setDescription("Compara vehículos en " . $tenantinfo->title);

        $clothings = ClothingCategory::where('status', 1)
            ->leftJoin('product_images', function ($join) {
                $join->on('clothing.id', '=', 'product_images.clothing_id')->whereRaw('product_images.id = (
                                SELECT MIN(id) FROM product_images
                                WHERE product_images.clothing_id = clothing.id
                            )');
            })
            ->select(
                'clothing.id as id',
                'clothing.name as name',
                'clothing.code as code',
                DB::raw('IFNULL(product_images.image, "") as image'), // Obtener la primera imagen del producto
            )
            ->get();
        return view('frontend.carsale.compare', compact('clothings'));
    }
    public function aboutUs()
    {
        $tenantinfo = Cache::remember('tenant_info', $this->expirationTime, function () {
            return TenantInfo::first();
        });
        switch ($tenantinfo->kind_business) {

            case (6):
                return view('frontend.av.about_us');
                break;
            default:
                return view('frontend.about_us');
        }
    }
}
