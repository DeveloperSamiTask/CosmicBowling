<?php

use App\Http\Controllers\Api\Coupon;
use App\Http\Controllers\Frontend\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 /*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/client/check-authentication', function () {
    return response()->json(['isAuthenticated' => auth('client')->check()]);
});

Route::get('coupon/{code}', [Coupon::class, 'show']);
Route::post('/insertSocio', [Client::class, 'insertSocio'])->name('insertSocio');
Route::post('/renewPartner', [Client::class, 'renew'])->name('renew');
Route::post('client/register', [Client::class, 'store']);
Route::post('client/login', [Client::class, 'login']);
Route::post('client/recover', [Client::class, 'recover']);
Route::post('client/reset', [Client::class, 'reset']);

Route::middleware('auth:client')->post('client/logout', [Client::class, 'logout']);
