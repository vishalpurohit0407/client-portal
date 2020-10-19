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
	Route::get('/guide/flowchart/{flowchart_id}/{guide_id}','GuideController@flowChart')->name('user.flowchart');
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

	//Maintenance Module Routes
	Route::get('/maintenance/search','MaintenanceController@search')->name('user.maintenance.search');
	Route::resource('/maintenance/guide', 'MaintenanceController', [
	    'names' => [
	        'index' => 'user.maintenance.list',
	        'show' => 'user.maintenance.show'
	    ]
	]);

	//Warranty Extension Module Routes
	Route::post('/warranty_extension/user-img-upload/{id}','WarrantyExtensionController@userImgUpload')->name('user.warranty_extension.imgupload');
	Route::get('/warranty_extension/history/{unique_key}','WarrantyExtensionController@warrantyExtensionHistory')->name('user.warranty_extension.history');
	Route::post('/warranty_extension/list/data','WarrantyExtensionController@listdata')->name('user.warranty_extension.listdata');
	Route::get('/warranty_extension/list/request','WarrantyExtensionController@requestListData')->name('user.warranty_extension.listreqest');
	Route::post('/warranty_extension/list/request-data','WarrantyExtensionController@requestListData')->name('user.warranty_extension.listreqest.data');

	Route::post('/warranty_extension/history/saverequest','WarrantyExtensionController@saveRequest')->name('user.warranty_extension.saverequest');
	
	Route::resource('/warranty_extension', 'WarrantyExtensionController', [
	    'names' => [
	        'index' => 'user.warranty_extension.list',
	        'create' => 'user.warranty_extension.create',
	        'store' => 'user.warranty_extension.store',
	        'edit' => 'user.warranty_extension.edit',
	        'update' => 'user.warranty_extension.update',
	        'show' => 'user.warranty_extension.show'
	    ]
	]);

});
Route::get('/{url_slug}','PagepreviewController@pagepreview')->name('cms.pagepreview');
