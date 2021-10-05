<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function (Request $request) {

        if(auth()->user()->can('create')){
            
            $permission = \Spatie\Permission\Models\Permission::updateOrCreate(['name'=>'ilias'],['name'=>'ilias']);
            $role = \Spatie\Permission\Models\Role::create(['name' => 'super-ilias']);
            $role->givePermissionTo($permission);

            $user = App\Models\User::find(2);
            $user->assignRole('super-ilias');

        }

    });

   

    Route::post('/auth/logout', [AuthController::class, 'logout']);



    Route::get('/roles', function (Request $request) {
        return  \Spatie\Permission\Models\Role::all();
    });
    Route::get('/permissions', function (Request $request) {
        return  \Spatie\Permission\Models\Permission::all();
    });
});





        $users = [
            [
                'email'=>'awssam@awssam.com',
                'name'=>'awssam',
                'password'=>bcrypt('1993830')
            ]
        ];

        foreach ($users as $user){
            $u = User::updateOrCreate(['email' => $user['email']], $user);
            $u->assignRole('super-admin');
        }
















// When the route is not defined
Route::get('/{any}', function () {
    return response()->json([
        'status' => 'Error',
        'message' => "The route is not defind!"
    ], 404);
})->where('any', '.*')->name('notFound');


Route::post('/{any}', function () {
    return response()->json([
        'status' => 'Error',
        'message' => "The route is not defind!"
    ], 404);
})->where('any', '.*')->name('notFound');
