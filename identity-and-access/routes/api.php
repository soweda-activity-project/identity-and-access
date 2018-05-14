<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/users-invitations', 'UserController@inviteUserToRegister');
Route::post('/users/{inviteduserid}/registration-invitations', 'UserController@registerIvitedUser');
Route::post('/users/{username}/login', 'UserController@login');
Route::post('/users/{username}/change-password', 'UserController@changePassword');


Route::post('/roles', 'RoleController@createNewRole');
Route::post('/roles/{roleid}/groups-playing-role', 'RoleController@addGroupToRole');
Route::post('/roles/{roleid}/users-playing-role', 'RoleController@addUserToRole');


Route::post('/groups', 'GroupController@createNewGroup');
Route::post('/groups/{groupid}/members', 'GroupController@addGroupMemberToGroup');






