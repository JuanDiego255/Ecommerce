<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\ClothingCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\MetaTagsController;
use App\Http\Controllers\PersonalUserController;
use App\Http\Controllers\SocialNetworkController;
use App\Http\Controllers\TenantCarouselController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantInfoController;
use App\Http\Controllers\TenantPaymentController;
use App\Http\Controllers\TenantSocialNetworkController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\MetaDeletionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
Route::get('index/try', [FrontendController::class, 'index']);
Route::get('/', [TenantController::class, 'frontend']);
Route::get('category', [FrontendController::class, 'category']);
Route::get('/blog/index',  [BlogController::class, 'index']);
Route::get('blog/{blog}/{name_url}', [BlogController::class, 'showArticles']);
Route::post('send-email/blog', [BlogController::class, 'sendEmail']);
Route::get('departments/index', [FrontendController::class, 'departments']);
Route::get('category/{id}', [FrontendController::class, 'category']);
Route::get('clothes-category/{id}/{department_id}', [FrontendController::class, 'clothesByCategory']);
Route::get('detail-clothing/{id}/{cat_id}', [FrontendController::class, 'DetailClothingById']);
Route::post('/add-to-cart', [CartController::class, 'store']);
Route::post('/edit-quantity', [CartController::class, 'updateQuantity']);
Route::get('/view-cart', [CartController::class, 'viewCart']);
Route::get('/get-cart-items', [CartController::class, 'getCart']);
Route::delete('/delete-item-cart/{id}', [CartController::class, 'delete']);
Route::post('/payment', [CheckOutController::class, 'payment']);
Route::get('/paypal/process/{orderId}', [CheckOutController::class, 'process']);
Route::post('/comments/store/', [TestimonialController::class, 'store']);
Route::get('/get-stock/{cloth_id}/{attr_id}/{value_attr}', [FrontendController::class, 'getStock']);
Route::get('/proyectos', [TenantController::class, 'projects'])->name('central.projects');

Route::get('/privacy-policy', [TenantController::class, 'privacyPolicy'])->name('privacy.policy');

// ── Callbacks requeridos por Meta para aprobación LIVE ───────────────────────
// POST: Meta los llama con un signed_request (exentos de CSRF en VerifyCsrfToken)
Route::post('/facebook/data-deletion',   [MetaDeletionController::class, 'deletionCallback'])->name('meta.data.deletion');
Route::post('/facebook/deauthorize',     [MetaDeletionController::class, 'deauthorizeCallback'])->name('meta.deauthorize');
// GET: Página pública de estado de eliminación (para el usuario)
Route::get('/facebook/deletion-status/{code}', [MetaDeletionController::class, 'deletionStatus'])->name('meta.deletion.status');

Auth::routes();

Route::group(['middleware' => 'isAdmin'], function () {
    //Routes for Categories
    Route::get('/dashboard', [FrontendController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/add-category/{id}', [CategoryController::class, 'add']);
    Route::get('/edit-category/{id}', [CategoryController::class, 'edit']);
    Route::post('/insert-category', [CategoryController::class, 'store']);
    Route::post('/process-image', [CategoryController::class, 'processImage']);
    Route::put('/update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('/delete-category/{id}', [CategoryController::class, 'delete']);
    //Routes for ArticleCategory
    Route::get('/add-item/{id}', [ClothingCategoryController::class, 'indexById']);
    Route::get('/new-item/{id}', [ClothingCategoryController::class, 'add']);
    Route::post('/insert-clothing', [ClothingCategoryController::class, 'store']);
    Route::post('/import/products/{id}', [ClothingCategoryController::class, 'importProducts']);
    Route::get('/edit-clothing/{id}/{cat_id}', [ClothingCategoryController::class, 'edit']);
    Route::put('/update-clothing/{id}', [ClothingCategoryController::class, 'update']);
    Route::delete('/delete-clothing/{id}', [ClothingCategoryController::class, 'delete']);
    Route::post('status/{id}', [ClothingCategoryController::class, 'isStatus']);
    //Routes for Buys
    Route::get('/buys-admin', [BuyController::class, 'indexAdmin']);
    Route::post('/size-by-cloth', [BuyController::class, 'sizeByCloth']);
    Route::get('/total-buys', [BuyController::class, 'indexTotalBuys']);
    Route::get('/new-buy', [BuyController::class, 'indexBuy']);
    Route::get('/buy/details/admin/{id}', [BuyController::class, 'buyDetailsAdmin']);
    Route::put('/approve/{id}/{approved}', [BuyController::class, 'approve']);
    Route::put('/delivery/{id}/{delivery}', [BuyController::class, 'delivery']);
    Route::delete('delete-buy/{id}', [BuyController::class, 'destroy']);
    //Rutas Metatags
    Route::get('/meta-tags/indexadmin', [MetaTagsController::class, 'index']);
    Route::post('/metatag', [MetaTagsController::class, 'store']);
    Route::get('/metatag/agregar', [MetaTagsController::class, 'agregar']);
    Route::get('metatag/edit/{id}', [MetaTagsController::class, 'edit']);
    Route::put('metatags/{id}', [MetaTagsController::class, 'update']);
    Route::delete('delete-metatag/{id}', [MetaTagsController::class, 'destroy']);
    //Rutas para redes sociales seccion
    Route::post('social/store', [SocialNetworkController::class, 'store']);
    Route::put('/social/update/{id}', [SocialNetworkController::class, 'update']);
    Route::get('/social-network', [SocialNetworkController::class, 'index']);
    Route::delete('/delete/social/{id}', [SocialNetworkController::class, 'destroy']);
    //Rutas para información de la empresa
    Route::post('tenant-info/store', [TenantInfoController::class, 'store']);
    Route::put('/tenant-info/update/{id}', [TenantInfoController::class, 'update']);
    Route::put('/tenant-components/save/', [TenantInfoController::class, 'updateComp']);
    Route::put('/tenant-components/color-save/', [TenantInfoController::class, 'updateColor']);
    Route::put('/tenant-components/icon-save/', [TenantInfoController::class, 'saveIcons']);
    Route::get('/tenant-info', [TenantInfoController::class, 'index']);
    Route::get('/tenant-components', [TenantInfoController::class, 'indexComponents']);
    Route::delete('/delete/tenant-info/{id}', [TenantInfoController::class, 'destroy']);
    //Rutas para información de las redes sociales de la empresa
    Route::post('tenant-social/store', [TenantSocialNetworkController::class, 'store']);
    Route::put('/tenant-social/update/{id}', [TenantSocialNetworkController::class, 'update']);
    Route::delete('/delete/tenant-social/{id}', [TenantSocialNetworkController::class, 'destroy']);
    //Rutas para carousel de la empresa
    Route::post('tenant-carousel/store', [TenantCarouselController::class, 'store']);
    Route::put('/tenant-carousel/update/{id}', [TenantCarouselController::class, 'update']);
    Route::delete('/delete/tenant-carousel/{id}', [TenantCarouselController::class, 'destroy']);
    //Rutas para usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::post('user/mayor/{id}', [UserController::class, 'mayor']);
    //Rutas para departamentos
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('department/store', [DepartmentController::class, 'store']);
    Route::put('/department/update/{id}', [DepartmentController::class, 'update']);
    Route::delete('/delete/department/{id}', [DepartmentController::class, 'delete']);
    //Rutas para el blog           
    Route::get('/blog/indexadmin',  [BlogController::class, 'indexadmin']);
    Route::post('/blog', [BlogController::class, 'store']);
    Route::post('/blog/more-info/{id}', [BlogController::class, 'storeMoreInfo']);
    Route::get('/blog/agregar', [BlogController::class, 'agregar']);
    Route::get('blog-edit/{blog}/edit', [BlogController::class, 'edit']);
    Route::get('blog/{blog}/{id}/edit-info', [BlogController::class, 'editArticle']);
    Route::get('blog-show/{id}/show', [BlogController::class, 'indexArticles']);
    Route::get('blog-cards/{id}/view-cards', [BlogController::class, 'indexCards']);
    Route::put('blog/{blog}', [BlogController::class, 'update']);
    Route::put('tag/{id}/{blog_id}', [BlogController::class, 'updateArticle']);
    Route::delete('blog/{id}', [BlogController::class, 'destroy']);
    Route::delete('delete-article/{id}', [BlogController::class, 'destroyArticle']);
    Route::get('blog-add/{id}/agregar-info', [BlogController::class, 'agregarInfo']);
    Route::get('blog-add/{id}/add-card', [BlogController::class, 'addCard']);
    Route::get('blog/{blog}/{id}/edit-card', [BlogController::class, 'editCard']);
    Route::post('/blog/add-card/{id}', [BlogController::class, 'storeCard']);
    Route::put('card/{id}/{blog_id}', [BlogController::class, 'updateCard']);
    Route::delete('delete-card/{id}', [BlogController::class, 'destroyCard']);
    Route::post('upload/', [BlogController::class, 'upload'])->name('upload');
    //Rutas para información profesional
    Route::get('/user-info/', [PersonalUserController::class, 'indexAdmin']);
    Route::get('/new-user-info/', [PersonalUserController::class, 'add']);
    Route::post('/insert-user-info', [PersonalUserController::class, 'store']);
    Route::get('/edit-user-info/{id}', [PersonalUserController::class, 'edit']);
    Route::put('/update-user-info/{id}', [PersonalUserController::class, 'update']);
    Route::delete('/delete-user-info/{id}', [PersonalUserController::class, 'destroy']);
    //Rutas para testimonios de los clientes
    Route::get('/comments/', [TestimonialController::class, 'indexAdmin']);
    Route::put('comments/{id}', [TestimonialController::class, 'update']);
    Route::post('approve-comment/{id}', [TestimonialController::class, 'updateStatus']);
    Route::delete('delete-comments/{id}', [TestimonialController::class, 'destroy']);
    Route::get('comments/{id}/edit', [TestimonialController::class, 'edit']);
    Route::get('comments/add', [TestimonialController::class, 'add']);
    //Rutas para atributos de productos
    Route::get('/attributes/', [AttributeController::class, 'indexAdmin']);
    Route::put('attribute/{id}', [AttributeController::class, 'update']);
    Route::delete('delete-attribute/{id}', [AttributeController::class, 'destroy']);
    Route::get('attribute/{id}/edit', [AttributeController::class, 'edit']);
    Route::get('attribute/add', [AttributeController::class, 'add']);
    Route::post('attribute/store', [AttributeController::class, 'store']);
    Route::post('main-attribute/{id}', [AttributeController::class, 'mainAttribute']);
    //Rutas para valores de los atributos
    Route::get('attribute-values/{id}', [AttributeController::class, 'values']);
    Route::get('get-values/{id}', [AttributeController::class, 'getValues']);
    Route::get('get-attr_id/{id}', [AttributeController::class, 'getAttrId']);
    Route::put('value/{id}/{attr_id}', [AttributeController::class, 'updateValue']);
    Route::delete('delete-value/{id}', [AttributeController::class, 'destroyValue']);
    Route::post('/value/store/{id}', [AttributeController::class, 'storeValue']);
    Route::get('value/{attr_id}/{id}/edit', [AttributeController::class, 'editValue']);
    //Rutas para inquilinos y pagos de inquilinos
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::post('tenant/store/', [TenantController::class, 'store']);
    Route::get('manage/tenant/{tenant}', [TenantController::class, 'manage']);
    Route::post('user/admin/{id}', [TenantController::class, 'isAdmin']);
    Route::post('license/{id}', [TenantController::class, 'isLicense']);
    Route::post('manage/size/{id}', [TenantController::class, 'manageSize']);
    Route::post('manage/department/{id}', [TenantController::class, 'manageDepartment']);
    Route::post('generate/sitemap/', [TenantController::class, 'generateSitemap']);
    Route::post('generate/migrate/', [TenantController::class, 'generateMigrate']);
    //Rutas para pagos de inquilinos
    Route::get('tenants/payments', [TenantPaymentController::class, 'index']);
    Route::get('tenant/manage-pay/{id}', [TenantPaymentController::class, 'indexPayment']);
    Route::post('tenant-payment/store/', [TenantPaymentController::class, 'store']);
    Route::delete('/delete/pay/{id}', [TenantPaymentController::class, 'destroy']);
    //Rutas para gastos de safewor
    Route::get('/bills/', [BillController::class, 'index']);
    Route::post('bill/store/', [BillController::class, 'store']);
    Route::delete('/delete/bill/{id}', [BillController::class, 'destroy']);
});

//images Tenant
Route::get('/file/{path}', function ($path) {
    $path = Storage::path($path);
    $path = str_replace('app\\', 'app\\public\\', $path);

    return response()->file($path);
})->where('path', '.*')->name('file');
