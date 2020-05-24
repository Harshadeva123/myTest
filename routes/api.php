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

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('auth_test', function () {
        return \Illuminate\Support\Facades\Auth::user()->fName . ' api authenticated';
    })->name('authTest');


////Response management
    Route::post('view_comments', 'Api\ApiPostResponseController@viewComments')->name('viewComments');
    Route::post('save_comment', 'Api\ApiPostResponseController@store')->name('saveComment');
    Route::post('save_comment_attachments', 'Api\ApiPostResponseController@storeAttachments')->name('saveCommentAttachments');
//user management

//Post management
    Route::post('get_posts', 'Api\ApiPostController@getPosts')->name('getPosts');
    Route::post('view_post', 'Api\ApiPostController@viewPost')->name('viewPost');
//Post management end

});

Route::post('get_polling_booths_by_election_division', 'Api\ApiPollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
Route::post('get_gramasewa_divisions_by_polling_booth', 'Api\ApiGramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
Route::post('get_villages_by_gramasewa_division', 'Api\ApiVillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');


//user management
Route::post('save_user', 'Api\ApiUserController@store')->name('saveUser');
Route::post('login', 'Api\ApiUserController@login')->name('loginUser');
Route::post('get_office_by_referral', 'Api\ApiUserController@getOfficeAdminByReferral')->name('getOfficeByReferral');
Route::post('get_agent_by_referral', 'Api\ApiUserController@getAgentByReferral')->name('getAgentByReferral');
//user management





