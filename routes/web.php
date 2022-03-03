<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*
 *
 * UNAUTHENTICATED ROUTES
 *
 */

//JWT
$router->group(['prefix' => '/api/v1'], function( $router ) {
        $router->post( '/authenticate', 'AuthController@login');
        $router->post( '/register', 'AuthController@register' );
    }
);

//POSTS
$router->group(['prefix' => '/api/v1/posts'], function( $router ) {
    $router->get( '/', 'PostController@index' );
    $router->get( '/{id}', 'PostController@show' );
});

/*
 *
 * AUTHENTICATED ROUTES
 *
 */

//JWT
$router->group(['prefix' => '/api/v1', 'middleware' => 'auth'], function( $router ) {
        $router->post( '/logout', 'AuthController@logout' );
        $router->get( '/refresh', 'AuthController@refresh' );
        $router->post( '/refresh', 'AuthController@refresh' );
});

//POSTS
$router->group(['prefix' => '/api/v1/posts', 'middleware' => 'auth'], function( $router ) {
    $router->post( '/', 'PostController@store' );
    $router->put( '/{id}', 'PostController@update' );
    $router->delete( '/{id}', 'PostController@destroy' );
});

//COMMENTS
$router->group(['prefix' => '/api/v1/posts', 'middleware' => 'auth'], function( $router ) {
    $router->post( '/{post_id}/comments', 'CommentController@store' );
    $router->put( '/{post_id}/comments/{id}', 'CommentController@update' );
    $router->delete( '{post_id}/comments/{id}', 'CommentController@destroy' );
});

