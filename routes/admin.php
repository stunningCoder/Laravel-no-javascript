<?php
	
	
//	Route::get('/reindex-products', function () {
//		Artisan::call('elasticsearch:reindex-all-products');
//	});
	
	Route::get('index', 'Admin\AdminController@index')->name('admin.index');

// Categories routes
	Route::get('categories', 'Admin\AdminController@categories')->name('admin.categories');
	Route::post('category/new', 'Admin\AdminController@newCategory')->name('admin.categories.new');
	Route::get('category/delete/{id}', 'Admin\AdminController@removeCategory')->name('admin.categories.delete');
	Route::get('category/{id}', 'Admin\AdminController@editCategoryShow')->name('admin.categories.show');
	Route::post('category/{id}', 'Admin\AdminController@editCategory')->name('admin.categories.edit');

// Mass message routes
	Route::get('message', 'Admin\AdminController@massMessage')->name('admin.messages.mass');
	Route::post('message/send', 'Admin\AdminController@sendMessage')->name('admin.messages.send');

// User routes
	Route::get('users', 'Admin\UserController@users')->name('admin.users');
	Route::post('users/query', 'Admin\UserController@usersPost')->name('admin.users.query');
	Route::get('users/{user?}', 'Admin\UserController@userView')->name('admin.users.view');
	
	Route::post('users/edit/group/{user}', 'Admin\UserController@editUserGroup')->name('admin.user.edit.group');
	Route::post('users/edit/info/{user}', 'Admin\UserController@editBasicInfo')->name('admin.user.edit.info');
	
	Route::post('users/ban/{user}', 'Admin\UserController@banUser')->name('admin.user.ban');
	Route::get('users/remove/ban/{ban}', 'Admin\UserController@removeBan')->name('admin.ban.remove');


// Log
	Route::get('log', 'Admin\LogController@showLog')->name('admin.log');

// Products
	Route::get('products', 'Admin\ProductController@products')->name('admin.products');
	Route::post('products/query', 'Admin\ProductController@productsPost')->name('admin.products.query');
	Route::post('product/delete', 'Admin\ProductController@deleteProduct')->name('admin.product.delete');
	Route::get('product/{id}/{section?}', 'Admin\ProductController@editProduct')->name('admin.product.edit');
	
	Route::get('purchases', 'Admin\ProductController@purchases')->name('admin.purchases');

// Post Announcements routes
Route::get('announcements', 'Admin\AdminController@announcement')->name('admin.announcement');
Route::post('announcements/new', 'Admin\AdminController@newAnnouncements')->name('admin.announcement.new');
Route::get('announcements/delete/{id}', 'Admin\AdminController@removeAnnouncements')->name('admin.announcement.delete');
Route::get('announcements/{id}', 'Admin\AdminController@editAnnouncementsShow')->name('admin.announcement.show');
Route::post('announcements/{id}', 'Admin\AdminController@editAnnouncements')->name('admin.announcement.edit');

// Bitmessage
	Route::get('bitmessage', 'Admin\BitmessageController@show')->name('admin.bitmessage');
	
	
	Route::get('disputes/{state?}', 'Admin\AdminController@disputes')->name('admin.disputes');
	Route::get('purchase/{purchase}', 'Admin\AdminController@disputedPurchase')->name('admin.purchase');
	Route::post('purchase/{purchase}/disputes/message', 'Admin\AdminController@newDisputeMessage')->name('admin.purchase.disputes.message');


// Support tickets
	Route::get('tickets/remove', 'Admin\AdminController@ticketsRemove')->name('admin.tickets.remove');
	Route::get('tickets/{state?}', 'Admin\AdminController@tickets')->name('admin.tickets');

//Route::get('tickets/open', 'Admin\AdminController@openTickets') -> name('admin.tickets.open');
//Route::get('tickets/answered', 'Admin\AdminController@answeredTickets') -> name('admin.tickets.answered');
//Route::get('tickets/solved', 'Admin\AdminController@solvedTickets') -> name('admin.tickets.solved');
	
	Route::get('ticket/{ticket}', 'Admin\AdminController@viewTicket')->name('admin.tickets.view');
	Route::get('ticket/{ticket}/solve', 'Admin\AdminController@solveTicket')->name('admin.tickets.solve');

// Vendor purchases
	Route::get('vendor/purchases', 'Admin\AdminController@vendorPurchases')->name('admin.vendor.purchases');

// Featured products
	
	Route::get('products/featured', 'Admin\ProductController@featuredProductsShow')->name('admin.featuredproducts.show');
	Route::get('products/featured/mark/{product}', 'Admin\ProductController@markAsFeatured')->name('admin.product.markfeatured');
	
	Route::post('products/featured/remove', 'Admin\ProductController@removeFromFeatured')->name('admin.featuredproducts.remove');

// Remove tickets
	
	Route::post('tickets/remove', 'Admin\AdminController@removeTickets')->name('admin.tickets.remove');
	
//ElasticSearch
	Route::get('elasticsearch/index', 'Admin\AdminController@elasticsearchIndexState')->name('admin.elasticsearch.index');
//	Route::get('elasticsearch/IndexProduct/{id}', 'Admin\AdminController@elasticsearchIndexProduct')->name('admin.elasticsearch.IndexProduct');
	Route::get('elasticsearch/ReindexAll', 'Admin\AdminController@elasticsearchReindexAllProducts')->name('admin.elasticsearch.ReindexAll');
//	Route::get('elasticsearch/deleteAllIndex', 'Admin\AdminController@elasticsearchDeleteAllIndex')->name('admin.elasticsearch.DeleteAllIndex');
