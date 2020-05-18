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


Auth::routes();

Route::get('/signin', function () {
    return view('signin');
})->middleware('guest');

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => 'auth', 'prefix' => ''], function () {
    Route::group(['middleware' => 'setLanguage', 'prefix' => ''], function () {

        //common
        Route::get('/', function () {
            return view('index', ['title' =>  __('Dashboard') ]);
        });

        //user management
        Route::get('add_user', 'UserController@index')->name('addUser');
        Route::get('pending_requests', 'UserController@viewPendingAgents')->name('pendingAgents');
        Route::post('save_user', 'UserController@store')->name('saveUser');
        Route::get('view_users', 'UserController@view')->name('viewUser');
        Route::post('edit_users', 'UserController@edit')->name('editUser');
        Route::post('update_user', 'UserController@update')->name('updateUser');
        Route::post('get_user_by_id', 'UserController@getById')->name('getUserById');
        Route::post('approve_agent', 'UserController@approveAgent')->name('approveAgent');
        Route::post('disable_user', 'UserController@disable')->name('disableUser');

        //office management
        Route::get('add_office', 'OfficeController@index')->name('addOffice');
        Route::post('save_office', 'OfficeController@store')->name('saveOffice');
        Route::get('view_offices', 'OfficeController@view')->name('viewOffice');
        Route::post('get_office_by_id', 'OfficeController@getById')->name('getOfficeById');
        Route::post('update_office', 'OfficeController@update')->name('updateOffice');


        //category management
        Route::get('add_category', 'CategoryController@index')->name('addCategory');
        Route::get('view_category', 'CategoryController@view')->name('viewCategory');
        Route::post('get_sub_cat_by_main', 'CategoryController@getSubCatByMain')->name('getSubCatByMain');
        Route::post('get_cat_by_sub', 'CategoryController@getCatBySub')->name('getCatBySub');
        Route::post('save_category', 'CategoryController@store')->name('saveCategory');

        //payment management
        Route::get('add_payment', 'PaymentController@index')->name('addPayment');
        Route::get('view_payment', 'PaymentController@view')->name('viewPayments');
        Route::get('view_outstanding_payment', 'PaymentController@viewOutstanding')->name('viewOutstandingPayments');
        Route::post('get_rental_by_office', 'PaymentController@getRentalByOffice')->name('getRentalByOffice');
        Route::post('save_payment', 'PaymentController@store')->name('savePayment');

        //Election Division
        Route::get('election_division', 'ElectionDivisionController@index')->name('electionDivision');
        Route::post('get_election_division_by_auth', 'ElectionDivisionController@getByAuth')->name('getElectionDivisionsByAuth');
        Route::post('get_election_division_by_district', 'ElectionDivisionController@getByDistrict')->name('getElectionDivisionsByDistrict');
        Route::post('save_election_division', 'ElectionDivisionController@store')->name('saveElectionDivision');
        Route::post('update_election_division', 'ElectionDivisionController@update')->name('updateElectionDivision');
        Route::post('confirm_election_divisions', 'ElectionDivisionController@confirm')->name('confirmElectionDivisions');

        //Polling Booth
        Route::get('polling_booth', 'PollingBoothController@index')->name('pollingBooth');
        Route::post('get_polling_booth_by_auth', 'PollingBoothController@getByAuth')->name('getPollingBoothByAuth');
        Route::post('get_polling_booth_by_election_division', 'PollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
        Route::post('get_polling_booth_by_election_divisions', 'PollingBoothController@getByElectionDivisions')->name('getPollingBoothByElectionDivisions');
        Route::post('save_polling_booth', 'PollingBoothController@store')->name('savePollingBooth');
        Route::post('update_polling_booth', 'PollingBoothController@update')->name('updatePollingBooth');
        Route::post('confirm_polling_booths', 'PollingBoothController@confirm')->name('confirmPollingBooths');

        //Gramasewa Division
        Route::get('gramasewa_division', 'GramasewaDivisionController@index')->name('gramasewaDivision');
        Route::post('get_pgramasewa_division_by_auth', 'GramasewaDivisionController@getByAuth')->name('getGramasewaDivisionByAuth');
        Route::post('get_pgramasewa_division_by_polling_booth', 'GramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
        Route::post('get_gramasewa_division_by_polling_booths', 'GramasewaDivisionController@getByPollingBooths')->name('getGramasewaDivisionByPollingBooths');
        Route::post('save_gramasewa_division', 'GramasewaDivisionController@store')->name('saveGramasewaDivision');
        Route::post('update_gramasewa_division', 'GramasewaDivisionController@update')->name('updateGramasewaDivision');
        Route::post('confirm_gramasewa_divisions', 'GramasewaDivisionController@confirm')->name('confirmGramasewaDivisions');

        //Village
        Route::get('village', 'VillageController@index')->name('village');
        Route::post('get_village_by_auth', 'VillageController@getByAuth')->name('getVillageByAuth');
        Route::post('get_village_by_gramasewa_division', 'VillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');
        Route::post('get_village_by_gramasewa_divisions', 'VillageController@getByGramasewaDivisions')->name('getVillageByGramasewaDivisions');
        Route::post('save_village', 'VillageController@store')->name('saveVillage');
        Route::post('update_village', 'VillageController@update')->name('updateVillage');
        Route::post('confirm_villages', 'VillageController@confirm')->name('confirmVillages');

        //Post
        Route::get('create_post', 'PostController@index')->name('createPost');
        Route::post('save_post', 'PostController@store')->name('savePost');
        Route::get('view_posts', 'PostController@view')->name('viewPosts');
        Route::post('view_posts_admin', 'PostController@showAdmin')->name('viewPostAdmin');

        //Task
        Route::get('assign_task', 'TaskController@index')->name('assignTask');
        Route::get('view_task', 'TaskController@view')->name('viewTasks');
        Route::post('get_task_by_id', 'TaskController@getById')->name('getTaskById');
        Route::post('save_task', 'TaskController@store')->name('saveTask');

        //Post Response
        Route::get('view_comments', 'PostResponseController@viewComments')->name('viewComments');
        Route::post('view_comment', 'PostResponseController@viewComment')->name('viewComment');
        Route::post('save_comment', 'PostResponseController@store')->name('saveComment');
        Route::post('save_comment_attachments', 'PostResponseController@storeAttachments')->name('saveCommentAttachments');
        Route::post('get_comment_by_user_and_post', 'PostResponseController@getCommentByUserAndPost')->name('getCommentByUserAndPost');

        //Staff management
        Route::get('assign_staff', 'StaffController@index')->name('assignStaff');

    });

});



