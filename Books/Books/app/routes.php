<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', 'HomeController@showWelcome');

Route::post('/searchBooks', function(){
	// Input::get();
	return HomeController::postWelcome(Input::get("ISBN"), Input::get("Title"), Input::get("Author"), Input::get("PublicationYear"), Input::get("Publisher") );
});

Route::post('/addBook', function(){
	return HomeController::postAddBookToInventory(Input::get("ISBN"));
});

Route::get('/inventory', 'HomeController@showInventory');

Route::post('/inventory', 'HomeController@postInventory');