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
        Route::post('save_user', 'UserController@store')->name('saveUser');
        Route::get('view_users', 'UserController@view')->name('viewUser');
        Route::post('edit_users', 'UserController@edit')->name('editUser');
        Route::post('update_user', 'UserController@update')->name('updateUser');

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
        Route::post('get_rental_by_office', 'PaymentController@getRentalByOffice')->name('getRentalByOffice');
        Route::post('save_payment', 'PaymentController@store')->name('savePayment');
    });

});



