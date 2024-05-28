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
        $tenants = Tenant::where('id','!=','main')->get();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $sitemap = Sitemap::create();

            // Define the base URL for the tenant
            $tenantBaseUrl = "https://{$tenant->id}.safeworsolutions.com";

            // Add static URLs
            // Add static URLs
            $sitemap->add(Url::create("{$tenantBaseUrl}/"));
            $sitemap->add(Url::create("{$tenantBaseUrl}/category"));
            $sitemap->add(Url::create("{$tenantBaseUrl}/blog/index"));
            $sitemap->add(Url::create("{$tenantBaseUrl}/departments/index"));
            $sitemap->add(Url::create("{$tenantBaseUrl}/view-cart"));
            $sitemap->add(Url::create("{$tenantBaseUrl}/checkout"));

            // Add dynamic URLs (example for blogs)
            Blog::all()->each(function ($blog) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/blog/{$blog->id}/{$blog->name_url}"));
            });

            // Add dynamic URLs for other models
            Categories::all()->each(function ($category) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/category/{$category->id}"));
            });

            Department::all()->each(function ($department) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/clothes-category/{$department->id}/{$department->category_id}"));
            });

            ClothingCategory::all()->each(function ($clothing) use ($sitemap, $tenantBaseUrl) {
                $sitemap->add(Url::create("{$tenantBaseUrl}/detail-clothing/{$clothing->id}/{$clothing->category_id}"));
            });

            // Handle dynamic file routes (assuming files are stored in public storage)
            $files = Storage::allFiles('public');
            foreach ($files as $file) {
                $relativePath = str_replace('public/', '', $file);
                $sitemap->add(Url::create("{$tenantBaseUrl}/file/{$relativePath}"));
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
