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
Route::get('/password/reset/{token}', 'Adminauth\ResetPasswordController@showResetPasswordForm')->name('admin.password.token');
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
	Route::post('/category/list/data','admin\CategoryController@listdata')->name('category.listdata');
	Route::post('/category/deletebytag','admin\CategoryController@orgTagDelete')->name('category.tagdelete');
	Route::resource('/category', 'admin\CategoryController', [
	    'names' => [
	        'index' => 'category.list',
	        'edit' => 'category.edit',
	        'update' => 'category.update',
	        'destroy' => 'category.destroy',
	        'show' => 'category.show'
	    ]
	]);

	// Document Type Module Routes
	Route::post('/typeofdocument/list/data','admin\TypeOfDocumentController@listdata')->name('documenttype.listdata');
	Route::resource('/typeofdocument', 'admin\TypeOfDocumentController', [
	    'names' => [
	        'index' => 'documenttype.list',
	        'create' => 'documenttype.create',
	        'store' => 'documenttype.store',
	        'edit' => 'documenttype.edit',
	        'update' => 'documenttype.update',
	        'destroy' => 'documenttype.destroy',
	        'show' => 'documenttype.show'
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

	// Organization Module Routes
	Route::get('/organization/pending','admin\OrganizationController@pendingUser')->name('organization.pending.list');
	Route::post('/organization/pending/list/data','admin\OrganizationController@pendingUserlistdata')->name('organization.pending.listdata');
	Route::get('/organization/approve/{id}/{status?}','admin\OrganizationController@approveOrDecline')->name('organization.approval');
	Route::get('/organization/deleted','admin\OrganizationController@deletedUser')->name('organization.deleted.list');
	Route::post('/organization/deleted/list/data','admin\OrganizationController@deletedUserlistdata')->name('organization.deleted.listdata');
	Route::get('/organization/restore/{uuid?}','admin\OrganizationController@userRestore')->name('organization.restore');
	Route::post('/organization/list/data','admin\OrganizationController@listdata')->name('organization.listdata');
	Route::resource('/organization/user', 'admin\OrganizationController', [
	    'names' => [
	        'index' => 'organization.list',
	        'create' => 'organization.create',
	        'store' => 'organization.store',
	        'edit' => 'organization.edit',
	        'update' => 'organization.update',
	        'destroy' => 'organization.destroy',
	        'show' => 'organization.show'
	    ]
	]);

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

	// Documentdetail Module Routes
	Route::post('/document/list/data','admin\DocumentdetailController@listdata')->name('document.listdata');
	Route::resource('/document', 'admin\DocumentdetailController', [
	    'names' => [
	        'index' => 'document.list',
	        'create' => 'document.create',
	        'store' => 'document.store',
	        'edit' => 'document.edit',
	        'update' => 'document.update',
	        'destroy' => 'document.destroy',
	        'show' => 'document.show'
	    ]
	]);

	// Complain Module Routes
	Route::post('/complain/list/data','admin\ComplainController@listdata')->name('complain.listdata');
	Route::resource('/complain', 'admin\ComplainController', [
	    'names' => [
	        'index' => 'complain.list',
	        'create' => 'complain.create',
	        'store' => 'complain.store',
	        'edit' => 'complain.edit',
	        'update' => 'admin.complain.update',
	        'destroy' => 'complain.destroy',
	        'show' => 'complain.show'
	    ]
	]);

	// Document Group Assigned Module Routes
	Route::post('/document/group/assigned/list/data','admin\DocumentgroupassignController@listdata')->name('document.group.assigned.listdata');
	Route::post('/documentgroupassign/deletebytag','admin\DocumentgroupassignController@typeTagDelete')->name('documentgrouptype.tagdelete');
	Route::resource('/documentgroupassign', 'admin\DocumentgroupassignController', [
	    'names' => [
	        'index' => 'document.group.assigned.list',
	        'create' => 'document.group.assigned.create',
	        'store' => 'document.group.assigned.store',
	        'edit' => 'document.group.assigned.edit',
	        'update' => 'document.group.assigned.update',
	        'destroy' => 'document.group.assigned.destroy',
	        'show' => 'document.group.assigned.show'
	    ]
	]);


});


//Clear Cache facade value:
Route::get('/reset-app', function() {
	Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return '<h1>Suucess</h1>';
});

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');