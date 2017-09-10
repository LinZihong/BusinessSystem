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

Route::get('/', 'HomeController@index')->name('index');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::group(['middleware' => 'register'], function () {
	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	Route::post('register', 'Auth\RegisterController@register');
});

Route::get('error', 'HomeController@showErrorPage')->name('error');

// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['middleware' => 'auth'], function () {

	Route::get('/dashboard', 'UserController@index')->name('dashboard');

	Route::group(['middleware' => 'suspend'], function () {

		Route::group(['prefix' => 'transaction'], function () {
			Route::get('/list', 'TransactionController@showTransactionList')->name('TransactionList');
			Route::get('/income', 'TransactionController@showIncomeCreateForm')->name('TransIn');//买
			Route::post('/income', 'TransactionController@buyFromUser')->name('doTransIn');
			Route::get('/outcome', 'TransactionController@showOutcomeCreateForm')->name('TransOut');//卖
			Route::post('/outcome', 'TransactionController@sellToUser')->name('doTransOut');
			Route::get('/buygov', 'TransactionController@showBuyGovCreateForm')->name('BuyGov');//从政府买
			Route::post('/buygov', 'TransactionController@buyFromGovernment')->name('doBuyGov');
			Route::get('/sellgov', 'TransactionController@showSellGovCreateForm')->name('SellGov');//向政府卖
			Route::post('/sellgov', 'TransactionController@sellToGovernment')->name('doSellGov');
			Route::post('/confirm', 'TransactionController@handleTransaction')->name('confirmTrans');
		});

		Route::group(['prefix' => 'resource'], function () {
			Route::get('/list', 'HomeController@showResource')->name('resource');
			Route::get('/{id}', 'HomeController@showIndividualResource');
			Route::get('/purchase', 'PurchaseController@showPurchaseForm')->name('purchaseForm');
			Route::post('/purchase', 'PurchaseController@TopUp')->name('doPurchase');
		});

		Route::group(['prefix' => 'technology'], function () {
		   Route::get('/show', 'TechnologyController@showTechPage')->name('showTech');
		   Route::get('/update', 'TechnologyController@updateTech')->name('updateTech');
        });
	});
	Route::group(['prefix' => 'announcement'], function () {
		Route::get('/', 'HomeController@showAnnouncement')->name('announcement');
	});

	Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
		Route::get('/', 'Admin\AdminController@showDashboard')->name('adminDashboard');
		Route::get('/refreshUserResource', 'Admin\AdminController@refreshUserResource');
		Route::get('/acquisition_price/list', 'Admin\ResourceController@listBots')->name('listBots');
		Route::post('/acquisition_price/update', 'Admin\ResourceController@updateBots')->name('updateBots');
		Route::get('/employment_price/list', 'Admin\ResourceController@listMiners')->name('listMiners');
		Route::post('/employment_price/update', 'Admin\ResourceController@updateMiners')->name('updateMiners');
	});
});

