<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use Illuminate\Http\Request;



Route::group(['middleware' => ['web']], function () {
     
    Route::get('/', function () {
      return view('welcome');
    });
    
    Route::auth();
    Route::get('/home', [
    	'middleware' => 'auth',
    	'uses' => 'HomeController@index',
    	'as' => 'home'
    ]);
    
    Route::get('/balances', [
    	'middleware' => 'auth',
        'uses' => 'BalancesController@getPage',
        'as' => ''
    ]);
     
    Route::get('/insert', [
    	'uses' => 'BalancesInsertController@insertform',
    	'as' => 'insert'
    ]);

    Route::post('create', [
    	'uses' => 'BalancesInsertController@insert',
    	'as' => 'create'
    ]);
    
    Route::get('/graphData', [
    	'uses' => 'graphController@index',
    	'as' => 'graph'
    ]);

    Route::get('transport', [
        'uses' => 'TransportController@getPage',
        'as' => ''
    ]);

    Route::get('transport/get', [
        'uses' => 'TransportController@getRecords',
        'as' => 'get'
    ]);
    
    Route::post('transport/edit', [
        'uses' => 'TransportController@postRecord',
        'as' => 'edit'
    ]);

    Route::get('money', [
        'uses' => 'MoneyController@getPage',
        'as' => ''
    ]);

    Route::get('money/get', [
        'uses' => 'MoneyController@getRecords',
        'as' => 'get'
    ]);
    
    Route::post('money/edit', [
        'uses' => 'MoneyController@postRecord',
        'as' => 'edit'
    ]);

    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');    
 
    
    Route::get('citi',function(){
         return view('citi');
    });

    /**
     * Show citi chart
     */
     
/*
    Route::get('citi',[
            'middleware' => 'Citi:editor',
            'uses'	 => 'CitiController@index',
    ]);
*/


    
    Route::get('/getmsg','AjaxController@index');
    Route::post('/getmsg','AjaxController@index');

    Route::get('profile', [
            'middleware' => 'auth',
            'uses' => 'UserController@showProfile'
    ]);

    Route::get('/usercontroller/path',[
            'middleware' => 'First',
            'uses'		 => 'UserController@showPath'
    ]);

    Route::resource('my','MyController');
    
    Route::get('user/{name}/{name1?}',function($name = 'Virat Gandhi', $name1 = ''){
        echo "Name: ".$name . $name1;
    });
    
    Route::get('ajax',function(){
         return view('message');
    });

});
