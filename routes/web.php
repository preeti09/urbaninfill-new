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

Route::get('/', 'homeController@home');
Route::get('/hpl2', 'homeController@homehpl2');
Route::get('/typesearch', 'homeController@typesearch');
Route::get('/location','homeController@location');
Route::get('/VacantProperties','homeController@VacantProperties');
Route::get('/getOwnerDetail/{line1}/{line2}','AjaxController@ExtendedDetail');
Route::get('/getpoidata/{line}/{zip}','AjaxController@PropertypoiData');
Route::get("savedata","AjaxController@saveProperty");
Route::get("savedproperties","HomeController@ShowSave");
Route::get("person","HomeController@person");
Route::get("deletesaveproperty/{id}",'HomeController@deletesaveproperty');
Route::get("propertymail/{fullname}/{fulladdress}/{emailaddress}/{template?}",'AjaxController@showmail');

Route::get('geocode/{address}','AjaxController@geocode_public');
// Ajax Responses
Route::get('/test','AjaxController@test');
Route::get("allpropertiesList",'AjaxController@allpropertiesList');
Route::get("getpropertiesByTypeList",'AjaxController@getpropertiesByTypeList');
Route::get("getTotalPages",'AjaxController@getTotalPages');
Route::get("getTotalTypePages",'AjaxController@getTotalTypePages');
Route::post('getzipdata', 'AjaxController@getzipResponse');
Route::post('getPropertyResponse','AjaxController@getPropertyResponse');
Route::get('/getHouseInventry','AjaxController@getHouseInventry');
Route::get("allVacantpropertiesList",'AjaxController@allVacantpropertiesList');
Route::post("propertymail",'AjaxController@sendPropertymail');

Route::get("personlist",'AjaxController@personlist');
Route::get("perosndetail/{fname}/{lname}/{index}",'AjaxController@persondetail');

Route::get('/school','AjaxController@school');
Route::post('/sendMail','AjaxController@SendEmail');
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('922197219', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('922197219', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('Lot');
Route::get("/logout","HomeController@logout");

