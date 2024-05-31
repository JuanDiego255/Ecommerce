<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Tenant;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\Department;
use App\Models\ClothingCategory;
use Illuminate\Support\Facades\Storage;

class GenerateTenantSitemaps extends Command
{
    protected $signature = 'tenants:sitemap:generate';
    protected $description = 'Generate sitemaps for all tenants';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tenants = Tenant::get();

        foreach ($tenants as $tenant) {
            if($tenant->id != "main"){
                tenancy()->initialize($tenant);
            }else{
                tenancy()->end();
            }            

            $sitemap = Sitemap::create();

            // Define the base URL for the tenant
            $tenantBaseUrl = "https://{$tenant->id}.safeworsolutions.com";
            // Add static URLs with higher priority
            $sitemap->add(Url::create("{$tenantBaseUrl}/")->setPriority(1.0)); // Highest priority
            $sitemap->add(Url::create("{$tenantBaseUrl}/category")->setPriority(0.5));
            $sitemap->add(Url::create("{$tenantBaseUrl}/blog/index")->setPriority(0.2));
            $sitemap->add(Url::create("{$tenantBaseUrl}/departments/index")->setPriority(0.7));
            $sitemap->add(Url::create("{$tenantBaseUrl}/view-cart")->setPriority(0.8));
            $sitemap->add(Url::create("{$tenantBaseUrl}/checkout")->setPriority(0.8));

            // Add dynamic URLs with higher priority
            Blog::all()->each(function ($blog) use ($sitemap, $tenantBaseUrl) {                
                $sitemap->add(Url::create("{$tenantBaseUrl}/blog/{$blog->id}/{$blog->name_url}")->setPriority(0.2));
            });

            Categories::all()->each(function ($category) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/category/{$category->id}")->setPriority(0.5));
            });

            Department::all()->each(function ($department) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/clothes-category/{$department->id}/{$department->category_id}")->setPriority(0.4));
            });

            ClothingCategory::all()->each(function ($clothing) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/detail-clothing/{$clothing->id}/{$clothing->category_id}")->setPriority(0.4));
            });

            // Handle dynamic file routes (assuming files are stored in public storage) with lower priority
            $files = Storage::allFiles('public');
            foreach ($files as $file) {
                $relativePath = str_replace('public/', '', $file);
                $sitemap->add(Url::create("{$tenantBaseUrl}/file/{$relativePath}")->setPriority(0.3));
            }


            // Determine the path to store the sitemap
            $path = public_path("sitemaps/{$tenant->id}");

            // Create the directory if it doesn't exist
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Store the sitemap to a file
            $sitemap->writeToFile("{$path}/sitemap.xml");

            $this->info("Sitemap generated for tenant {$tenant->id} at {$tenantBaseUrl}");
        }
    }
}
