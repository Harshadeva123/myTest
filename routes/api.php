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

    Route::post('logout', function () {
        $user = \Illuminate\Support\Facades\Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => 'Logged out', 'statusCode' => 0]);
    })->name('logout');

    ////Response management
    Route::post('view_comments', 'Api\ApiPostResponseController@viewComments')->name('viewComments');
    Route::post('save_comment', 'Api\ApiPostResponseController@store')->name('saveComment');
    Route::post('save_comment_attachments', 'Api\ApiPostResponseController@storeAttachments')->name('saveCommentAttachments');
    //user management

    //Post management
    Route::post('get_posts', 'Api\ApiPostController@getPosts')->name('getPosts');
    Route::post('view_post', 'Api\ApiPostController@viewPost')->name('viewPost');
    //Post management end

    //Agent Switch
    Route::post('add_agent', 'Api\ApiUserController@addAgent')->name('addAgent');
    Route::post('get_agents', 'Api\ApiUserController@getAgents')->name('getAgents');
    Route::post('set_agent', 'Api\ApiUserController@setAgents')->name('setAgents');
    //Agent Switch

    ////Get members by agent
    Route::post('get_members', 'Api\ApiUserController@getMembers')->name('getPendingMembers');
    Route::post('approve_member', 'Api\ApiUserController@approveMember')->name('approveMember');
    Route::post('activate_member', 'Api\ApiUserController@activateMember')->name('activateMember');
    Route::post('deactivate_member', 'Api\ApiUserController@deactivateMember')->name('deactivateMember');
    //Get members by agent end

    //Response panel
    Route::post('save_response', 'Api\ApiResponsePanelController@store')->name('saveResponse');
    //Response panel end

    //Task
    Route::post('get_tasks', 'Api\ApiTaskController@index')->name('getTask');

    //Save SMS member
    Route::post('get_registration_form', 'Api\ApiRegistrationController@getRegistrationForm')->name('getRegistrationForm');
    Route::post('register_member', 'Api\ApiRegistrationController@storeSmsUser')->name('registerMember');
    //Save SMS member end

    //Voters
    Route::post('store_voters_count', 'Api\ApiInitialSetupController@storeVotersCount')->name('storeVotersCount');
    //Voters end

    //Profile
    Route::post('profile', 'Api\ApiProfileController@index')->name('profile');
    //Profile end


    //agent initial setup
    Route::post('mark_village_location', 'Api\ApiInitialSetupController@markVillage')->name('markVilalge');
    //agent initial setup end

    //get app version
    Route::post('app_meta_data', 'GeneralController@getAppVersion')->name('getAppVersion');
    //get app version end
});

Route::post('get_polling_booths_by_election_division', 'Api\ApiPollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
Route::post('get_gramasewa_divisions_by_polling_booth', 'Api\ApiGramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
Route::post('get_villages_by_gramasewa_division', 'Api\ApiVillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');
Route::post('get_careers_by_job_sector', 'Api\ApiRegistrationController@getByJobSector')->name('getCareersByJobSector');


//user management
Route::post('save_user', 'Api\ApiRegistrationController@store')->name('saveUser');
Route::post('login', 'Api\ApiUserController@login')->name('loginUser');
Route::post('get_office_by_referral', 'Api\ApiUserController@getOfficeAdminByReferral')->name('getOfficeByReferral');
Route::post('get_agent_by_referral', 'Api\ApiUserController@getAgentByReferral')->name('getAgentByReferral');
//user management







