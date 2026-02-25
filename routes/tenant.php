<?php

declare(strict_types=1);

use App\Http\Controllers\AddressUserController;
use App\Http\Controllers\Admin\AdminPushTokenController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdvertController;
use App\Http\Controllers\Api\HomeDataController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CajasController;
use App\Http\Controllers\ClothingCategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EspecialistaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\LogosController;
use App\Http\Controllers\MatriculaEstudianteController;
use App\Http\Controllers\MedicineResultController;
use App\Http\Controllers\MetaTagsController;
use App\Http\Controllers\MetricaController;
use App\Http\Controllers\PagosMatriculaController;
use App\Http\Controllers\PersonalUserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SocialNetworkController;
use App\Http\Controllers\SuscriptorController;
use App\Http\Controllers\TenantCarouselController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantInfoController;
use App\Http\Controllers\TenantSocialNetworkController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\VentaEspecialistaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
//Controladores barberia
use App\Http\Controllers\Admin\BarberoController;
use App\Http\Controllers\Admin\BarberoTrabajoController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CitaAdminController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\InstagramCaptionSettingsController;
use App\Http\Controllers\Admin\InstagramCaptionTemplateController;
use App\Http\Controllers\Admin\InstagramCollectionController;
use App\Http\Controllers\Admin\InstagramController;
use App\Http\Controllers\Admin\InstagramPostController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\TenantSettingController;
use App\Http\Controllers\AutoBookingController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicRegistrationController;
use App\Http\Controllers\RouletteController;
use App\Services\FcmHttpV1;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


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
    Route::group(['middleware' => 'isAre'], function () {
        Route::get('/', [FrontendController::class, 'index']);
    });
    //Rutas para suscriptores    
    Route::get('/suscriptor/', [SuscriptorController::class, 'index']);
    Route::post('/suscriptor/store', [SuscriptorController::class, 'store']);
    //Rutas para suscriptores    
    Route::get('/about_us', [FrontendController::class, 'aboutUs']);
    Route::get('/contact', [FrontendController::class, 'contact']);
    Route::get('compare/vehicles', [FrontendController::class, 'compareIndex']);
    Route::get('/get-cart-details/{code}', [ClothingCategoryController::class, 'getCartDetail']);
    Route::get('/comment/{show}', [FrontendController::class, 'index']);
    Route::post('/add-favorite', [FavoriteController::class, 'store']);
    Route::get('/check/list-fav/{id}', [FavoriteController::class, 'checkCode']);
    Route::get('/paginate/{next_page}/{id}', [FrontendController::class, 'paginate'])->name('paginate');
    //Con prefijo aclimate
    Route::prefix('aclimate')->middleware('setTenantDatabase')->group(function () {
        Route::get('/', [FrontendController::class, 'index']);
        Route::get('/about_us', [FrontendController::class, 'aboutUs']);
        Route::get('/contact', [FrontendController::class, 'contact']);
        Route::get('compare/vehicles', [FrontendController::class, 'compareIndex']);
        Route::get('/get-cart-details/{code}', [ClothingCategoryController::class, 'getCartDetail']);
        Route::get('/comment/{show}', [FrontendController::class, 'index']);
        Route::post('/add-favorite', [FavoriteController::class, 'store']);
        Route::get('/check/list-fav/{id}', [FavoriteController::class, 'checkCode']);
        Route::get('/paginate/{next_page}/{id}', [FrontendController::class, 'paginate'])->name('paginate-aclimate');
        Route::get('/file/{path}', function ($path) {
            $path = Storage::path($path);
            $path = str_replace('app\\', 'app\\public\\', $path);

            return response()->file($path);
        })->where('path', '.*')->name('aclifile');
    });
    Route::get('/vencido', function () {
        return view('frontend.design_ecommerce.vencido'); // Nombre de tu vista
    });

    //Con prefijo aclimate
    Route::group(['middleware' => 'isLicense'], function () {
        Route::get('/', [FrontendController::class, 'index']);
        //Google authentication ----->
        Route::get('/google-auth/redirect', [AuthController::class, 'redirectGoogle']);
        Route::get('/google-auth/callback', [AuthController::class, 'callbackGoogle']);
        //Google authentication <-----

        //Facebook authentication ------>
        Route::get('/facebook-auth/redirect', [AuthController::class, 'redirectFacebook']);
        Route::get('/facebook-auth/callback', [AuthController::class, 'callbackFacebook']);
        //Facebook authentication <------
        Route::get('/roulette', [RouletteController::class, 'index']);
        Route::post('/roulette/spin', [RouletteController::class, 'spin'])
            ->name('roulette.spin');

        Route::group(['middleware' => 'isAre'], function () {
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
            Route::get('/view-cart/{unique_cart_id}', [CartController::class, 'viewCart']);
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
            Route::get('/catalogo/{barber}', [FrontendController::class, 'index']);

            //rutas barberia
            Route::get('/barberos/disponibles-para', [BookingController::class, 'barberosDisponibles']);
            Route::get('/barberos/{barbero}/agendar', [BookingController::class, 'showForm']);
            Route::get('/barberos/{barbero}/servicios', [BookingController::class, 'servicios']);
            Route::get('/barberos/{barbero}/disponibilidad', [BookingController::class, 'disponibilidad']);
            Route::post('/reservas', [BookingController::class, 'reservar']);
            // routes/web.php
            Route::get('/clients/{client}/auto-optin', function (\App\Models\Client $client) {
                $client->update(['auto_book_opt_in' => true]);
                return view('auto_book.optin_ok', ['client' => $client]);
            })->name('clients.auto.optin')->middleware('signed');
            Route::get('/auto-book/accept/{cita}',  [AutoBookingController::class, 'accept'])
                ->middleware('signed')->name('auto.accept');
            Route::get('/auto-book/decline/{cita}', [AutoBookingController::class, 'decline'])
                ->middleware('signed')->name('auto.decline');
            // Mostrar formulario de reprogramar (GET) + enviar cambios (POST)
            Route::get('/auto-book/resched/{cita}', [AutoBookingController::class, 'reschedForm'])
                ->middleware('signed')->name('auto.resched');

            Route::post('/auto-book/resched/{cita}', [AutoBookingController::class, 'reschedApply'])
                ->middleware('signed')->name('auto.resched.apply');
            //Rutas para los usuarios del form de ciclismo
            Route::get('/eventos/{event}', [PublicEventController::class, 'show']);
            Route::post('/eventos/{event}/inscribirse', [PublicRegistrationController::class, 'store']);
            Route::get('/registrations/show', [PublicRegistrationController::class, 'show'])->name('registrations.show');
        });

        Route::get('/test-fcm', function () {
            // Este es el token FCM del navegador/admin que quieras probar.
            // Debes haber obtenido este token en el front con messaging.getToken(...)
            $deviceToken = 'ACA_VA_TU_TOKEN_DEL_NAVEGADOR_O_MOVIL';

            $message = [
                'token' => $deviceToken,
                'notification' => [
                    'title' => 'Nueva cita reservada',
                    'body'  => 'Cliente Juan Pérez hoy a las 3:30pm',
                ],
                'data' => [
                    'type'    => 'appointment',
                    'cita_id' => '123',
                    'url'     => url('/admin/citas/123'),
                ],
            ];

            $result = FcmHttpV1::send(env('FCM_PROJECT_ID'), $message);

            dd($result);
        });
        Auth::routes();

        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        //Con prefijo aclimate
        Route::prefix('aclimate')->middleware('setTenantDatabase')->group(function () {
            // Rutas de autenticación de Google
            Route::get('/google-auth/redirect', [AuthController::class, 'redirectGoogle']);
            Route::get('/google-auth/callback', [AuthController::class, 'callbackGoogle']);

            // Rutas de autenticación de Facebook
            Route::get('/facebook-auth/redirect', [AuthController::class, 'redirectFacebook']);
            Route::get('/facebook-auth/callback', [AuthController::class, 'callbackFacebook']);

            // Otras rutas de tu aplicación
            Route::get('category', [FrontendController::class, 'category']);
            Route::get('/blog/index',  [BlogController::class, 'index']);
            Route::get('blog/{blog}/{name_url}', [BlogController::class, 'showArticles']);
            Route::post('send-email/blog', [BlogController::class, 'sendEmail']);
            Route::get('departments/index', [FrontendController::class, 'departments']);
            Route::get('category/{id}', [FrontendController::class, 'category']);
            Route::get('checkout', [CheckOutController::class, 'index']);
            Route::get('clothes-category/{id}/{department_id}', [FrontendController::class, 'clothesByCategory']);
            Route::get('detail-clothing/{id}/{cat_id}', [FrontendController::class, 'DetailClothingById']);
            Route::post('/add-to-cart', [CartController::class, 'store']);
            Route::post('/edit-quantity', [CartController::class, 'updateQuantity']);
            Route::get('/view-cart/{unique_cart_id}', [CartController::class, 'viewCart']);
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
        });
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
            Route::get('/index-gifts/', [GiftCardController::class, 'index']);
        });

        Route::group(['middleware' => 'isAdmin'], function () {
            //Acerca de y ayuda
            Route::get('/software/about_us', function () {
                return view('admin.about_us.index', ['titulo' => 'Información Importante']);
            });
            Route::get('/help', function () {
                return view('admin.about_us.help', ['titulo' => 'Información Importante']);
            });
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
            Route::post('/save/detail/{id}', [BuyController::class, 'updateDetailBuy']);
            Route::get('/total-buys', [BuyController::class, 'indexTotalBuys']);
            Route::get('/new-buy/{id}', [BuyController::class, 'indexBuy']);
            Route::get('/buy/details/admin/{id}', [BuyController::class, 'buyDetailsAdmin']);
            Route::put('/approve/{id}/{ready}', [BuyController::class, 'approve']);
            Route::put('/ready/{id}/{approved}', [BuyController::class, 'readyToGive']);
            Route::put('/delivery/{id}/{delivery}', [BuyController::class, 'delivery']);
            Route::delete('delete-buy/{id}', [BuyController::class, 'destroy']);
            Route::get('/get/buys/select/{id}', [BuyController::class, 'getBuys']);
            Route::get('/buys/total', [BuyController::class, 'getBuysTotals'])->name('buys.total');
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
            //Rutas para logos de clientes
            Route::get('/logos', [LogosController::class, 'index']);
            Route::put('logos/update/{id}', [LogosController::class, 'update']);
            Route::delete('delete/logos/{id}', [LogosController::class, 'destroy']);
            Route::post('/logos/store', [LogosController::class, 'store']);
            //Cambiar de TENANT AV
            Route::get('/switch-tenant/{identifier}', [TenantController::class, 'switchTenant'])->name('tenant.switch');
            //Rutas para gestionar cajas
            Route::get('/cajas', [CajasController::class, 'index']);
            Route::put('cajas/update/{id}', [CajasController::class, 'update']);
            Route::delete('delete/cajas/{id}', [CajasController::class, 'destroy']);
            Route::post('/cajas/store', [CajasController::class, 'store']);
            Route::post('/open/cajas/{id}', [CajasController::class, 'open']);
            Route::post('/close/cajas/{id}', [CajasController::class, 'close']);
            Route::get('/cajas/arqueos/{id}', [CajasController::class, 'indexArqueos']);
            //Rutas para gestionar especialistas
            Route::get('/especialistas', [EspecialistaController::class, 'index']);
            Route::put('especialistas/update/{id}', [EspecialistaController::class, 'update']);
            Route::delete('/especialistas/destroy/{id}', [EspecialistaController::class, 'destroy']);
            Route::delete('/especialistas/destroy/service/{id}/{especialista_id}', [EspecialistaController::class, 'destroyService']);
            Route::post('/especialistas/store', [EspecialistaController::class, 'store']);
            Route::post('/especialistas/service/store-new', [EspecialistaController::class, 'storeService']);
            Route::get('/services/specialists/{id}', [EspecialistaController::class, 'indexServices']);
            Route::get('/get/products/select/{id}', [EspecialistaController::class, 'getProductsToSelect']);
            Route::get('/especialistas/service/list/{id}', [EspecialistaController::class, 'listServices']);
            //Rutas para gestionar estudiantes y sus matriculas
            Route::get('/estudiantes/manage/{tipo}', [EstudianteController::class, 'index']);
            Route::put('estudiantes/update/{id}', [EstudianteController::class, 'update']);
            Route::delete('delete/estudiantes/{id}', [EstudianteController::class, 'destroy']);
            Route::post('/estudiantes/store', [EstudianteController::class, 'store']);
            Route::post('matricula/estudiante/{id}', [MatriculaEstudianteController::class, 'matriculaEstudiante']);
            Route::get('/list/matricula/{id}', [MatriculaEstudianteController::class, 'index']);
            Route::put('matricula/update/{id}', [MatriculaEstudianteController::class, 'update']);
            Route::delete('delete/matricula/{id}', [MatriculaEstudianteController::class, 'destroy']);
            //Rutas para los pagos de las matriculas
            Route::get('/pagos/matricula/{id}', [PagosMatriculaController::class, 'index']);
            Route::put('pago/matricula/update/{id}', [PagosMatriculaController::class, 'update']);
            Route::delete('delete/matricula/pago/{id}', [PagosMatriculaController::class, 'destroy']);
            Route::post('/pago/matricula/store/{id}', [PagosMatriculaController::class, 'store']);
            //Rutas para tipos de pago
            Route::get('/tipo_pagos', [TipoPagoController::class, 'index']);
            Route::put('tipo_pago/update/{id}', [TipoPagoController::class, 'update']);
            Route::delete('delete/tipo_pago/{id}', [TipoPagoController::class, 'destroy']);
            Route::post('/tipo_pago/store', [TipoPagoController::class, 'store']);
            //Rutas para ventas de especialstas
            Route::get('/ventas/especialistas/{id}', [VentaEspecialistaController::class, 'index']);
            Route::get('/ventas/list/', [VentaEspecialistaController::class, 'listVentas']);
            Route::put('tipo_pago/update/{id}', [TipoPagoController::class, 'update']);
            Route::put('anular/venta/{id}', [VentaEspecialistaController::class, 'updateStatus']);
            Route::put('cambiar/venta/{id}', [VentaEspecialistaController::class, 'updateArqueo']);
            Route::delete('delete/tipo_pago/{id}', [TipoPagoController::class, 'destroy']);
            Route::post('venta/especialista/store', [VentaEspecialistaController::class, 'store']);
            Route::get('get-list/especialistas/service/', [VentaEspecialistaController::class, 'getServices']);
            Route::get('/ajax/ventas', [VentaEspecialistaController::class, 'ajaxVentas'])->name('ajax.ventas');
            Route::get('/api/arqueos-validos', [VentaEspecialistaController::class, 'arqueosValidos']);

            //Rutas para ver los movimientos de las cuentas
            Route::get('list-esp/ventas/{fecha}/{fecha_fin}/{id}', [VentaEspecialistaController::class, 'indexVentas']);
            //Rutas para metricas
            Route::get('metrica/admin', [MetricaController::class, 'index']);
            Route::put('metrica/update/{id}', [MetricaController::class, 'update']);
            Route::delete('delete/metrica/{id}', [MetricaController::class, 'destroy']);
            Route::post('/metrica/store', [MetricaController::class, 'store']);
            //Rutas para suscriptores
            Route::get('/suscriptors/admin', [SuscriptorController::class, 'indexAdmin']);
            Route::put('/suscriptor/update/{id}', [SuscriptorController::class, 'update']);
            Route::delete('/suscriptor/delete/{id}', [SuscriptorController::class, 'destroy']);

            // Solo dueño (owner)
            Route::middleware('role:owner')->group(function () {
                // Catálogo servicios global
                Route::get('/servicios', [ServicioController::class, 'index']);
                Route::post('/servicios/store', [ServicioController::class, 'store']);
                Route::put('/servicios/update/{id}', [ServicioController::class, 'update']);
                Route::delete('/servicios/destroy/{id}', [ServicioController::class, 'destroy']);
                // Barberos CRUD y asignación de servicios
                Route::get('/barberos', [BarberoController::class, 'index']);
                Route::post('/barberos/store', [BarberoController::class, 'store']);
                Route::put('/barberos/update/{id}', [BarberoController::class, 'update']);
                Route::delete('/barberos/destroy/{id}', [BarberoController::class, 'destroy']);
                Route::get('/barberos/{id}/services', [BarberoController::class, 'services']);
                Route::post('/barberos/service/store', [BarberoController::class, 'storeService']);
                Route::put('/barberos/{barbero_id}/services/{servicio_id}', [BarberoController::class, 'updateService']);
                Route::delete('/barberos/{barbero}/services/{servicio}', [BarberoController::class, 'destroyService']);
                Route::post('/barberos/{barbero}/photo', [BarberoController::class, 'uploadPhoto'])->name('barberos.photo.upload');
                Route::delete('/barberos/{barbero}/photo', [BarberoController::class, 'deletePhoto'])->name('barberos.photo.delete');
                // Configuración tenant, usuarios, roles, etc. (todos los tuyos)
                Route::get('/seguridad', [SecurityController::class, 'index'])->name('security.index');
                Route::put('/seguridad/user/{id}/role', [SecurityController::class, 'updateRole'])->name('security.updateRole');
                Route::put('/seguridad/user/{id}/barbero', [SecurityController::class, 'attachBarbero'])->name('security.attachBarbero');
                //
                Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
                    ->name('owner.dashboard');
                // Datos para charts (opcional)
                Route::get('/owner/dashboard/data', [OwnerDashboardController::class, 'data'])
                    ->name('owner.dashboard.data');
                // ...
                Route::get('/payroll',        [PayrollController::class, 'index'])->name('payroll.index');
                Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
                Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
                Route::put('/payroll/{payroll}/close', [PayrollController::class, 'close'])->name('payroll.close');
                Route::put('/payroll/{payroll}/reopen', [PayrollController::class, 'reopen'])->name('payroll.reopen');

                // actualizar ítems (ajustes / marcar pagado)
                Route::put('/payroll/items/{item}', [PayrollController::class, 'updateItem'])->name('payroll.items.update');
                Route::put('/payroll/items/{item}/paid', [PayrollController::class, 'markItemPaid'])->name('payroll.items.paid');

                // config rápida de comisión por barbero
                Route::get('/pay/config', [PayrollController::class, 'config'])->name('payroll.config');
                Route::put('/payroll/config/barbero/{barbero}', [PayrollController::class, 'updateBarberCommission'])->name('payroll.barbero.commission');
                Route::put('/payroll/{payroll}/pay-all', [PayrollController::class, 'markAllPaid'])
                    ->name('payroll.pay_all');

                Route::get('/payroll/{payroll}/export/csv', [PayrollController::class, 'exportCsv'])
                    ->name('payroll.export.csv');
                Route::middleware(['auth', 'can:owner-only'])->group(function () {
                    Route::get('/payroll/{payroll}/export/pdf', [PayrollController::class, 'exportPdf'])
                        ->name('payroll.export.pdf');
                });
                Route::get('/clientes', [\App\Http\Controllers\Admin\ClientController::class, 'index'])->name('clientes.index');
                Route::get('/clientes/{client}/edit', [\App\Http\Controllers\Admin\ClientController::class, 'edit'])->name('clientes.edit');
                Route::put('/clientes/{client}', [\App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clientes.update');
                // si quieres crear manualmente:
                Route::post('/clientes', [\App\Http\Controllers\Admin\ClientController::class, 'store'])->name('clientes.store');
                /**
                 * Instagram - conexión
                 */
                Route::get('/instagram', [InstagramController::class, 'index'])->name('instagram.index');
                Route::get('/instagram/connect', [InstagramController::class, 'connect'])->name('instagram.connect');
                Route::get('/instagram/callback', [InstagramController::class, 'callback'])->name('instagram.callback');
                Route::delete('/instagram/disconnect/{id}', [InstagramController::class, 'disconnect'])->name('instagram.disconnect');

                /**
                 * Instagram - publicaciones individuales
                 */
                Route::get('/instagram/posts', [InstagramPostController::class, 'index'])->name('instagram.posts.index');
                Route::post('/instagram/posts/store', [InstagramPostController::class, 'store'])->name('instagram.posts.store');
                Route::put('/instagram/posts/update/{id}', [InstagramPostController::class, 'update'])->name('instagram.posts.update');
                Route::delete('/instagram/posts/destroy/{id}', [InstagramPostController::class, 'destroy'])->name('instagram.posts.destroy');

                Route::post('/instagram/posts/{id}/publish-now', [InstagramPostController::class, 'publishNow'])->name('instagram.posts.publishNow');
                Route::post('/instagram/posts/{id}/reschedule', [InstagramPostController::class, 'reschedule'])->name('instagram.posts.reschedule');

                /**
                 * Instagram - colecciones
                 */
                Route::get('/instagram/collections', [InstagramCollectionController::class, 'index'])->name('ig.collections.index');
                Route::get('/instagram/collections/create', [InstagramCollectionController::class, 'create'])->name('ig.collections.create');
                Route::post('/instagram/collections/store', [InstagramCollectionController::class, 'store'])->name('ig.collections.store');
                Route::get('/instagram/collections/{id}/edit', [InstagramCollectionController::class, 'edit'])->name('ig.collections.edit');
                Route::put('/instagram/collections/update/{id}', [InstagramCollectionController::class, 'update'])->name('ig.collections.update');
                Route::delete('/instagram/collections/destroy/{id}', [InstagramCollectionController::class, 'destroy'])->name('ig.collections.destroy');

                /**
                 * Instagram - items (imágenes)
                 */
                Route::post('/instagram/collections/{collection}/items/upload', [InstagramCollectionController::class, 'uploadItems'])
                    ->name('ig.collections.items.upload');

                Route::delete('/instagram/collections/{collection}/items/{item}', [InstagramCollectionController::class, 'deleteItem'])
                    ->name('ig.collections.items.delete');

                Route::post('/instagram/collections/{collection}/items/reorder', [InstagramCollectionController::class, 'reorderItems'])
                    ->name('ig.collections.items.reorder');

                /**
                 * Instagram - carruseles (groups)
                 */
                Route::post('/instagram/collections/{collection}/groups/create', [InstagramCollectionController::class, 'createGroup'])
                    ->name('ig.collections.groups.create');

                Route::delete('/instagram/collections/{collection}/groups/{group}', [InstagramCollectionController::class, 'deleteGroup'])
                    ->name('ig.collections.groups.delete');

                Route::post('/instagram/collections/{collection}/items/move', [InstagramCollectionController::class, 'moveItem'])
                    ->name('ig.collections.items.move');

                /**
                 * Instagram - generar post individual por carrusel
                 */
                Route::post('/instagram/collections/{collection}/groups/{group}/generate-post', [InstagramCollectionController::class, 'generatePostForGroup'])
                    ->name('ig.collections.groups.generatePost');

                Route::post('/instagram/collections/{collection}/groups/{group}/analyze-images', [InstagramCollectionController::class, 'analyzeGroupImages'])
                    ->name('ig.collections.groups.analyzeImages');

                Route::post('/instagram/collections/{collection}/groups/{group}/analyze-ecommerce', [InstagramCollectionController::class, 'analyzeEcommerce'])
                    ->name('ig.collections.groups.analyzeEcommerce');

                Route::post('/instagram/collections/{collection}/groups/{group}/save-caption', [InstagramCollectionController::class, 'saveGroupCaption'])
                    ->name('ig.collections.groups.saveCaption');

                Route::post('/instagram/collections/{collection}/mass-schedule', [InstagramCollectionController::class, 'massSchedule'])
                    ->name('ig.collections.massSchedule');

                Route::post('/instagram/collections/{collection}/groups/{group}/add-to-queue', [InstagramCollectionController::class, 'addToQueue'])
                    ->name('ig.collections.groups.addToQueue');

                /**
                 * (Opcional / legacy) generar posts automáticos por colección
                 */
                Route::post('/instagram/collections/{collection}/generate-posts', [InstagramCollectionController::class, 'generatePosts'])
                    ->name('ig.collections.generatePosts');
                Route::put('/instagram/collections/{collection}/groups/{group}', [InstagramCollectionController::class, 'updateGroup'])
                    ->name('ig.collections.groups.update');

                /**
                 * Instagram - plantillas de caption (Spintax)
                 */
                Route::get('/instagram/caption-templates', [InstagramCaptionTemplateController::class, 'index'])
                    ->name('ig.caption-templates.index');
                Route::get('/instagram/caption-templates/create', [InstagramCaptionTemplateController::class, 'create'])
                    ->name('ig.caption-templates.create');
                Route::post('/instagram/caption-templates/store', [InstagramCaptionTemplateController::class, 'store'])
                    ->name('ig.caption-templates.store');
                Route::get('/instagram/caption-templates/{id}/edit', [InstagramCaptionTemplateController::class, 'edit'])
                    ->name('ig.caption-templates.edit');
                Route::put('/instagram/caption-templates/{id}', [InstagramCaptionTemplateController::class, 'update'])
                    ->name('ig.caption-templates.update');
                Route::delete('/instagram/caption-templates/{id}', [InstagramCaptionTemplateController::class, 'destroy'])
                    ->name('ig.caption-templates.destroy');
                Route::post('/instagram/caption-templates/preview', [InstagramCaptionTemplateController::class, 'preview'])
                    ->name('ig.caption-templates.preview');
                Route::post('/instagram/caption-templates/generate', [InstagramCaptionTemplateController::class, 'generate'])
                    ->name('ig.caption-templates.generate');

                /**
                 * Instagram - configuración de generación de captions
                 */
                Route::get('/instagram/caption-settings', [InstagramCaptionSettingsController::class, 'index'])
                    ->name('ig.caption-settings.index');
                Route::put('/instagram/caption-settings/update', [InstagramCaptionSettingsController::class, 'updateSettings'])
                    ->name('ig.caption-settings.update');
                Route::post('/instagram/caption-settings/preview', [InstagramCaptionSettingsController::class, 'previewCaption'])
                    ->name('ig.caption-settings.preview');

                // Hashtag Pools
                Route::get('/instagram/caption-settings/hashtag-pools/create', [InstagramCaptionSettingsController::class, 'createHashtagPool'])
                    ->name('ig.hashtag-pools.create');
                Route::post('/instagram/caption-settings/hashtag-pools/store', [InstagramCaptionSettingsController::class, 'storeHashtagPool'])
                    ->name('ig.hashtag-pools.store');
                Route::get('/instagram/caption-settings/hashtag-pools/{id}/edit', [InstagramCaptionSettingsController::class, 'editHashtagPool'])
                    ->name('ig.hashtag-pools.edit');
                Route::put('/instagram/caption-settings/hashtag-pools/{id}', [InstagramCaptionSettingsController::class, 'updateHashtagPool'])
                    ->name('ig.hashtag-pools.update');
                Route::delete('/instagram/caption-settings/hashtag-pools/{id}', [InstagramCaptionSettingsController::class, 'destroyHashtagPool'])
                    ->name('ig.hashtag-pools.destroy');

                // CTAs
                Route::get('/instagram/caption-settings/ctas/create', [InstagramCaptionSettingsController::class, 'createCta'])
                    ->name('ig.ctas.create');
                Route::post('/instagram/caption-settings/ctas/store', [InstagramCaptionSettingsController::class, 'storeCta'])
                    ->name('ig.ctas.store');
                Route::get('/instagram/caption-settings/ctas/{id}/edit', [InstagramCaptionSettingsController::class, 'editCta'])
                    ->name('ig.ctas.edit');
                Route::put('/instagram/caption-settings/ctas/{id}', [InstagramCaptionSettingsController::class, 'updateCta'])
                    ->name('ig.ctas.update');
                Route::delete('/instagram/caption-settings/ctas/{id}', [InstagramCaptionSettingsController::class, 'destroyCta'])
                    ->name('ig.ctas.destroy');
            });

            // Dueño o manager: ver/gestionar citas de TODOS
            Route::middleware('role:owner,manager')->group(function () {
                Route::get('/citas', [CitaAdminController::class, 'index'])->name('citas.index');
                Route::delete('/citas/{id}', [CitaAdminController::class, 'destroy']);
                Route::put('update/cita/total/{id}', [CitaAdminController::class, 'updateTotal'])->name('cita.updateTotal');
                Route::put('update/cita/pago/{id}', [CitaAdminController::class, 'updateTipoPago'])->name('cita.updateTipoPago');
            });

            // Mis citas: todos los roles autenticados que sean barbero vinculado
            Route::middleware('role:owner,manager,barber')->group(function () {
                Route::post(
                    '/admin/push-token',
                    [AdminPushTokenController::class, 'store']
                )->name('admin.push-token.store');
                Route::put('/citas/{id}/status', [CitaAdminController::class, 'updateStatus']);
                Route::get('/citas/{id}', [CitaAdminController::class, 'show'])->name('citas.show');
                Route::get('/mis-citas', [CitaAdminController::class, 'myIndex'])->name('citas.mine');
                Route::get('/barberos/{barbero}/trabajos', [BarberoTrabajoController::class, 'index'])->name('barberos.trabajos.index');
                Route::post('/barberos/{barbero}/trabajos', [BarberoTrabajoController::class, 'store'])->name('barberos.trabajos.store');
                Route::delete('/barberos/{barbero}/trabajos/{photo}', [BarberoTrabajoController::class, 'destroy'])->name('barberos.trabajos.destroy');
                Route::post('/barberos/{barbero}/trabajos/{photo}/feature', [BarberoTrabajoController::class, 'feature'])->name('barberos.trabajos.feature');
                //Excepciones barbero
                Route::post('/barberos/{barbero}/excepciones', [BarberoController::class, 'storeExcepcion'])->name('barberos.excepciones.store');
                Route::delete('/barberos/{barbero}/excepciones/{id}', [BarberoController::class, 'destroyExcepcion']);
                Route::post('/barberos/{barbero}/bloques', [BarberoController::class, 'storeBloque'])->name('barberos.bloques.store');
                Route::delete('/barberos/{barbero}/bloques/{id}', [BarberoController::class, 'destroyBloque'])->name('barberos.bloques.destroy');
                Route::get('/barberos/{barbero}/agenda', [BarberoController::class, 'agenda'])->name('barberos.agenda');
                //AJAX Barberos excepciones y bloques
                Route::get('/barberos/{barbero}/excepciones/list', [BarberoController::class, 'listExcepciones'])
                    ->name('barberos.excepciones.list');
                Route::get('/barberos/{barbero}/bloques/list', [BarberoController::class, 'listBloques'])
                    ->name('barberos.bloques.list');
                //Calendar
                Route::get('/barberos/{barbero}/calendar', [CalendarController::class, 'barberoCalendar'])
                    ->name('barberos.calendar');
                // Feed de eventos (JSON) que consume FullCalendar
                Route::get('/barberos/{barbero}/events', [CalendarController::class, 'barberoEvents'])
                    ->name('barberos.events');
                Route::put('/citas/{cita}/reschedule', [CalendarController::class, 'reschedule'])
                    ->name('citas.reschedule');
                // NUEVO: crear bloque desde selección
                Route::post('/barberos/{barbero}/bloques/quick', [CalendarController::class, 'quickBlock'])
                    ->name('barberos.bloques.quick');
                Route::get('/settings/policies', [TenantSettingController::class, 'index'])->name('settings.policies');
                Route::put('/settings/policies', [TenantSettingController::class, 'update'])->name('settings.policies.update');
                Route::get('/settings/email', [EmailSettingController::class, 'index'])->name('settings.email');
                Route::put('/settings/email', [EmailSettingController::class, 'update'])->name('settings.email.update');
                Route::put('/settings/payroll', [TenantSettingController::class, 'updateTimePayroll'])->name('settings.payroll.update');
                Route::get('/barberos/{barbero}', [BarberoController::class, 'show'])->name('barberos.show');
            });

            Route::middleware(['signed'])->group(function () {
                Route::get('/booking/{cita}/cancel', [PublicBookingController::class, 'cancel'])->name('booking.cancel');     // GET por simplicidad
                Route::get('/booking/{cita}/reschedule', [PublicBookingController::class, 'reschedule'])->name('booking.reschedule');
            });
            //Rutas para form de ciclismo
            Route::get('/events', [EventController::class, 'index'])->name('events.index');
            Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
            Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
            Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
            Route::delete('/events/destroy/{id}', [EventController::class, 'destroy'])->name('events.destroy');

            // Categorías de evento
            Route::get('/event-categories', [EventCategoryController::class, 'index'])->name('event-categories.index');
            Route::post('/event-categories/store', [EventCategoryController::class, 'store'])->name('event-categories.store');
            Route::get('/event-categories/edit/{id}', [EventCategoryController::class, 'edit'])->name('event-categories.edit');
            Route::put('/event-categories/update/{id}', [EventCategoryController::class, 'update'])->name('event-categories.update');
            Route::delete('/event-categories/destroy/{id}', [EventCategoryController::class, 'destroy'])->name('event-categories.destroy');

            // Inscripciones (admin)
            Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
            Route::post('/registration/store/{id}', [PublicRegistrationController::class, 'store'])->name('registration.store');
            Route::patch('/registrations/{id}/estado', [RegistrationController::class, 'updateEstado'])->name('registrations.updateEstado');
            Route::get('/registrations/{id}/comprobante', [RegistrationController::class, 'descargarComprobante'])->name('registrations.download');
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

Route::prefix('api')->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'api',
])->group(function () {
    Route::post('/login', [ApiLoginController::class, 'login']);
    Route::get('/home/admin/{tenant}', [HomeDataController::class, 'index']);
    Route::get('/tenant/info/{tenant}', [HomeDataController::class, 'getTenantInfo']);
    Route::get('/products/category/{id}/{tenant}', [HomeDataController::class, 'apiIndexByCategory']);
    Route::get('/categories/by-department/{id}/{tenant}', [HomeDataController::class, 'apiCategoriesByDepartment']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});
