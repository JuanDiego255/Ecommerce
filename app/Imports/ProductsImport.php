<?php

namespace App\Imports;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ClothingCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Requests\ImportCategoryRequest;
use App\Http\Requests\ImportDepartmentRequest;
use App\Http\Requests\ImportProductRequest;
use App\Models\Categories;
use App\Models\ClothingCategory;
use App\Models\Department;
use App\Models\TenantInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{

    public function startRow(): int
    {
        return 2;
    }
    protected $department_id;

    public function __construct($department_id)
    {
        $this->department_id = $department_id;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key != 0) {
                $department = Department::where('department',trim($row[9]))->first();
                if (!$department) {
                    $requestDataDepartment = new ImportDepartmentRequest([
                        'department' => trim($row[9])
                    ]);
                    $departmentController = new DepartmentController();
                    $departmentController->store($requestDataDepartment);
                }
                $department = Department::where('department',trim($row[9]))->first();
                $department_id = isset($department->id) && $department->id != null ? $department->id : $this->department_id;

                $category = Categories::where('name', trim($row[0]))
                ->where('department_id',$department_id)
                ->first();

                if (!$category) {                    
                    $requestDataCategory = new ImportCategoryRequest([
                        'name' => trim($row[0]),
                        'slug' => trim($row[0]),
                        'description' => trim($row[0]),
                        'department_id' => $department_id
                    ]);
                    $categoryController = new CategoryController();
                    $categoryController->store($requestDataCategory);
                }

                $category = Categories::where('name', trim($row[0]))
                ->where('department_id',$department_id)
                ->first();

                if ($category) {
                    $clothing_check = ClothingCategory::where('code', trim($row[1]))->first();
                    if (!$clothing_check) {
                        $requestData = new ImportProductRequest([
                            'category_id' => $category->id,
                            'code' => trim($row[1]),
                            'name' => trim($row[2]),
                            'description' => trim($row[3]),
                            'stock' => trim($row[4]),
                            'price' => trim($row[5]),
                            'mayor_price' => trim($row[6]),
                            'trending' => trim($row[7]),
                            'discount' => trim($row[8])
                        ]);

                        $clothingController = new ClothingCategoryController();
                        $tenantinfo = TenantInfo::first();
                        DB::beginTransaction();
                        $clothingController->createClothing($requestData,$tenantinfo);
                        DB::commit();
                    }
                }
            }
        }
    }
}
