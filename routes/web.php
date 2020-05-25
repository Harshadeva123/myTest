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
        Route::post('enable_user', 'UserController@enable')->name('enableUser');

        //office management
        Route::get('add_office', 'OfficeController@index')->name('addOffice');
        Route::post('save_office', 'OfficeController@store')->name('saveOffice');
        Route::get('view_offices', 'OfficeController@view')->name('viewOffice');
        Route::post('get_office_by_id', 'OfficeController@getById')->name('getOfficeById');
        Route::post('update_office', 'OfficeController@update')->name('updateOffice');
        Route::post('disable_office', 'OfficeController@disable')->name('disableOffice');
        Route::post('enable_office', 'OfficeController@enable')->name('enableOffice');

        //category management
        Route::get('add_category', 'CategoryController@index')->name('addCategory');
        Route::get('view_category', 'CategoryController@view')->name('viewCategory');
        Route::post('get_sub_cat_by_main', 'CategoryController@getSubCatByMain')->name('getSubCatByMain');
        Route::post('load_category_recent', 'CategoryController@loadRecent')->name('loadCategoryRecent');
        Route::post('view_categories', 'CategoryController@getCatBySub')->name('viewCategories');
        Route::post('save_category', 'CategoryController@store')->name('saveCategory');
        Route::post('update_category', 'CategoryController@update')->name('updateCategory');
        Route::post('deactivate_category', 'CategoryController@deactivate')->name('deactivateCategory');
        Route::post('activate_category', 'CategoryController@activate')->name('activateCategory');

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
        Route::get('view_posts_by_category', 'PostController@viewByCategory')->name('viewPostsByCategory');

        //Task
        Route::get('assign_task', 'TaskController@index')->name('assignTask');
        Route::get('view_task', 'TaskController@view')->name('viewTasks');
        Route::post('get_task_by_id', 'TaskController@getById')->name('getTaskById');
        Route::post('save_task', 'TaskController@store')->name('saveTask');
        Route::post('deactivate_task', 'TaskController@deactivate')->name('deactivateTask');
        Route::post('activate_task', 'TaskController@activate')->name('activateTask');

        //Post Response
        Route::post('view_comments', 'PostResponseController@viewComments')->name('viewComments');
        Route::post('view_user_comments', 'PostResponseController@viewUserComments')->name('viewUserComments');
        Route::post('save_comment', 'PostResponseController@store')->name('saveComment');
        Route::post('save_comment_attachments', 'PostResponseController@storeAttachments')->name('saveCommentAttachments');
        Route::post('get_comment_by_user_and_post', 'PostResponseController@getCommentByUserAndPost')->name('getCommentByUserAndPost');

        //Staff management
        Route::get('assign_staff', 'StaffController@index')->name('assignStaff');
        Route::post('assign_staff', 'StaffController@store')->name('assignStaff');
        Route::post('view_assigned_division', 'StaffController@viewAssignedDivision')->name('viewAssignedDivision');

        //Analysis management
        Route::get('pending_response', 'ResponseAnalysisController@index')->name('pendingResponse');
        Route::post('analyse_response', 'ResponseAnalysisController@analyse')->name('analyseResponse');
        Route::post('save_response_analysis', 'ResponseAnalysisController@store')->name('saveResponseAnalysis');

        //Direct Messages
        Route::get('direct_messages', 'DirectMessageController@index')->name('directMessages');
        Route::post('load_message_users', 'DirectMessageController@loadUsers')->name('loadMessageUsers');
        Route::post('get_messages_by_user', 'DirectMessageController@getByUser')->name('getMessagesByUser');
        Route::post('save_message_attachments', 'DirectMessageController@storeAttachments')->name('saveMessageAttachments');
        Route::post('save_message', 'DirectMessageController@store')->name('saveMessage');
        Route::post('get_message_by_user', 'DirectMessageController@getMessageByUser')->name('getMessageByUser');

        //Report management
        Route::get('report_category_wise', 'ReportController@categoryWise')->name('report-categoryWise');
        Route::post('report_category_wise', 'ReportController@categoryWiseChart')->name('report-categoryWise');
        Route::get('report_location_wise', 'ReportController@locationWise')->name('report-locationWise');
        Route::post('report_location_wise', 'ReportController@locationWiseChart')->name('report-locationWise');

        //Generic Reports
        Route::get('agents_report', 'GenericReportController@agents')->name('report-agents');
        Route::get('members_report', 'GenericReportController@members')->name('report-members');
        Route::get('age_report', 'GenericReportController@age')->name('report-age');
        Route::post('age_report', 'GenericReportController@ageChart')->name('report-age');

    });

});



