<?php

use Illuminate\Http\Request;
use App\Actions\Roles\GetRoleAction;
use App\Actions\Users\GetUserAction;
use Illuminate\Support\Facades\Route;
use App\Actions\Admins\GetAdminAction;
use App\Actions\Roles\StoreRoleAction;
use App\Actions\Roles\DeleteRoleAction;
use App\Actions\Roles\UpdateRoleAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Roles\GetRoleListAction;
use App\Actions\Users\GetUserListAction;
use App\Actions\Admins\DeleteAdminAction;
use App\Actions\Admins\UpdateAdminAction;
use App\Actions\Admins\GetAdminListAction;
use App\Actions\Auth\User\UserLoginAction;
use App\Actions\Auth\Admin\AdminLoginAction;
use App\Actions\Balances\StoreBalanceAction;
use App\Actions\Auth\User\UserRegisterAction;
use App\Actions\Auth\Admin\AdminRegisterAction;
use App\Actions\Permissions\StorePermissionAction;
use App\Actions\Balances\GetBalanceListByUserAction;
use App\Actions\Permissions\GetPermissionListAction;
use App\Actions\Permissions\GetPermissionsByRoleAction;
use App\Actions\Permissions\AssignPermissionToRoleAction;
use App\Actions\Permissions\AssignOnePermissionToRoleAction;

Route::group(['prefix'=>'permissions'], function(){
    Route::post('assign', AssignPermissionToRoleAction::class);
    Route::get('', GetPermissionListAction::class);
    Route::get('{id}',GetPermissionsByRoleAction ::class);
    Route::put('', StorePermissionAction::class);
    //AssignOnePermissionToRoleAction
    Route::post('assignOnePermissionToRole', AssignOnePermissionToRoleAction::class);
});
Route::group(['prefix'=>'roles'], function(){
    Route::get('', GetRoleListAction::class);
    Route::get('{id}', GetRoleAction::class);
    Route::put('', StoreRoleAction::class);
    Route::post('{id}', UpdateRoleAction::class);
    Route::delete('{id}', DeleteRoleAction::class);
});
Route::group(['prefix'=>'balances'], function(){
    Route::put('', StoreBalanceAction::class);
    Route::get('', GetBalanceListByUserAction::class);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('', UserLoginAction::class);
    Route::put('', UserRegisterAction::class);

});

Route::group(['prefix' => 'auth/admins'], function () {
    Route::put('', AdminRegisterAction::class);
    Route::post('', AdminLoginAction::class);
});
Route::group(['prefix'=>'users'], function(){
    Route::get('', GetUserListAction::class);
    Route::get('{id}', GetUserAction::class);
    Route::post('{id}', UpdateUserAction::class);
    Route::delete('{id}', DeleteUserAction::class);
});

Route::group(['prefix' => 'admins'], function () {
    Route::get('', GetAdminListAction::class);
    Route::get('{id}', GetAdminAction::class);
    Route::put('', AdminRegisterAction::class);
    Route::post('{id}', UpdateAdminAction::class);
    Route::delete('{id}', DeleteAdminAction::class);
});

