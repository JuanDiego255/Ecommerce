<?php

namespace App\Imports;

use App\Models\AttributeValue;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\PivotClothingCategory;
use App\Models\ProductImage;
use App\Models\Stock;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductsCsvImport
{
    public array $results = [];

    private ?object $stockAttr = null;

    public function __construct()
    {
        $this->stockAttr = AttributeValue::where('attributes.name', 'Stock')
            ->where('attribute_values.value', 'Automático')
            ->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')
            ->select('attributes.id as attr_id', 'attribute_values.id as value_id')
            ->first();
    }

    /**
     * Process a parsed CSV rows array (skip header row index 0).
     * Returns summary: ['created' => int, 'skipped' => int, 'errors' => string[]]
     */
    public function import(array $rows): array
    {
        $created  = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // skip header

            $row = array_map('trim', $row);

            // Required columns: 0=categoria, 1=codigo, 2=nombre, 3=precio, 4=stock
            if (empty($row[1]) || empty($row[2]) || empty($row[3])) {
                $errors[] = "Fila " . ($i + 1) . ": código, nombre o precio vacío — omitida.";
                $skipped++;
                continue;
            }

            // Skip duplicate codes
            if (ClothingCategory::where('code', $row[1])->exists()) {
                $skipped++;
                continue;
            }

            DB::beginTransaction();
            try {
                $categoryName   = $row[0]  ?? 'General';
                $code           = $row[1];
                $name           = $row[2];
                $price          = (float) $row[3];
                $mayorPrice     = isset($row[4]) && $row[4] !== '' ? (float) $row[4] : $price;
                $stock          = isset($row[5]) && $row[5] !== '' ? (int)   $row[5] : 0;
                $description    = $row[6]  ?? '';
                $keywords       = $row[7]  ?? '';
                $trending       = isset($row[8]) && in_array(strtolower($row[8]), ['1', 'si', 'sí', 'yes']) ? 1 : 0;
                $discount       = isset($row[9])  && $row[9]  !== '' ? (float) $row[9] : 0;
                $departmentName = $row[10] ?? 'Default';
                $imageUrls      = array_filter([
                    $row[11] ?? '', $row[12] ?? '', $row[13] ?? '', $row[14] ?? '',
                ]);

                // Resolve or create department
                $department = Department::firstOrCreate(['department' => $departmentName]);

                // Resolve or create category
                $category = Categories::where('name', $categoryName)
                    ->where('department_id', $department->id)
                    ->first();
                if (!$category) {
                    $category = new Categories();
                    $category->name          = $categoryName;
                    $category->slug          = \Str::slug($categoryName);
                    $category->description   = $categoryName;
                    $category->department_id = $department->id;
                    $category->save();
                }

                // Create product
                $tenantinfo = TenantInfo::first();
                $clothing               = new ClothingCategory();
                $clothing->name         = $name;
                $clothing->code         = $code;
                $clothing->price        = $price;
                $clothing->mayor_price  = $mayorPrice;
                $clothing->stock        = $stock;
                $clothing->description  = $description;
                $clothing->meta_keywords = $keywords;
                $clothing->trending     = $trending;
                $clothing->discount     = $discount;
                $clothing->status       = 1;
                $clothing->manage_stock = 1;
                $clothing->save();

                // Link to category
                PivotClothingCategory::create([
                    'clothing_id' => $clothing->id,
                    'category_id' => $category->id,
                ]);

                // Create stock record
                if ($this->stockAttr) {
                    Stock::create([
                        'clothing_id' => $clothing->id,
                        'attr_id'     => $this->stockAttr->attr_id,
                        'value_attr'  => $this->stockAttr->value_id,
                        'stock'       => $stock,
                        'price'       => $price,
                        'order'       => 1,
                    ]);
                }

                DB::commit();

                // Download images after commit (skip failures silently)
                foreach ($imageUrls as $url) {
                    if (!filter_var($url, FILTER_VALIDATE_URL)) continue;
                    try {
                        $response = Http::timeout(8)->get($url);
                        if ($response->successful()) {
                            $ext      = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                            $ext      = strtolower(preg_replace('/[^a-z0-9]/i', '', $ext)) ?: 'jpg';
                            $filename = 'uploads/' . uniqid('img_') . '.' . $ext;
                            Storage::disk('public')->put($filename, $response->body());
                            ProductImage::create([
                                'clothing_id' => $clothing->id,
                                'image'       => $filename,
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Image download failed — product already saved, skip silently
                    }
                }

                $created++;
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = "Fila " . ($i + 1) . " (" . ($row[2] ?? '?') . "): " . $e->getMessage();
                $skipped++;
            }
        }

        return compact('created', 'skipped', 'errors');
    }
}
