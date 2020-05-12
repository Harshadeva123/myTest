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

        //office management
        Route::get('add_office', 'OfficeController@index')->name('addOffice');
        Route::post('save_office', 'OfficeController@store')->name('saveOffice');
        Route::get('view_offices', 'OfficeController@view')->name('viewOffice');

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
        Route::post('save_election_division', 'ElectionDivisionController@store')->name('saveElectionDivision');

        //Polling Booth
        Route::get('polling_booth', 'PollingBoothController@index')->name('pollingBooth');
        Route::post('get_polling_booth_by_auth', 'PollingBoothController@getByAuth')->name('getPollingBoothByAuth');
        Route::post('save_polling_booth', 'PollingBoothController@store')->name('savePollingBooth');

        //Gramasewa Division
        Route::get('gramasewa_division', 'GramasewaDivisionController@index')->name('gramasewaDivision');
        Route::post('get_pgramasewa_division_by_auth', 'GramasewaDivisionController@getByAuth')->name('getGramasewaDivisionByAuth');
        Route::post('save_gramasewa_division', 'GramasewaDivisionController@store')->name('saveGramasewaDivision');
    });

});



