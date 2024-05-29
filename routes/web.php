<?php
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [TenantController::class, 'frontend']);
Auth::routes();

Route::group(['middleware' => 'isAdmin'], function () {    
    //Rutas para inquilinos y pagos de inquilinos
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::post('tenant/store/', [TenantController::class, 'store']);
    Route::get('manage/tenant/{tenant}', [TenantController::class, 'manage']);
    Route::get('tenants/payments', [TenantPaymentController::class, 'index']);
    Route::get('tenant/manage-pay/{id}', [TenantPaymentController::class, 'indexPayment']);
    Route::post('tenant-payment/store/', [TenantPaymentController::class, 'store']); 
    Route::delete('/delete/pay/{id}', [TenantPaymentController::class, 'destroy']);
    Route::post('user/admin/{id}', [TenantController::class, 'isAdmin']);     
    Route::post('license/{id}', [TenantController::class, 'isLicense']);   
    Route::post('manage/size/{id}', [TenantController::class, 'manageSize']);   
    Route::post('manage/department/{id}', [TenantController::class, 'manageDepartment']);  
    Route::post('generate/sitemap/', [TenantController::class, 'generateSitemap']);   
});
