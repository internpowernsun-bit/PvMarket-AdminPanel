<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\IncotermController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ChargeController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PricePromotionController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MainMenuController;
use App\Http\Controllers\Admin\SubMenuController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ProductDetailOptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PvSpotPriceController;
use App\Http\Controllers\Admin\PageSectionController;

// ── Redirect root to login ──────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('admin.login.page');
});

// ── Guest only routes ───────────────────────────────────────────────────
Route::middleware('guest')->group(function () {

    Route::get('/admin/login', [AuthController::class, 'showLogin'])
        ->name('admin.login.page');

    Route::post('/admin/login', [AuthController::class, 'login'])
        ->name('admin.login');

});

// ── Protected routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User Management
    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');
        Route::get('/admin/users/{id}/edit',            [UserController::class, 'edit'])                ->name('admin.users.edit');
Route::put('/admin/users/{id}/update-basic',     [UserController::class, 'updateBasic'])         ->name('admin.users.update-basic');
Route::put('/admin/users/{id}/update-company',   [UserController::class, 'updateCompany'])       ->name('admin.users.update-company');
Route::patch('/admin/users/{id}/toggle-verified',[UserController::class, 'toggleCompanyVerified'])->name('admin.users.toggle-verified');

    // Schedules
    Route::get('/admin/schedules', [ScheduleController::class, 'index'])
        ->name('admin.schedules.index');

    // Sliders
    Route::prefix('admin/setup/sliders')->name('admin.setup.sliders.')->group(function () {
        Route::get('/',              [SliderController::class, 'index'])  ->name('index');
        Route::get('/create',        [SliderController::class, 'create']) ->name('create');
        Route::post('/',             [SliderController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit',     [SliderController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',          [SliderController::class, 'update']) ->name('update');
        Route::patch('/{id}/toggle', [SliderController::class, 'toggle']) ->name('toggle');
        Route::delete('/{id}',       [SliderController::class, 'destroy'])->name('destroy');
        Route::post('/reorder',      [SliderController::class, 'reorder'])->name('reorder');
    });

    // Advertisements
    Route::prefix('admin/setup/advertisements')->name('admin.setup.advertisements.')->group(function () {
        Route::get('/',              [AdvertisementController::class, 'index'])  ->name('index');
        Route::get('/create',        [AdvertisementController::class, 'create']) ->name('create');
        Route::post('/',             [AdvertisementController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit',     [AdvertisementController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',          [AdvertisementController::class, 'update']) ->name('update');
        Route::patch('/{id}/toggle', [AdvertisementController::class, 'toggle']) ->name('toggle');
        Route::delete('/{id}',       [AdvertisementController::class, 'destroy'])->name('destroy');
    });

    // Incoterms
    Route::prefix('admin/setup/incoterms')->name('admin.setup.incoterms.')->group(function () {
        Route::get('/',          [IncotermController::class, 'index'])  ->name('index');
        Route::get('/create',    [IncotermController::class, 'create']) ->name('create');
        Route::post('/',         [IncotermController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [IncotermController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [IncotermController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [IncotermController::class, 'destroy'])->name('destroy');
    });


    // Coupons
    Route::prefix('admin/setup/coupons')->name('admin.setup.coupons.')->group(function () {
        Route::get('/',          [CouponController::class, 'index'])  ->name('index');
        Route::get('/create',    [CouponController::class, 'create']) ->name('create');
        Route::post('/',         [CouponController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [CouponController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [CouponController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [CouponController::class, 'destroy'])->name('destroy');
    });

    

Route::prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('/',                  [ProductController::class, 'index'])  ->name('index');
    Route::get('/create',            [ProductController::class, 'create']) ->name('create');
    Route::post('/',                 [ProductController::class, 'store'])  ->name('store');
    Route::get('/{id}/edit',         [ProductController::class, 'edit'])   ->name('edit');
    Route::put('/{id}',              [ProductController::class, 'update']) ->name('update');
    Route::patch('/{id}/verify',     [ProductController::class, 'verify']) ->name('verify');
    Route::patch('/{id}/reject',     [ProductController::class, 'reject']) ->name('reject');
    Route::delete('/{id}',           [ProductController::class, 'destroy'])->name('destroy');
    Route::get('/options-by-submenu',[ProductController::class, 'getOptionsBySubMenu'])  ->name('options-by-submenu');
    Route::get('/sub-menus-by-main', [ProductController::class, 'getSubMenusByMainMenu'])->name('sub-menus-by-main');
});

    

Route::get('/setup/units',               [UnitController::class, 'index'])->name('admin.setup.units.index');
Route::get('/setup/units/create',        [UnitController::class, 'create'])->name('admin.setup.units.create');
Route::post('/setup/units',              [UnitController::class, 'store'])->name('admin.setup.units.store');
Route::get('/setup/units/{id}/edit',     [UnitController::class, 'edit'])->name('admin.setup.units.edit');
Route::put('/setup/units/{id}',          [UnitController::class, 'update'])->name('admin.setup.units.update');
Route::patch('/setup/units/{id}/toggle', [UnitController::class, 'toggleStatus'])->name('admin.setup.units.toggle');
Route::delete('/setup/units/{id}',       [UnitController::class, 'destroy'])->name('admin.setup.units.destroy');

    

Route::prefix('admin/setup/brands')->name('admin.setup.brands.')->group(function () {
    Route::get('/',              [BrandController::class, 'index'])  ->name('index');
    Route::get('/create',        [BrandController::class, 'create']) ->name('create');
    Route::post('/',             [BrandController::class, 'store'])  ->name('store');
    Route::get('/{id}/edit',     [BrandController::class, 'edit'])   ->name('edit');
    Route::put('/{id}',          [BrandController::class, 'update']) ->name('update');
    Route::patch('/{id}/toggle', [BrandController::class, 'toggle'])->name('toggle');
    Route::delete('/{id}',       [BrandController::class, 'destroy'])->name('destroy');
});

    // Sub-Admins
    Route::prefix('admin/setup/sub-admins')->name('admin.setup.sub-admins.')->group(function () {
        Route::get('/',          [SubAdminController::class, 'index'])  ->name('index');
        Route::get('/create',    [SubAdminController::class, 'create']) ->name('create');
        Route::post('/',         [SubAdminController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [SubAdminController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [SubAdminController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [SubAdminController::class, 'destroy'])->name('destroy');
    });

    // Charges
    Route::prefix('admin/setup/charges')->name('admin.setup.charges.')->group(function () {
        Route::get('/',          [ChargeController::class, 'index'])  ->name('index');
        Route::get('/create',    [ChargeController::class, 'create']) ->name('create');
        Route::post('/',         [ChargeController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [ChargeController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [ChargeController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [ChargeController::class, 'destroy'])->name('destroy');
    });

    // Events
    Route::prefix('admin/knowledge-hub/events')->name('admin.knowledge-hub.events.')->group(function () {
        Route::get('/',            [EventController::class, 'index'])  ->name('index');
        Route::get('/create',      [EventController::class, 'create']) ->name('create');
        Route::post('/',           [EventController::class, 'store'])  ->name('store');
        Route::get('/{event}/edit',[EventController::class, 'edit'])   ->name('edit');
        Route::put('/{event}',     [EventController::class, 'update']) ->name('update');
        Route::delete('/{event}',  [EventController::class, 'destroy'])->name('destroy');
    });

    // Blogs
    Route::prefix('admin/knowledge-hub/blogs')->name('admin.knowledge-hub.blogs.')->group(function () {
        Route::get('/',          [BlogController::class, 'index'])  ->name('index');
        Route::get('/create',    [BlogController::class, 'create']) ->name('create');
        Route::post('/',         [BlogController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [BlogController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [BlogController::class, 'destroy'])->name('destroy');
    });

    // Price Promotions
    Route::prefix('admin/knowledge-hub/price-promotions')->name('admin.knowledge-hub.price-promotions.')->group(function () {
        Route::get('/',          [PricePromotionController::class, 'index'])  ->name('index');
        Route::get('/create',    [PricePromotionController::class, 'create']) ->name('create');
        Route::post('/',         [PricePromotionController::class, 'store'])  ->name('store');
        Route::get('/{id}/edit', [PricePromotionController::class, 'edit'])   ->name('edit');
        Route::put('/{id}',      [PricePromotionController::class, 'update']) ->name('update');
        Route::delete('/{id}',   [PricePromotionController::class, 'destroy'])->name('destroy');
    });

    // Product Detail Options
Route::prefix('admin/products/detail-options')->name('admin.products.detail-options.')->group(function () {
    Route::get('/',                        [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'index'])  ->name('index');
    Route::get('/create',                  [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'create']) ->name('create');
    Route::post('/',                       [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'store'])  ->name('store');
    Route::get('/{detailOption}/edit',     [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'edit'])   ->name('edit');
    Route::put('/{detailOption}',          [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'update']) ->name('update');
    Route::delete('/{detailOption}',       [App\Http\Controllers\Admin\ProductDetailOptionController::class, 'destroy'])->name('destroy');
});



Route::prefix('admin/knowledge-hub/pv-spot-price')->name('admin.knowledge-hub.pv-spot-price.')->group(function () {
    Route::get('/',          [PvSpotPriceController::class, 'index'])  ->name('index');
    Route::get('/create',    [PvSpotPriceController::class, 'create']) ->name('create');
    Route::post('/',         [PvSpotPriceController::class, 'store'])  ->name('store');
    Route::get('/{id}/edit', [PvSpotPriceController::class, 'edit'])   ->name('edit');
    Route::put('/{id}',      [PvSpotPriceController::class, 'update']) ->name('update');
    Route::delete('/{id}',   [PvSpotPriceController::class, 'destroy'])->name('destroy');
});

    

Route::get('/setup/sub-menus',               [SubMenuController::class, 'index'])->name('admin.setup.sub-menus.index');
Route::get('/setup/sub-menus/create',        [SubMenuController::class, 'create'])->name('admin.setup.sub-menus.create');
Route::post('/setup/sub-menus',              [SubMenuController::class, 'store'])->name('admin.setup.sub-menus.store');
Route::get('/setup/sub-menus/{id}/edit',     [SubMenuController::class, 'edit'])->name('admin.setup.sub-menus.edit');
Route::put('/setup/sub-menus/{id}',          [SubMenuController::class, 'update'])->name('admin.setup.sub-menus.update');
Route::patch('/setup/sub-menus/{id}/toggle', [SubMenuController::class, 'toggleStatus'])->name('admin.setup.sub-menus.toggle');
Route::delete('/setup/sub-menus/{id}',       [SubMenuController::class, 'destroy'])->name('admin.setup.sub-menus.destroy');
Route::patch('sub-menus/{id}/stock-toggle', [SubMenuController::class, 'toggleStock'])
     ->name('admin.setup.sub-menus.stock-toggle');

    // Main Menus
Route::get('/setup/main-menus',                  [MainMenuController::class, 'index'])->name('admin.setup.main-menus.index');
Route::get('/setup/main-menus/create',           [MainMenuController::class, 'create'])->name('admin.setup.main-menus.create');
Route::post('/setup/main-menus',                 [MainMenuController::class, 'store'])->name('admin.setup.main-menus.store');
Route::get('/setup/main-menus/{id}/edit',        [MainMenuController::class, 'edit'])->name('admin.setup.main-menus.edit');
Route::put('/setup/main-menus/{id}',             [MainMenuController::class, 'update'])->name('admin.setup.main-menus.update');
Route::patch('/setup/main-menus/{id}/toggle',    [MainMenuController::class, 'toggleStatus'])->name('admin.setup.main-menus.toggle');
Route::delete('/setup/main-menus/{id}',          [MainMenuController::class, 'destroy'])->name('admin.setup.main-menus.destroy');
Route::patch('main-menus/{id}/stock-toggle', [MainMenuController::class, 'toggleStock'])
     ->name('admin.setup.main-menus.stock-toggle');

    // News
    Route::get   ('admin/knowledge-hub/news',           [NewsController::class, 'index'])  ->name('admin.knowledge-hub.news.index');
    Route::get   ('admin/knowledge-hub/news/create',    [NewsController::class, 'create']) ->name('admin.knowledge-hub.news.create');
    Route::post  ('admin/knowledge-hub/news',           [NewsController::class, 'store'])  ->name('admin.knowledge-hub.news.store');
    Route::get   ('admin/knowledge-hub/news/{id}/edit', [NewsController::class, 'edit'])   ->name('admin.knowledge-hub.news.edit');
    Route::put   ('admin/knowledge-hub/news/{id}',      [NewsController::class, 'update']) ->name('admin.knowledge-hub.news.update');
    Route::delete('admin/knowledge-hub/news/{id}',      [NewsController::class, 'destroy'])->name('admin.knowledge-hub.news.destroy');

    // Countries
    Route::post  ('admin/setup/countries/import-api',        [CountryController::class, 'importFromApi'])->name('admin.setup.countries.import-api');
    Route::get   ('admin/setup/countries',                   [CountryController::class, 'index'])        ->name('admin.setup.countries.index');
    Route::get   ('admin/setup/countries/create',            [CountryController::class, 'create'])       ->name('admin.setup.countries.create');
    Route::post  ('admin/setup/countries',                   [CountryController::class, 'store'])        ->name('admin.setup.countries.store');
    Route::get   ('admin/setup/countries/{id}/edit',         [CountryController::class, 'edit'])         ->name('admin.setup.countries.edit');
    Route::put   ('admin/setup/countries/{id}',              [CountryController::class, 'update'])       ->name('admin.setup.countries.update');
    Route::delete('admin/setup/countries/{id}',              [CountryController::class, 'destroy'])      ->name('admin.setup.countries.destroy');
    Route::patch ('admin/setup/countries/{id}/default',      [CountryController::class, 'setDefault'])   ->name('admin.setup.countries.default');

    // Profile
    Route::get('/admin/profile',          [ProfileController::class, 'show'])           ->name('admin.profile');
    Route::put('/admin/profile',          [ProfileController::class, 'update'])         ->name('admin.profile.update');
    Route::put('/admin/profile/password', [ProfileController::class, 'updatePassword']) ->name('admin.profile.password');
    
     

Route::prefix('admin/page-sections')->name('admin.page-sections.')->group(function () {
    Route::get('/',           [PageSectionController::class, 'index']) ->name('index');
    Route::get('/{page}',     [PageSectionController::class, 'edit'])  ->name('edit');
    Route::put('/{page}',     [PageSectionController::class, 'update'])->name('update');
});

    // Logout
    Route::post('/admin/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');

});