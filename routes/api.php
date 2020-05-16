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

//user management
Route::post('save_user', 'ApiUserController@store')->name('saveUser');
Route::post('get_office_by_referral', 'ApiUserController@getOfficeAdminByReferral')->name('getOfficeByReferral');
Route::post('get_agent_by_referral', 'ApiUserController@getAgentByReferral')->name('getAgentByReferral');
//user management

Route::post('get_polling_booth_by_election_divisions', 'PollingBoothController@getByElectionDivisions')->name('getPollingBoothByElectionDivisions');
Route::post('get_pgramasewa_division_by_polling_booths', 'GramasewaDivisionController@getByPollingBooths')->name('getGramasewaDivisionByPollingBooths');
Route::post('get_village_by_gramasewa_divisions', 'VillageController@getByGramasewaDivisions')->name('getVillageByGramasewaDivisions');



