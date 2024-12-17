<?php

declare(strict_types=1);

use App\Http\Controllers\AddressUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdvertController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\ClothingCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\MedicineResultController;
use App\Http\Controllers\MetaTagsController;
use App\Http\Controllers\PersonalUserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SocialNetworkController;
use App\Http\Controllers\TenantCarouselController;
use App\Http\Controllers\TenantInfoController;
use App\Http\Controllers\TenantSocialNetworkController;
use App\Http\Controllers\TestimonialController;
use App\Models\Attribute;
use App\Models\MedicineResult;
use App\Models\PersonalUser;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [FrontendController::class, 'index']);
    Route::get('compare/vehicles', [FrontendController::class, 'compareIndex']);
    Route::get('/get-cart-details/{code}', [ClothingCategoryController::class, 'getCartDetail']);
    Route::get('/comment/{show}', [FrontendController::class, 'index']);
    Route::group(['middleware' => 'isLicense'], function () {

        //Google authentication ----->
        Route::get('/google-auth/redirect', [AuthController::class, 'redirectGoogle']);
        Route::get('/google-auth/callback', [AuthController::class, 'callbackGoogle']);
        //Google authentication <-----

        //Facebook authentication ------>
        Route::get('/facebook-auth/redirect', [AuthController::class, 'redirectFacebook']);
        Route::get('/facebook-auth/callback', [AuthController::class, 'callbackFacebook']);
        //Facebook authentication <------


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
        Route::post('/payment/apartado/{id}', [CheckOutController::class, 'paymentApartado']);
        Route::get('/paypal/process/{orderId}', [CheckOutController::class, 'process']);
        Route::post('/comments/store/', [TestimonialController::class, 'store']);
        Route::get('/get-stock/{cloth_id}/{attr_id}/{value_attr}', [FrontendController::class, 'getStock']);
        Route::get('/gift-code/{id}', [GiftCardController::class, 'applyCode']);
        Route::post('gift/store', [GiftCardController::class, 'store']);
        Route::get('/get/products/select/', [ClothingCategoryController::class, 'getProductsToSelect']);
        Auth::routes();

        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::group(['auth'], function () {

            Route::get('checkout', [CheckOutController::class, 'index']);
            Route::get('/buys', [BuyController::class, 'index']);
            //::post('cancel/buy/{id}/{status}', [BuyController::class, 'cancelBuy']);
            //Route::post('cancel/buy-item/{id}/{status}', [BuyController::class, 'cancelBuyItem']);
            Route::get('/buy/details/{id}', [BuyController::class, 'buyDetails']);
            Route::post('address/store', [AddressUserController::class, 'store']);
            Route::post('address/status/{id}', [AddressUserController::class, 'status']);
            Route::put('/address/update/{id}', [AddressUserController::class, 'update']);
            Route::get('/address', [AddressUserController::class, 'index']);
            Route::delete('/delete/address/{id}', [AddressUserController::class, 'destroy']);
        });

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
            Route::get('/get-total-categories/{id}', [ClothingCategoryController::class, 'getTotalCategories']);
            //Routes for Buys
            Route::get('/buys-admin', [BuyController::class, 'indexAdmin']);
            Route::post('/size-by-cloth', [BuyController::class, 'sizeByCloth']);        
            Route::post('/save/guide-number/{id}', [BuyController::class, 'updateGuideNumber']);            
            Route::get('/total-buys', [BuyController::class, 'indexTotalBuys']);
            Route::get('/new-buy', [BuyController::class, 'indexBuy']);
            Route::get('/buy/details/admin/{id}', [BuyController::class, 'buyDetailsAdmin']);
            Route::put('/approve/{id}/{ready}', [BuyController::class, 'approve']);
            Route::put('/ready/{id}/{approved}', [BuyController::class, 'readyToGive']);
            Route::put('/delivery/{id}/{delivery}', [BuyController::class, 'delivery']);
            Route::delete('delete-buy/{id}', [BuyController::class, 'destroy']);
            Route::get('/get/buys/select/{id}', [BuyController::class, 'getBuys']);
            //Rutas para anuncios
            Route::post('advert/store', [AdvertController::class, 'store']);
            Route::get('/adverts', [AdvertController::class, 'index']);
            Route::delete('/delete/advert/{id}', [AdvertController::class, 'destroy']);
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
            Route::put('user/{id}', [UserController::class, 'update']);            
            Route::delete('delete-user/{id}', [UserController::class, 'destroy']);            
            Route::get('user/{id}/edit', [UserController::class, 'edit']);
            //Rutas para departamentos
            Route::get('/departments', [DepartmentController::class, 'index']);
            Route::post('department/store', [DepartmentController::class, 'store']);
            Route::put('/department/update/{id}', [DepartmentController::class, 'update']);
            Route::delete('/delete/department/{id}', [DepartmentController::class, 'delete']);
            //Rutas para vendedores
            Route::get('/sellers', [SellerController::class, 'index']);
            Route::post('seller/store', [SellerController::class, 'store']);
            Route::put('/seller/update/{id}', [SellerController::class, 'update']);
            Route::delete('/delete/seller/{id}', [SellerController::class, 'destroy']);
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
            //Rutas para resultados medicos
            Route::get('/results/{id}', [MedicineResultController::class, 'indexAdmin']);
            Route::put('result/{id}/{blog_id}', [MedicineResultController::class, 'updateResult']);
            Route::delete('delete-result/{id}', [MedicineResultController::class, 'destroy']);
            Route::post('/blog/result/{id}', [MedicineResultController::class, 'storeResult']);
            Route::get('blog/{blog}/{id}/edit-result', [MedicineResultController::class, 'editResult']);
            Route::get('blog-result/{id}/add', [MedicineResultController::class, 'addResult']);
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
            //Rutas para tarjetas de regalo
            Route::get('/gifts/', [GiftCardController::class, 'indexAdmin']);
            Route::get('/index-gifts/', [GiftCardController::class, 'index']);
            Route::put('gift/{id}', [GiftCardController::class, 'update']);            
            Route::delete('delete-gift/{id}', [GiftCardController::class, 'destroy']);            
            Route::get('gift/{id}/edit', [GiftCardController::class, 'edit']); 
            Route::put('/approve-gift/{id}/{approved}', [GiftCardController::class, 'approve']);
            //Rutas para roles
            Route::get('/roles', [RolesController::class, 'index']);
            Route::put('role/update/{id}', [RolesController::class, 'update']);            
            Route::delete('delete/role/{id}', [RolesController::class, 'destroy']);            
            Route::get('role/{id}/edit', [RolesController::class, 'edit']);
            Route::get('/new-role/', [RolesController::class, 'add']);
            Route::post('/role/store', [RolesController::class, 'store']);
            //Rutas para reportes
            Route::get('/report/stock', [ClothingCategoryController::class, 'reportStock']);
            Route::get('/report/cat-prod/{type}', [ClothingCategoryController::class, 'reportCatProd']);
            Route::get('/report/logs/{type}', [UserController::class, 'reportLogs']);
        });
    });

    //images Tenant
    Route::get('/file/{path}', function ($path) {
        $path = Storage::path($path);
        $path = str_replace('app\\', 'app\\public\\', $path);

        return response()->file($path);
    })->where('path', '.*')->name('file');
    //images tenant
});
