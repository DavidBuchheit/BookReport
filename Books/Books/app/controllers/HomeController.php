<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello', HomeController::varstoViewHome() );
	}

	public static function varstoViewHome(){
		$vars = array();
		$vars['similar'] = Home::similarBooks('0140304770');

		return $vars;
	}

	public function showInventory(){
		return View::make('inventory', HomeController::varstoViewInventory());
	}

	public function postWelcome($ISBN, $Title, $Author, $PublicationYear, $Publisher ){
		return Home::searchByISBN($ISBN, $Title, $Author, $PublicationYear, $Publisher);
	}

	public function postAddBookToInventory($ISBN){
		return Home::AddBookToInventory($ISBN);
	}

	public static function varstoViewInventory(){
		$vars = array();
		$vars['books'] = Home::getInventory();
		return $vars;
	}

	public static function postInventory(){
		if (Request::ajax())
		{
		   switch(Input::get("type")){
			   case 'remove':
			   		return Home::removeInventoryItem(Input::get("ISBN"));
			   	break;
		   	}
		}
	}

}
