<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');




Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('/auth/info', [AuthController::class, 'infoAction'])->name('info');
    Route::get('/auth/logout', [AuthController::class, 'logoutAction'])->name('logout');


});





// When the route is not defined
Route::get('/{any}', function () {
    return response()->json([
        'status' => 'Error',
        'message' => "The route is not defind!"
    ], 404);
})->where('any', '.*')->name('notFound');
