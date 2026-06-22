<?php

use App\Http\Controllers\Frontend\Booking;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Home;
use App\Http\Controllers\Frontend\Cart;
use App\Http\Controllers\Frontend\Client;
use App\Http\Controllers\Frontend\Payment;
use App\Http\Controllers\Frontend\TestView;
use Illuminate\Http\Request;

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

Route::get('/', [Home::class, 'index'])->name('home.index');

Route::get('/socios', function () {
    return view('frontend.socios');
})->name('socios');

Route::view('/mail', 'frontend.emails.verify');

Route::controller(Client::class)->group(function () {
  Route::get('/Iniciar_sesion', 'index')->name('client.login');
  Route::get('/Registrate', 'create')->name('client.register');
  Route::get('/Perfil', 'show')->name('client.profile');
  Route::get('/verify', 'verifyEmail');
  Route::get('/recover-password', 'recoverPassword');
  Route::post('/searchPartner', 'search');
  Route::post('/renewPartner', 'renew');

  Route::get('/Registro', 'create')->name('registro');
  Route::post('/login-process', 'loginSocios')->name('client.login.process');
  Route::get('/perfilSocio', 'profile')->name('client.profileSocio')->middleware('auth:client');
  Route::get('/renovarVista', 'renovarVista')->name('client.renovar')->middleware('auth:client'); // C:\laragon\www\cosmicbowling\resources\views\frontend\client\renovar.blade.php

  Route::get('/promociones', 'promociones')->name('client.promociones')->middleware('auth:client');
  Route::post('/promociones/imprimir', 'imprimirCupon')->name('client.imprimir')->middleware('auth:client');
  Route::post('/logout', 'logout')->name('client.logout')->middleware('auth:client');

});

Route::controller(Cart::class)->group(function () {
  Route::get('/Carrito/{subcategory}', 'index');
  Route::get('/updateUI/{subcategory}/{date}', 'show');
  Route::post('updateGuests', 'guests');
  Route::post('/cartsession', 'cartData');
  Route::post('/billingsession', 'billingData');
  Route::post('/updateInsurance', 'updateInsurance');
  Route::get('/sesiones', 'showSession');
  Route::get('/holiday/{date}', 'verifyHoliday');
  Route::post('/getBtnPayment', 'paymentData');
});

Route::controller(Booking::class)->group(function () {
  Route::post('/Reserva', 'summaryPayment');
  Route::get('/Correo', 'email');
});

Route::controller(TestView::class)->group(function () {
  Route::get('test', 'index');
});
