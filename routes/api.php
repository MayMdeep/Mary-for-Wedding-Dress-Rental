<?php

use Illuminate\Http\Request;
use App\Actions\Users\GetUserAction;
use Illuminate\Support\Facades\Route;
// roles
use App\Actions\Roles\StoreRoleAction;
use App\Actions\Roles\DeleteRoleAction;
use App\Actions\Roles\UpdateRoleAction;
use App\Actions\Roles\GetRoleAction;
use App\Actions\Roles\GetRoleListAction;
// users
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\GetUserListAction;
//admins
use App\Actions\Admins\DeleteAdminAction;
use App\Actions\Admins\GetAdminAction;
use App\Actions\Admins\UpdateAdminAction;
use App\Actions\Admins\GetAdminListAction;
// dresses
use App\Actions\Dresses\DeleteDressAction;
use App\Actions\Dresses\UpdateDressAction;
use App\Actions\Dresses\GetDressAction;
use App\Actions\Dresses\GetDressListAction;
use App\Actions\Dresses\StoreDressAction;

use App\Actions\Auth\User\UserLoginAction;
use App\Actions\Auth\Admin\AdminLoginAction;
use App\Actions\Auth\User\UserRegisterAction;
use App\Actions\Auth\Admin\AdminRegisterAction;
// Reservations
use App\Actions\Reservations\GetReservationAction;
use App\Actions\Reservations\StoreReservationAction;
use App\Actions\Reservations\DeleteReservationAction;
use App\Actions\Reservations\UpdateReservationAction;
use App\Actions\Reservations\GetReservationListAction;
// permissions
use App\Actions\Permissions\GetPermissionsByRoleAction;
use App\Actions\Permissions\AssignPermissionToRoleAction;
use App\Actions\Permissions\AssignOnePermissionToRoleAction;
use App\Actions\Permissions\GetPermissionListAction;
use App\Actions\Permissions\StorePermissionAction;
// specifications 

use App\Actions\Specifications\StoreSpecificationAction;
use App\Actions\Specifications\DeleteSpecificationAction;
use App\Actions\Specifications\UpdateSpecificationAction;
use App\Actions\Specifications\GetSpecificationListAction;
use App\Actions\Specifications\GetSpecificationAction;

// specification options
use App\Actions\SpecificationOptions\GetSpecificationOptionAction;
use App\Actions\SpecificationOptions\StoreSpecificationOptionAction;
use App\Actions\SpecificationOptions\DeleteSpecificationOptionAction;
use App\Actions\SpecificationOptions\UpdateSpecificationOptionAction;
use App\Actions\SpecificationOptions\GetSpecificationOptionListAction;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('', UserLoginAction::class);
    Route::put('', UserRegisterAction::class);

});

Route::group(['prefix' => 'auth/admins'], function () {
    Route::put('', AdminRegisterAction::class);
    Route::post('', AdminLoginAction::class);
});
Route::group(['prefix' => 'admins'], function () {
    Route::get('', GetAdminListAction::class);
    Route::get('{id}', GetAdminAction::class);
    Route::put('', AdminRegisterAction::class);
    Route::post('{id}', UpdateAdminAction::class);
    Route::delete('{id}', DeleteAdminAction::class);
});
Route::group(['prefix'=>'users'], function(){
    Route::get('', GetUserListAction::class);
    Route::get('{id}', GetUserAction::class);
    Route::post('{id}', UpdateUserAction::class);
    Route::delete('{id}', DeleteUserAction::class);
});

Route::group(['prefix' => 'dresses'], function () {
    Route::get('', GetDressListAction::class);
    Route::get('{id}', GetDressAction::class);
    Route::post('{id}', UpdateDressAction::class);
    Route::delete('{id}', DeleteDressAction::class);
    Route::put('', StoreDressAction::class);
});
Route::group(['prefix' => 'specifications'], function () {
    Route::get('', GetSpecificationListAction::class);
    Route::get('{id}', GetSpecificationAction::class);
    Route::post('{id}', UpdateSpecificationAction::class);
    Route::delete('{id}', DeleteSpecificationAction::class);
    Route::put('', StoreSpecificationAction::class);
});
Route::group(['prefix' => 'specificationOptions'], function () {
    Route::get('', GetSpecificationOptionListAction::class);
    Route::get('{id}', GetSpecificationOptionAction::class);
    Route::post('{id}', UpdateSpecificationOptionAction::class);
    Route::delete('{id}', DeleteSpecificationOptionAction::class);
    Route::put('', StoreSpecificationOptionAction::class);
});

Route::group(['prefix' => 'reservations'], function () {
    Route::get('', GetReservationListAction::class);
    Route::get('{id}', GetReservationAction::class);
    Route::post('{id}', UpdateReservationAction::class);
    Route::delete('{id}', DeleteReservationAction::class);
    Route::put('', StoreReservationAction::class);
});
