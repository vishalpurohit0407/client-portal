<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');
Auth::routes(['verify' => true]);



Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	// Self Diagnosis Module Routes
	Route::get('/guide/pdf/{id}','GuideController@createPDF')->name('selfdiagnosis.pdf.export');
	Route::get('/guide/complete/{id}','GuideController@completedGuide')->name('user.complete.guide');
	Route::get('/guide/search','GuideController@search')->name('user.selfdiagnosis.search');
	Route::resource('/guide', 'GuideController', [
	    'names' => [
	        'index' => 'user.selfdiagnosis.list',
	        'show' => 'user.selfdiagnosis.show'
	    ]
	]);

	Route::post('/support-ticket/list/data','SupportTicketController@listdata')->name('user.support.ticket.listdata');
	Route::post('/support-ticket/send-comment','SupportTicketController@sendComment')->name('user.support.ticket.sendcomment');
	Route::post('/support-ticket/getcomment','SupportTicketController@getComment')->name('user.support.ticket.getcomment');
	Route::resource('/support-ticket', 'SupportTicketController', [
	    'names' => [
	        'index' => 'user.support.ticket.list',
	        'store' => 'user.support.ticket.store',
	    ]
	]);

	
});
Route::get('/{url_slug}','PagepreviewController@pagepreview')->name('cms.pagepreview');
