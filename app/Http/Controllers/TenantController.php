<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Categories;
use App\Models\MetaTags;
use App\Models\Seller;
use App\Models\SocialNetwork;
use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\Testimonial;
use App\Models\User;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Facades\Tenancy; // O el método propio de tu paquete de tenancy

class TenantController extends Controller
{
    protected $expirationTime;

    public function __construct()
    {
        // Define el tiempo de expiración en minutos
        $this->expirationTime = 60;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = Tenant::where('id', '!=', 'main')->get();

        $tenants = $tenants->map(function ($tenant) {
            $tenant_info = $this->getData($tenant->id);
            $tenant->license = $tenant_info->license;
            $tenant->manage_size = $tenant_info->manage_size;
            $tenant->manage_department = $tenant_info->manage_department;
            return $tenant;
        });
        return view('admin.tenant.index', compact('tenants'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage($tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $users = User::all();
        tenancy()->end();
        return view('admin.tenant.manage', compact('users', 'tenant'));
    }
    public function isAdmin($tenant, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->role_as == "1") {
                User::where('id', $request->id)->update(['role_as' => 1]);
            } else {
                User::where('id', $request->id)->update(['role_as' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado (Es Admin) para este usuario', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function frontend()
    {
        $tenantinfo = TenantInfo::first();

        $social = Cache::remember('social_networks', $this->expirationTime, function () {
            return SocialNetwork::get();
        });
        $tags = Cache::remember('meta_tags_inicio', $this->expirationTime, function () {
            return MetaTags::where('section', 'Inicio')->get();
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
                ->take(7)
                ->get();
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
        $blogs = Blog::inRandomOrder()->orderBy('title', 'asc')
            ->take(4)->get();

        $comments = Testimonial::where('approve', 1)->inRandomOrder()->orderBy('name', 'asc')
            ->get();
        return view('frontend.central.index', compact('blogs', 'social', 'category', 'comments'));
    }
    public function getData($tenant)
    {
        $tenants = Tenant::where('id', $tenant)->first();
        tenancy()->initialize($tenants);
        $tenant_info = TenantInfo::first();
        tenancy()->end();
        return $tenant_info;
    }

    public function isLicense($tenant, Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $tenants = Tenant::where('id', $tenant)->first();
            tenancy()->initialize($tenants);
            if ($request->license == "1") {
                TenantInfo::where('tenant', $tenant)->update(['license' => 1]);
            } else {
                TenantInfo::where('tenant', $tenant)->update(['license' => 0]);
            }

            DB::commit();
            tenancy()->end();
            return redirect()->back()->with(['status' => 'Se cambio el estado de la licencia para este inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
        }
    }
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $datos = [
                "tenancy_db_name" => "safewors_" . $request->tenant,
                "tenancy_db_password" => "UYHkOYFXReJ4aDcJ",
                "tenancy_db_username" => "safewors"
            ];
            $new_tenant = new Tenant();
            $new_tenant->id = $request->tenant;
            $new_tenant->data = $datos;
            $new_tenant->save();
            $id = $new_tenant->id;
            $new_domain = new Domain();
            $new_domain->domain = $id . ".safeworsolutions.com";
            $new_domain->tenant_id = $id;
            $new_domain->save();

            Artisan::call('tenants:migrate');
            $tenants = Tenant::where('id', $id)->first();
            tenancy()->initialize($tenants);
            Artisan::call('db:seed');
            tenancy()->end();
            DB::commit();
            return redirect()->back()->with(['status' => 'Se agregó un nuevo inquilino', 'icon' => 'success']);
        } catch (\Exception $th) {
            dd($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with(['status' => $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function generateSitemap()
    {
        try {
            Artisan::call('tenants:sitemap:generate ');
            return redirect()->back()->with(['status' => 'Se crearon los sitemaps con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            return redirect()->back()->with(['status' => 'Hubo un error al crear los sitemaps. Error: ' . $th->getMessage(), 'icon' => 'error']);
        }
    }
    public function generateMigrate()
    {
        try {
            Artisan::call('tenants:migrate ');
            return redirect()->back()->with(['status' => 'Se ejecutaron las migraciones con éxito', 'icon' => 'success']);
        } catch (\Exception $th) {
            return redirect()->back()->with(['status' => 'Hubo un error al ejecutar las migraciones', 'icon' => 'error']);
        }
    }
    public function switchTenant($identifier)
    {
        $tenant = Tenant::where('id', $identifier)->firstOrFail();
        try {            
            Tenancy::initialize($tenant);
            session(['current_tenant' => $tenant->id]);
            return redirect()->back()->with('success', "Se ha cambiado al tenant: {$tenant->identifier}");
        } catch (\Exception $th) {
            return redirect()->back()->with('success', "Fallo al cambiar de tenant: {$tenant->identifier}. " . $th->getMessage());
        }
    }
}
