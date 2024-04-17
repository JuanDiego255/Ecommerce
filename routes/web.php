<?php
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\TenantController;
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
    //Rutas para inquilinos   
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('manage/tenant/{tenant}', [TenantController::class, 'manage']);
    Route::post('user/admin/{id}', [TenantController::class, 'isAdmin']); 
    Route::post('license/{id}', [TenantController::class, 'isLicense']);   
});
