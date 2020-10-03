<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::prefix('admins')->as('admins.')->group(function () {
        Route::namespace('CMS')->group(function () {

            // Manage Role and Permission

            /** Additional Management **/
            Route::prefix('roles')->as('role.')->group(function () {
                // assign role to user
                Route::post('user/assign', 'RoleController@assignRoleToUser')->name('user.assign');
                // revoke role to user
                Route::post('user/revoke', 'RoleController@revokeRoleToUser')->name('user.revoke');
                // store role with permissions
                Route::post('store/permission', 'RoleController@addRoleWithPermissions')->name('store.permissions');
                // assign permission to role
                Route::put('permission/assign', 'RoleController@assignPermissionsRole')->name('assign.permissions');

                Route::get('sheet/export', 'RoleController@export')->name('export');
            });

            Route::apiResource('roles', 'RoleController');

            Route::prefix('permissions')->group(function () {
                Route::get('/', 'PermissionController@index')->name('permissions.list');
            });
        });
    });

    Route::namespace('CMS')->group(function () {
        Route::get('account/permissions', 'PermissionController@userPermissions');
    });

});
