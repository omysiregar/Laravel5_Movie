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
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/', 'HomeController@index')->name('.index');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('.index');
    Route::post('/favorite', 'HomeController@favorite')->name('favorite.add');
    Route::post('/unfavorite', 'HomeController@unfavorite')->name('favorite.remove');
    Route::get('/favorites', 'HomeController@getFavorites')->name('favorite.list');
    Route::get('/movie/{imdb_id}', 'HomeController@show')->name('movie.detail');
});


// // Auth Routes
// Route::group(['namespace' => 'Auth', 'as' => 'auth'], function(){
//     // Login Page
//     Route::get('/login',     'LoginController@showLoginForm')->name('.login');
//     // Login Post
//     Route::post('/login',    'LoginController@login')->name('.login');
//     // Logout
//     Route::get('/logout',    'LoginController@logout')->name('.logout');
// });
