<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HolidaysController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\SubCategoriesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\Client;

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



Route::get('/', [AuthController::class, 'index'])->name('admin.login');
Route::post('/auth', [AuthController::class, 'authenticate'])->name('admin.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    Route::controller(CategoriesController::class)->group(function ($route) {
        Route::get('/Categorias', 'index')->name('Categorias');
        Route::get('/getAllCategories', 'selectCategory');
        Route::post('/insertCategory', 'new');
        Route::get('/getCategories', 'show');
        Route::post('/updateCategory', 'update');
        Route::post('/updateStatus', 'updateStatus');
    });

    Route::controller(SubCategoriesController::class)->group(function ($route) {
        Route::get('/SubCategorias', 'index')->name('SubCategorias');
        Route::get('/getSubCategories', 'show');
        Route::post('/insertSubCategory', 'new');
        Route::post('/updateSubCategory', 'update');
        Route::post('/updateStatusSub', 'updateStatus');
        Route::post('/selectSubCat', 'selectSubCategory');
    });

    Route::controller(ProductsController::class)->group(function ($route) {
        Route::get('productos', 'index')->name('products.index');
        Route::get('tableProducts', 'show');
        Route::post('newProduct', 'store');
        Route::post('editProduct', 'update');
        Route::post('/selectProduct', 'selectProduct');
    });

    Route::controller(CalendarController::class)->group(function ($route) {
        Route::get('calendario', 'index')->name('calendar.index');
        Route::get('schemaEvents', 'show');
        Route::post('insertHours', 'store');
    });

    Route::controller(CouponsController::class)->group(function ($route) {
        Route::get('cupones', 'index')->name('coupons.index');
        Route::get('tableCoupon', 'show');
        Route::post('createCoupon', 'create');
        Route::post('updateCoupon', 'update');
        Route::get('CodeCoupon', 'codeCoupon');
    });

    Route::controller(HolidaysController::class)->group(function ($route) {
        Route::get('feriados', 'index')->name('holidays.index');
        Route::get('tableHolidays', 'show');
        Route::post('createHoliday', 'create');
        Route::post('updateHoliday', 'update');
        Route::post('statusHoliday', 'store');
        Route::post('validateHoliday', 'validateHoliday');
    });
    /* */
    Route::controller(ClientController::class)->group(function ($route) {
        Route::get('Clientes', 'index')->name('clients.index');
        Route::get('ClientesSocio', 'indexSocio')->name('clients.indexSocio');
        Route::get('tableClients', 'show');
        Route::get('tableClientsSocio', 'showSocio');
        Route::post('searchPartner', 'search');
        Route::post('renewPartner', 'renew');
        Route::post('updatePartner', 'update');
        Route::post('insertSocio', 'insert');
    });

    Route::controller(OrdersController::class)->group(function ($route) {
        Route::get('Reservas', 'index')->name('orders.index');
        Route::get('Validar_Reserva', 'showReservation')->name('orders.validate');
        Route::get('TableBooking', 'show');
        Route::get('searchReserve/{code}', 'search');
        Route::get('validateR/{code}', 'validateReservation');
        Route::post('attachInvoice', 'attachInvoice');
    });

    Route::controller(UserController::class)->group(function ($route) {
        Route::get('Usuarios', 'index')->name('users.index');
        Route::get('Users/show', 'show');
        Route::post('Users/add', 'store');
    });

    Route::post('/insertSocio', [Client::class, 'insertSocio'])->name('insertSocio');
});
