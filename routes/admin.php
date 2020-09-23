<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return 'admin';
// });

// Add new routes for admin login, forgot and reset password
Route::get('/','Adminauth\LoginController@showLoginForm');
Route::get('/login','Adminauth\LoginController@showLoginForm')->name('admin.login');
Route::post('/login','Adminauth\LoginController@adminLogin')->name('admin.login');
Route::get('/forgotpassword','Adminauth\ForgotPasswordController@showForgotPasswordForm')->name('admin.forgotpassword');
Route::post('/password/email','Adminauth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.eamil');
Route::get('/password/reset/{token}/{email}', 'Adminauth\ResetPasswordController@showResetPasswordForm')->name('admin.password.token');
Route::post('/password/reset', 'Adminauth\ResetPasswordController@reset')->name('admin.password.reset');

// Add new route for 'admin' middleware
Route::group(['middleware' => ['admin']],function(){
	
	// Logout routes
	//Route::get('/','admin\AdminController@index');
    Route::post('/logout','Adminauth\LoginController@logout');
	Route::get('/logout','Adminauth\LoginController@logout')->name('admin.logout');

	// Change password routes
	Route::get('/changepass','admin\AdminController@getChangePass')->name('admin.changepass');
	Route::post('/changepass','admin\AdminController@changePass')->name('admin.updatechangepass');

	// Change Profile routes
	Route::get('/editprofile','admin\AdminController@profile')->name('admin.editprofile');
	Route::post('/updateprofile','admin\AdminController@postprofile')->name('admin.updateprofile');

	// Super Admin Module Routes
	Route::get('/dashboard','admin\AdminController@index')->name('admin.dashboard');

	// Category Module Routes
	Route::post('/category/list/data','admin\CategoryController@listdata')->name('admin.category.listdata');
	Route::resource('/category', 'admin\CategoryController', [
	    'names' => [

	        'index' => 'admin.category.list',
	        'edit' => 'admin.category.edit',
	        'create' => 'admin.category.create',
	        'store' => 'admin.category.store',
	        'update' => 'admin.category.update',
	        'destroy' => 'admin.category.destroy',
	    ]
	]);

	// User Module Routes
	Route::get('/user/document/details/{document_id}','admin\UserController@userDocumentDetail')->name('user.document.details');
	Route::get('/user/document/delete/{document_id}','admin\UserController@userDocumentDelete')->name('user.document.delete');
	Route::get('/user/document/verify/{id}/{flag?}','admin\UserController@userDocumentVerify')->name('user.document.verify');
	Route::get('/user/deleted','admin\UserController@deletedUser')->name('user.deleted.list');
	Route::post('/user/deleted/list/data','admin\UserController@deletedUserlistdata')->name('user.deleted.listdata');
	Route::get('/user/restore/{uuid?}','admin\UserController@userRestore')->name('user.restore');
	Route::post('/user/list/data/{status?}','admin\UserController@listdata')->name('user.listdata');
	Route::resource('/user', 'admin\UserController', [
	    'names' => [
	        'index' => 'user.list',
	        'edit' => 'user.edit',
	        'update' => 'user.update',
	        'destroy' => 'user.destroy',
	        'show' => 'user.show'
	    ]
	]);

	// Self Diagnosis Module Routes
	Route::get('/guide/search','admin\GuideController@search')->name('admin.selfdiagnosis.search');
	Route::resource('/guide', 'admin\GuideController', [
	    'names' => [
	        'index' => 'admin.selfdiagnosis.list',
	        'create' => 'admin.selfdiagnosis.create',
	        'store' => 'admin.selfdiagnosis.store',
	        'edit' => 'admin.selfdiagnosis.edit',
	        'update' => 'admin.selfdiagnosis.update',
	        'destroy' => 'admin.selfdiagnosis.destroy',
	        'show' => 'admin.selfdiagnosis.show'
	    ]
	]);
	Route::post('/selfdiagnosis/img-upload','admin\GuideController@img_upload')->name('admin.selfdiagnosis.upload');
	Route::post('/selfdiagnosis/main-img-upload/{id}','admin\GuideController@mainImgUpload')->name('admin.selfdiagnosis.mainupload');
	Route::post('/selfdiagnosis/remove/img-upload','admin\GuideController@removeImage')->name('admin.selfdiagnosis.remove.image');
	Route::post('/selfdiagnosis/remove/step','admin\GuideController@removeStep')->name('admin.selfdiagnosis.remove.step');

	// Organization Module Routes
	Route::post('/cms/pages/list/data','admin\CmspagsController@listdata')->name('cms.pages.listdata');
	Route::resource('/cmspags', 'admin\CmspagsController', [
	    'names' => [
	        'index' => 'cms.pages.list',
	        'edit' => 'cms.pages.edit',
	        'update' => 'cms.pages.update',
	        'destroy' => 'cms.pages.destroy',
	        'show' => 'cms.pages.show'
	    ]
	]);
});

//ckeditor file upload
Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');

//Clear Cache facade value:
Route::get('/reset-app', function() {
	Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return '<h1>Suucess</h1>';
});

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');