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

//Route::get('/linkstorage', function () {
//    Artisan::call('storage:link')
//});
Route::get('test_sms', function (){
    $client = new \GuzzleHttp\Client();
    $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=testSms-Harsha&dst=0717275539");
    return json_decode($res->getBody(), true);
})->name('testSms');

Auth::routes();

Route::get('/signin', function () {
    return view('signin');
})->name('signin')->middleware('guest');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('privacy', function (){
    return view('profile.privacy');
})->name('privacy');


Route::group(['middleware' => 'auth', 'prefix' => ''], function () {
    Route::group(['middleware' => 'isActive', 'prefix' => ''], function () {
        Route::group(['middleware' => 'setLanguage', 'prefix' => ''], function () {

            //common
            Route::get('/', 'DashboardController@index')->name('dashboard');

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
            Route::post('auto_approve_member', 'UserController@autoMember')->name('autoApproveMember');
            Route::post('auto_approve_agent', 'UserController@autoAgent')->name('autoApproveAgent');

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
            Route::post('delete_election_division', 'ElectionDivisionController@deleteRecord')->name('deleteElectionDivision');

            //Polling Booth
            Route::get('member_division', 'PollingBoothController@index')->name('pollingBooth');
            Route::post('get_polling_booth_by_auth', 'PollingBoothController@getByAuth')->name('getPollingBoothByAuth');
            Route::post('get_polling_booth_by_election_division', 'PollingBoothController@getByElectionDivision')->name('getPollingBoothByElectionDivision');
            Route::post('get_polling_booth_by_election_divisions', 'PollingBoothController@getByElectionDivisions')->name('getPollingBoothByElectionDivisions');
            Route::post('save_polling_booth', 'PollingBoothController@store')->name('savePollingBooth');
            Route::post('update_polling_booth', 'PollingBoothController@update')->name('updatePollingBooth');
            Route::post('confirm_polling_booths', 'PollingBoothController@confirm')->name('confirmPollingBooths');
            Route::post('delete_polling_booth', 'PollingBoothController@deleteRecord')->name('deletePollingBooth');

            //Gramasewa Division
            Route::get('gramasewa_division', 'GramasewaDivisionController@index')->name('gramasewaDivision');
            Route::post('get_pgramasewa_division_by_auth', 'GramasewaDivisionController@getByAuth')->name('getGramasewaDivisionByAuth');
            Route::post('get_pgramasewa_division_by_polling_booth', 'GramasewaDivisionController@getByPollingBooth')->name('getGramasewaDivisionByPollingBooth');
            Route::post('get_gramasewa_division_by_polling_booths', 'GramasewaDivisionController@getByPollingBooths')->name('getGramasewaDivisionByPollingBooths');
            Route::post('save_gramasewa_division', 'GramasewaDivisionController@store')->name('saveGramasewaDivision');
            Route::post('update_gramasewa_division', 'GramasewaDivisionController@update')->name('updateGramasewaDivision');
            Route::post('confirm_gramasewa_divisions', 'GramasewaDivisionController@confirm')->name('confirmGramasewaDivisions');
            Route::post('delete_gramasewa_division', 'GramasewaDivisionController@deleteRecord')->name('deleteGramasewaDivision');
            Route::post('get_gramasewa_by_secretariat', 'GramasewaDivisionController@getBySecretariat')->name('getGramasewaBySecretariat');
            Route::post('get_gramasewa_by_council', 'GramasewaDivisionController@getByCouncil')->name('getGramasewaByCouncil');
            Route::post('get_unassigned_gramasewa_divisions', 'GramasewaDivisionController@getUnAssigned')->name('getUnAssignedGramasewaDivisions');
            Route::post('get_noncouncilled_gramasewa_divisions', 'GramasewaDivisionController@getUnCouncilled')->name('getNonCouncilledGramasewaDivisions');

            //Village
            Route::get('village', 'VillageController@index')->name('village');
            Route::post('get_village_by_auth', 'VillageController@getByAuth')->name('getVillageByAuth');
            Route::post('get_village_by_gramasewa_division', 'VillageController@getByGramasewaDivision')->name('getVillageByGramasewaDivision');
            Route::post('get_village_by_gramasewa_divisions', 'VillageController@getByGramasewaDivisions')->name('getVillageByGramasewaDivisions');
            Route::post('save_village', 'VillageController@store')->name('saveVillage');
            Route::post('update_village', 'VillageController@update')->name('updateVillage');
            Route::post('confirm_villages', 'VillageController@confirm')->name('confirmVillages');
            Route::post('delete_village', 'VillageController@deleteRecord')->name('deleteVillage');

            //Post
            Route::get('create_post', 'PostController@index')->name('createPost');
            Route::post('save_post', 'PostController@store')->name('savePost');
            Route::get('view_posts', 'PostController@view')->name('viewPosts');
            Route::post('view_posts_admin', 'PostController@showAdmin')->name('viewPostAdmin');
            Route::get('view_posts_by_category', 'PostController@viewByCategory')->name('viewPostsByCategory');

            //Task
            Route::get('assign_budget', 'TaskController@index')->name('assignTask');
            Route::get('default_budget', 'TaskController@createDefault')->name('createDefaultTask');
            Route::get('view_budget', 'TaskController@view')->name('viewTasks');
            Route::post('get_budget_by_id', 'TaskController@getById')->name('getTaskById');
            Route::post('save_budget', 'TaskController@storeDefault')->name('saveTask');
            Route::post('deactivate_budget', 'TaskController@deactivate')->name('deactivateTask');
            Route::post('activate_budget', 'TaskController@activate')->name('activateTask');
            Route::post('view_budget', 'TaskController@view')->name('viewBudget');
            Route::post('view_budget_by_type', 'TaskController@viewByType')->name('viewBudgetByType');
            Route::post('is_default_budget_created', 'TaskController@isDefaultBudgetCreated')->name('isDefaultBudgetCreated');

            //Post Response
            Route::post('view_comments', 'PostResponseController@viewComments')->name('viewComments');
            Route::post('view_user_comments', 'PostResponseController@viewUserComments')->name('viewUserComments');
            Route::post('save_comment', 'PostResponseController@store')->name('saveComment');
            Route::post('save_comment_attachments', 'PostResponseController@storeAttachments')->name('saveCommentAttachments');
            Route::post('get_comment_by_user_and_post', 'PostResponseController@getCommentByUserAndPost')->name('getCommentByUserAndPost');
            Route::post('publish_management_comment', 'PostResponseController@publishManagementComment')->name('publishManagementComment');
            Route::post('reject_management_comment', 'PostResponseController@rejectManagementComment')->name('rejectManagementComment');
            Route::post('pending_responses', 'PostResponseController@pendingResponses')->name('pendingResponses');

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
            Route::post('report_category_data', 'ReportController@getCategoryData')->name('report-category_data');

            //Generic Reports
            Route::get('agents_report', 'GenericReportController@agents')->name('report-agents');
            Route::get('members_report', 'GenericReportController@members')->name('report-members');
            Route::get('age_report', 'GenericReportController@age')->name('report-age');
            Route::post('age_report', 'GenericReportController@ageChart')->name('report-age');
            Route::get('education_qualification_report', 'GenericReportController@education')->name('report-education');
            Route::post('education_qualification_report', 'GenericReportController@educationChart')->name('report-education');
            Route::get('income_report', 'GenericReportController@income')->name('report-income');
            Route::post('income_report', 'GenericReportController@incomeChart')->name('report-income');
            Route::get('career_report', 'GenericReportController@career')->name('report-career');
            Route::post('career_report', 'GenericReportController@careerChart')->name('report-career');
            Route::get('religion_report', 'GenericReportController@religion')->name('report-religion');
            Route::post('religion_report', 'GenericReportController@religionChart')->name('report-religion');
            Route::get('ethnicity_report', 'GenericReportController@ethnicity')->name('report-ethnicity');
            Route::post('ethnicity_report', 'GenericReportController@ethnicityChart')->name('report-ethnicity');
            Route::get('voters_report', 'GenericReportController@voters')->name('report-voters');
            Route::post('voters_report', 'GenericReportController@votersChart')->name('report-voters');

            //Attendance management
            Route::get('create_event', 'EventController@index')->name('create-event');
            Route::get('view_events', 'EventController@view')->name('view-events');
            Route::post('save_event', 'EventController@store')->name('save-event');

            //Dashboard
            Route::post('dashboard-getStorage', 'DashboardController@getStorage')->name('dashboard-getStorage');


            //Divisional secretariat management
            Route::get('divisional_secretariat', 'DivisionalSecretariatController@index')->name('divisionalSecretariat');
            Route::post('save_divisional_secretariat', 'DivisionalSecretariatController@store')->name('saveDivisionalSecretariat');
            Route::post('get_divisional_secretariat_by_auth', 'DivisionalSecretariatController@getByAuth')->name('getDivisionalSecretariatByAuth');
            Route::post('update_divisional_secretariat', 'DivisionalSecretariatController@update')->name('updateDivisionalSecretariat');
            Route::post('delete_divisional_secretariat', 'DivisionalSecretariatController@deleteRecord')->name('deleteDivisionalSecretariat');
            Route::get('divisional_secretariat_view', 'DivisionalSecretariatController@view')->name('divisionalSecretariat-view');
            Route::post('assign_gramasewa_divisions', 'DivisionalSecretariatController@assignDivisions')->name('assignedGramasewaDivisions');
            Route::post('confirm_divisional_secretariat', 'DivisionalSecretariatController@confirm')->name('confirmDivisionalSecretariat');

            //Council management
            Route::get('council', 'CouncilController@index')->name('council');
            Route::post('save_council', 'CouncilController@store')->name('saveCouncil');
            Route::post('get_council_by_auth', 'CouncilController@getByAuth')->name('getCouncilByAuth');
            Route::post('update_council', 'CouncilController@update')->name('updateCouncil');
            Route::post('delete_council', 'CouncilController@deleteRecord')->name('deleteCouncil');
            Route::get('council_view', 'CouncilController@view')->name('council-view');
            Route::post('assigned_gramasewa_divisions_council', 'CouncilController@assignDivisions')->name('assignedGramasewaCouncil');
            Route::post('confirm_council', 'CouncilController@confirm')->name('confirmCouncil');


            //SMS
            Route::get('welcome_message', 'SmsController@index')->name('welcomeMessage');
            Route::get('sms_configuration', 'SmsController@config')->name('smsConfiguration');
            Route::post('updateSmsLimit', 'SmsController@limit')->name('updateSmsLimit');
            Route::post('saveWelcomeSms', 'SmsController@saveWelcome')->name('saveWelcomeSms');
            Route::get('create_sms', 'SmsController@create')->name('createSms');
            Route::post('create_sms', 'SmsController@send')->name('createSms');
            Route::post('get_number_of_users', 'SmsController@getNumberOfReceivers')->name('getNumberOfReceivers');

            //setting
            Route::get('app_version', 'GeneralController@appVersion')->name('appVersion');
            Route::post('app_version', 'GeneralController@storeAppVersion')->name('storeAppVersion');


        });
    });

});



