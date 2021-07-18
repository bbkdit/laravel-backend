<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    //  Route::post('login', 'Auth\AuthController@login')->name('login');
    //  Route::post('register', 'Auth\AuthController@register');
     Route::post('/register', [AuthController::class, 'register']);

     Route::group([
        'middleware' => 'auth:api'
      ], function() {
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
        //   Route::get('logout', 'Auth\AuthController@logout');
        //   Route::get('user', 'Auth\AuthController@user');
   });
});