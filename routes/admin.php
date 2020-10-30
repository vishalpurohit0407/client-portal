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
	//Route::get('/changepass','admin\AdminController@getChangePass')->name('admin.changepass');
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
	Route::get('/user/restore/{uuid?}','admin\UserController@userRestore')->name('admin.user.restore');
	Route::post('/user/list/data/{status?}','admin\UserController@listdata')->name('admin.user.listdata');
	Route::resource('/user', 'admin\UserController', [
	    'names' => [
	        'index' => 'admin.user.list',
	        'create' => 'admin.user.create',
	        'store' => 'admin.user.store',
	        'edit' => 'admin.user.edit',
	        'update' => 'admin.user.update',
	        'destroy' => 'admin.user.destroy',
	        'show' => 'admin.user.show'
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
	Route::get('/selfdiagnosis/pdf/{id}','GuideController@createPDF')->name('selfdiagnosis.pdf.export');


	//Maintenance Module Routes
	Route::get('/maintenance/search','admin\MaintenanceController@search')->name('admin.maintenance.search');
	Route::resource('/maintenance/guide', 'admin\MaintenanceController', [
	    'names' => [
	        'index' => 'admin.maintenance.list',
	        'create' => 'admin.maintenance.create',
	        'store' => 'admin.maintenance.store',
	        'edit' => 'admin.maintenance.edit',
	        'update' => 'admin.maintenance.update',
	        'destroy' => 'admin.maintenance.destroy',
	        'show' => 'admin.maintenance.show'
	    ]
	]);
	Route::post('/maintenance/img-upload','admin\MaintenanceController@img_upload')->name('admin.maintenance.upload');
	Route::post('/maintenance/main-img-upload/{id}','admin\MaintenanceController@mainImgUpload')->name('admin.maintenance.mainupload');
	Route::post('/maintenance/remove/img-upload','admin\MaintenanceController@removeImage')->name('admin.maintenance.remove.image');
	Route::post('/maintenance/remove/step','admin\MaintenanceController@removeStep')->name('admin.maintenance.remove.step');


	//Warranty Extension
	Route::post('/warranty-extension/list/data','admin\WarrantyExtensionController@listdata')->name('admin.warrantyextension.listdata');
	Route::post('/warranty-extension/img-upload/{id}','admin\WarrantyExtensionController@machineImgUpload')->name('admin.warrantyextension.imgupload');
	Route::get('/warranty-extension/history/{unique_key}','admin\WarrantyExtensionController@warrantyExtensionHistory')->name('admin.warrantyextension.history');
	Route::get('/warranty-extension/list/request','admin\WarrantyExtensionController@requestListData')->name('admin.warrantyextension.listreqest');
	Route::post('/warranty-extension/list/request-data','admin\WarrantyExtensionController@requestListData')->name('admin.warrantyextension.listreqest.data');
	Route::resource('/warranty-extension', 'admin\WarrantyExtensionController', [
	    'names' => [
	        'index' => 'admin.warrantyextension.list',
	        'edit' => 'admin.warrantyextension.edit',
	        'update' => 'admin.warrantyextension.update',
	        'show' => 'admin.warrantyextension.show'
	    ]
	]);


	//Flowchart

	Route::delete('/flowchart/remove-node/{id}','admin\FlowchartController@removeNode')->name('admin.flowchart.remove.node');
	Route::post('/flowchart/list/data','admin\FlowchartController@listdata')->name('admin.flowchart.listdata');
	Route::post('/flowchart/node/update','admin\FlowchartController@nodeUpdate')->name('admin.flowchart.node.update');
	Route::resource('/flowchart', 'admin\FlowchartController', [
	    'names' => [
	        'index' => 'admin.flowchart.list',
	        'create' => 'admin.flowchart.create',
	        'store' => 'admin.flowchart.store',
	        'edit' => 'admin.flowchart.edit',
	        'update' => 'admin.flowchart.update',
	        'show' => 'admin.flowchart.show',
	        'destroy' => 'admin.flowchart.destroy',
	    ]
	]);


	// CMS Page Module Routes
	Route::post('/cms_page/list/data','admin\CmsPageController@listdata')->name('admin.cms.page.listdata');
	Route::resource('/cms_page', 'admin\CmsPageController', [
	    'names' => [
	        'index' => 'admin.cms.page.list',
	        'create'=>'admin.cms.page.create',
	        'store'=>'admin.cms.page.store',
	        'edit' => 'admin.cms.page.edit',
	        'update' => 'admin.cms.page.update',
	        'destroy' => 'admin.cms.page.destroy',
	        'show' => 'admin.cms.page.show'
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