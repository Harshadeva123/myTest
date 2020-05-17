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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'auth:api'], function(){

    Route::post('auth_test', function (){
        return \Illuminate\Support\Facades\Auth::user()->fName.' api authenticated';
    })->name('authTest');


});

Route::post('get_polling_booths_by_election_division', 'PollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
Route::post('get_gramasewa_divisions_by_polling_booth', 'GramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
Route::post('get_villages_by_gramasewa_division', 'VillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');


//user management
Route::post('save_user', 'ApiUserController@store')->name('saveUser');
Route::post('login', 'ApiUserController@login')->name('loginUser');
Route::post('get_office_by_referral', 'ApiUserController@getOfficeAdminByReferral')->name('getOfficeByReferral');
Route::post('get_agent_by_referral', 'ApiUserController@getAgentByReferral')->name('getAgentByReferral');
//user management




