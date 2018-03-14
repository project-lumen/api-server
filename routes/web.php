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

$router->get('/allList', function () use ($router) {
 $variable = \App\myList::all();
 return $variable;
});

$router->get('/test', function () use ($router) {
 $variable = \App\User::all();
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
$router->post('users/logout', ['middleware' => 'auth','uses' => 'UserController@logout']);

// ACCES VIA Middleware
$router->get('users/info', ['middleware' => 'auth','uses' => 'UserController@info']);
$router->get('/user/{token}', ['middleware' => 'auth', 'uses' =>  'UserController@get_user']);

$router->post('/user/info', ['middleware' => 'auth', 'uses' =>  'UserController@infoUser']);



// printList
$router->get('/ownList', ['middleware' => 'auth', 'uses' =>  'MyListController@showListsUser']);

//AFFICHAGE DE LA LISTE
$router->post('/myList/printList', ['middleware' => 'auth', 'uses' =>  'MyListController@printList']);

//AJOUT D'UNE NOUVELLE LISTE
$router->post('/myList/addList', ['middleware' => 'auth', 'uses' =>  'MyListController@addList']);

//AJOUT D'UNE TACHE
$router->post('/myList/addTask', ['middleware' => 'auth', 'uses' =>  'MyListController@addTask']);

//MODIFICATION DE L'ATTRIBUT CHECK DE LA TACHE SELECTION
$router->post('/myList/checkTask', ['middleware' => 'auth', 'uses' =>  'MyListController@checkTask']);

//MODIFICATION DE LA TACHE
$router->post('/myList/modifTask', ['middleware' => 'auth', 'uses' =>  'MyListController@modifTask']);

//ADD UNE TACHE À UN USER
$router->post('/myList/addUser', ['middleware' => 'auth', 'uses' =>  'MyListController@addUser']);

// RETOURNE LA LISTE DES TACHES IMPORTANTES
$router->post('/myList/impTask', ['middleware' => 'auth', 'uses' =>  'MyListController@importantTask']);

// RETOURNE LES TACHES D'AUJOURD'HUI
$router->post('/myList/todTask', ['middleware' => 'auth', 'uses' =>  'MyListController@todayTask']);

// RETOURNE LES TACHES QUI SE TERMINE BIENTOT
$router->post('/myList/expTask', ['middleware' => 'auth', 'uses' =>  'MyListController@soonTask']);
