<?php

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

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('/test', 'StuAdmin\BaseController@getPostParams');
    Route::get('/createdPerm', 'StuAdmin\RbacController@createPermission');

    Route::group(['prefix' => 'rbac'], function () {
        Route::resource('permission', 'StuAdmin\Rbac\PermissionController');
        Route::resource('role', 'StuAdmin\Rbac\RoleController');
        Route::group(['prefix' => 'role'], function (){
            Route::post('/bind', 'StuAdmin\Rbac\RoleController@bindPermisson');
            Route::post('/remove', 'StuAdmin\Rbac\RoleController@removePermission');

        });
        Route::resource('user', 'StuAdmin\Rbac\UserController');
        Route::group(['prefix' => 'user'], function (){
            Route::post('/bind', 'StuAdmin\Rbac\UserController@bindRole');
            Route::post('/remove', 'StuAdmin\Rbac\UserController@removeRole');
            Route::post('/check', 'StuAdmin\Rbac\UserController@checkRole');
            Route::get('/get/userlist', 'StuAdmin\Rbac\UserController@getUserList');
            Route::post('/login', 'StuAdmin\Rbac\UserController@login');
            Route::post('/logout', 'StuAdmin\Rbac\UserController@logout');
            Route::post('/check_user', 'StuAdmin\Rbac\UserController@checkUser');
        });
    });
});

Route::group(['prefix' => 'auth'], function () {
    //列表
    Route::post('/test', 'StuAdmin\AuthController@test');

    Route::post('/get_menu_list', 'StuAdmin\AuthController@getMenuList');
    Route::post('/check_user_permission', 'StuAdmin\AuthController@checkUserPermission');

});


//Route::get('/test', 'StuAdmin\BaseController@test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/do/something', [
    'uses' => 'StuAdmin\RbacController@show',
//    'middleware' => 'rbac:role,roleSlug1|roleSlug2',
]);
