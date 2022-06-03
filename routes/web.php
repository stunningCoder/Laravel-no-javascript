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
	//@TODO remove before production
	Route::get('/clear-cache', function() {
		Artisan::call('cache:clear');
		Artisan::call('optimize:clear');
		return "Cache is cleared with cache:clear and optimize:clear";
	});
	
	
/*	Route::get('/storage-link', function() {
		Artisan::call('storage:link');
		return "Make disk public and allow files to be shown";
	});*/

Route::name('auth.')->group(function () {
    include 'auth.php';
});

Route::prefix('admin') -> group(function (){
	
    Route::middleware(['admin_panel_access'])->group(function () {
        include 'admin.php';
    });
    
});

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('banned', 'ProfileController@banned')->name('profile.banned');
    Route::middleware(['is_banned']) -> group(function(){
        include 'profile.php';
    });
});

Route::get('/', 'IndexController@home')->name('home');
Route::get('/category/filter', 'IndexController@categoryFilter') -> name('category.filter');
Route::get('/category/select/{categoryId}','CategoryFilter@selectCategory')->name('category.select');
Route::get('/category/deselect/{categoryId}','CategoryFilter@deselectCategory')->name('category.deselect');
Route::get('/category/selectAll','CategoryFilter@selectAll')->name('category.selectAll');
Route::get('/category/deselectAll','CategoryFilter@deselectAll')->name('category.deselectAll');

Route::get('/category/{category}', 'IndexController@category') -> name('category.show');

Route::get('/login', 'IndexController@login')->name('login');
Route::get('/confirmation', 'IndexController@confirmation')->name('confirmation');

Route::get('setview/{list}', 'IndexController@setView') -> name('setview');

// Product routes
Route::get('product/{product}', 'ProductController@show') -> name('product.show');
Route::get('product/{product}/rules', 'ProductController@showRules') -> name('product.rules');
Route::get('product/{product}/feedback', 'ProductController@showFeedback') -> name('product.feedback');
Route::get('product/{product}/delivery', 'ProductController@showDelivery') -> name('product.delivery');
Route::get('product/{product}/vendor', 'ProductController@showVendor') -> name('product.vendor');

// category routes
Route::get('category/{category}', 'IndexController@category') -> name('category.show');


// vendor routes
Route::get('vendor/{user}', 'IndexController@vendor')->name('vendor.show');

Route::get('vendor/{user}/feedback', 'IndexController@vendorsFeedbacks') -> name('vendor.show.feedback');

Route::post('search','SearchController@search')->name('search');
Route::get('search','SearchController@searchShow')->name('search.show');
