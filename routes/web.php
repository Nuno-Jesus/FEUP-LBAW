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

//! Home

use App\Http\Controllers\AdminController;
use App\Models\Product;

Route::get('/', 'HomeController@home')->name('home');

//! Static Pages
Route::get('about', 'StaticController@about')->name('about');
Route::get('faq', 'StaticController@faq')->name('faq');
Route::get('contacts', 'StaticController@contacts')->name('contacts');

//! Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('getResetPasswordForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('sendEmail');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//! Admins
Route::get('admin/users', 'AdminController@showAllUsers')->name('showAllUsers');
Route::post('admin/users/edit/{user_id}', 'AdminController@editUser');
Route::get('admin/users/create', 'AdminController@getCreateUserForm')->name('newUser');
Route::post('admin/users/create', 'AdminController@createUser')->name('createUser');
Route::delete('admin/users/delete/{user_id}', 'AdminController@deleteUser');
Route::post('admin/users/block/{id}', 'AdminController@blockUser');
Route::post('admin/users/search', 'AdminController@searchUsers')->name('adminSearchUsers');

Route::get('admin/orders', 'AdminController@showAllOrders')->name('showAllOrders');
Route::post('admin/orders/edit/{order_id}', 'AdminController@editOrder');

Route::get('admin/products', 'AdminController@showAllProducts')->name('showAllProducts');
Route::get('admin/products/create', 'AdminController@getCreateProductForm')->name('newProduct');
Route::post('admin/products/create', 'AdminController@createProduct')->name('createProduct');
Route::delete('admin/products/delete/{product_id}', 'AdminController@deleteProduct');
Route::get('admin/products/edit/{product_id}', 'AdminController@getEditProductForm');
Route::post('admin/products/edit/{product_id}', 'AdminController@editProduct');
Route::post('admin/products/search', 'AdminController@searchProducts')->name('adminSearchProducts');


//! Users
Route::get('users', 'UserController@show')->name('profile');
Route::delete('users', 'UserController@delete')->name('deleteProfile');
Route::post('users/edit/{id}', 'UserController@update');
Route::post('users/photo', 'UserController@upload')->name('upload');

//! Cart
Route::get('users/cart', 'CartController@show')->name('cart');
Route::delete('users/cart', 'CartController@empty')->name('emptyCart');
Route::put('users/cart/{product_id}', 'CartController@add');
Route::delete('users/cart/{product_id}', 'CartController@delete');
Route::post('users/cart/{product_id}/increment', 'CartController@incrementQuantity');
Route::post('users/cart/{product_id}/decrement', 'CartController@decrementQuantity');

//! Wishlist
Route::get('users/wishlist', 'WishlistController@show')->name('wishlist');
Route::delete('users/wishlist', 'WishlistController@empty')->name('emptyWishlist');
Route::put('users/wishlist/product/{product_id}', 'WishlistController@add');
Route::delete('users/wishlist/product/{product_id}', 'WishlistController@delete');

//! Addresses
Route::get('users/addresses', 'AddressController@show')->name('addresses');
Route::get('users/addresses/new', 'AddressController@showAddressForm')->name('newAddress');
Route::post('users/addresses/new', 'AddressController@add');
Route::delete('users/addresses/{address_id}', 'AddressController@delete');

//! Orders
Route::get('users/orders', 'OrderController@show')->name('orders');
Route::post('users/orders/cancel/{id}', 'OrderController@cancel');

//! Notifications 
Route::get('users/notifications', 'NotificationController@show')->name('notifications');
Route::delete('users/notifications/mark/{id}', 'NotificationController@mark');

//! Products
Route::get('products', 'ProductController@getAllProducts')->name('allProducts');
Route::get('products/{id}', 'ProductController@showProduct');
Route::post('products/search', 'ProductController@search')->name('search');
Route::get('products/categories/{category}', 'ProductController@getCategoryProducts');
Route::post('products/search/filter', 'ProductController@filter')->name('filter');
Route::get('products/search/brand/{brand}', 'ProductController@searchBrand');


//! Reviews
Route::post('reviews/upvote/{id}', 'ProductController@upvote');
Route::post('reviews/downvote/{id}', 'ProductController@downvote');
Route::post('reviews/submit/{user_id}/{product_id}', 'ProductController@post');
Route::post('reviews/delete/{id}', 'ProductController@delete');
Route::post('reviews/report/{id}', 'ProductController@report');

//! Payments
//Route::get('users/cart/checkout', 'PaymentController@showPaymentForm')->name('newPurchase');
//Route::post('users/cart/checkout', 'PaymentController@add');
Route::get('users/cart/checkout', 'PaymentController@checkout')->name('checkout');
Route::post('users/cart/checkout', 'PaymentController@add');
