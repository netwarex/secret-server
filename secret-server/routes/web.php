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

/*$router->get('/', function () use ($router) {
    return $router->app->version();
});*/

// API Version 1 Routes
$ApiV1 = function () use ($router) {
    $router->post('/secret','SecretController@addSecret'); //create secret
    $router->get('/secret/{hash}', 'SecretController@getSecretByHash'); //get single secret
};

// API Versions
$router->group(['namespace' => 'v1'], $ApiV1); // Default API Version is the oldest available
$router->group(['namespace' => 'v1', 'prefix' => 'v1'], $ApiV1); // /v1/
