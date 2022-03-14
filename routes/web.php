<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version() . "<br>" . "Created by LM ®";
});

/*
 *
 * AUTHENTICATED ROUTES
 *
 */

//JWT
$router->group(['prefix' => '/api/v1', 'middleware' => ['auth']], function( $router ) {
    $router->post( '/logout', 'AuthController@logout' );
    $router->get( '/refresh', 'AuthController@refresh' );
    $router->post( '/refresh', 'AuthController@refresh' );
});

//POSTS
$router->group(['prefix' => '/api/v1/posts', 'middleware' => ['auth', 'wrapper', 'query']], function( $router ) {
    $router->get( '/list', 'PostController@userPosts' );
    $router->get( '/favorites', 'PostController@favoritePosts' );
    $router->post( '/', 'PostController@store' );
    $router->put( '/{id}', 'PostController@update' );
    $router->delete( '/{id}', 'PostController@destroy' );

});

//COMMENTS
$router->group(['prefix' => '/api/v1/posts', 'middleware' => ['auth', 'wrapper', 'query']], function( $router ) {
    $router->post( '/{post_id}/comments', 'CommentController@store' );
    $router->put( '/{post_id}/comments/{id}', 'CommentController@update' );
    $router->delete( '{post_id}/comments/{id}', 'CommentController@destroy' );
});

//LIKES
$router->group(['prefix' => '/api/v1/posts', 'middleware' => ['auth', 'wrapper', 'query']], function( $router ) {
    $router->post( '/{post_id}/like', 'PostLikesController@likePost' );
    $router->delete( '{post_id}/like', 'PostLikesController@unLikePost' );
    $router->post( '/comments/{comment_id}/like', 'CommentLikesController@likeComment' );
    $router->delete( '/comments/{comment_id}/like', 'CommentLikesController@unLikeComment' );
});

//CATEGORIES
$router->group(['prefix' => '/api/v1/categories', 'middleware' => ['auth', 'wrapper', 'query']], function( $router ) {
    $router->get( '/', 'CategoryController@index' );
    $router->get( '/{cat_name}', 'CategoryController@show' );
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
    $router->group(['prefix' => '/api/v1/posts', 'middleware' => ['wrapper', 'query']], function( $router ) {
    $router->get( '/', 'PostController@index' );
    $router->get( '/{id}', 'PostController@show' );
    $router->get( '/search/{toSearch}', 'PostController@search' );
});

