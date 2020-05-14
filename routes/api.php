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

Route::post('save_user', 'ApiUserController@store')->name('saveUser');
Route::post('get_office_by_referral', 'ApiUserController@getOfficeAdminByReferral')->name('getOfficeByReferral');
Route::post('get_agent_by_referral', 'ApiUserController@getAgentByReferral')->name('getAgentByReferral');



