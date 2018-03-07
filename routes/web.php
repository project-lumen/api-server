<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () use ($router) {
 $variable = \App\myList::all();
 return $variable;
});

// TEST REQUETE //
// $router->get('Users/ten', 'MyListController@index');
// $router->get('Users/{id}', 'testController@getUser');
// $router->delete('Users/delete/{id}', 'testController@deleteUser');
// $router->post('Users/add', 'testController@createUser');
// $router->put('Users/modify/{id}', 'testController@updateUser');



// LOGIN
$router->post('users/login', 'UserController@login');
// REGISTER
$router->post('users/register', 'UserController@register');

// // LOGOUT
$router->post('users/logout', 'UserController@logout');

// ACCES VIA Middleware
$router->get('users/info', ['middleware' => 'auth','uses' => 'UserController@info']);
$router->get('/user/{id}', ['middleware' => 'auth', 'uses' =>  'UserController@get_user']);



// printList

$router->post('/myList/printList', ['middleware' => 'auth', 'uses' =>  'MyListController@printList']);

$router->post('/myList/addList', ['middleware' => 'auth', 'uses' =>  'MyListController@addList']);

$router->post('/myList/addTask', ['middleware' => 'auth', 'uses' =>  'MyListController@addTask']);
