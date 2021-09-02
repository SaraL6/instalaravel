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

// Auth Route

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();





//  Profile Route
Route::get('/profile/{user}', 'ProfilesController@index');
Route::get('/profile/edit/{user}', 'ProfilesController@edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');

// Post Route
 Route::get('/', 'PostsController@index')->name('home.index');;
 Route::get('/post/create', 'PostsController@create');
 Route::post('/post/store', 'PostsController@store');
 Route::patch('/post/update/{post}', 'PostsController@update')->name('posts.update');
 Route::delete('/post/delete/{post}', 'PostsController@destroy');
 Route::get('/post/show/{post}', 'PostsController@show')->name('post.show');
 Route::post('like/{like}', 'LikeController@update2')->name('like.create');
 Route::post('/p/{post}', 'PostsController@updatelikes')->name('post.update');
Route::post('follow/{user}','FollowsController@store');



//  Comment Route
Route::get('/posts/{post}/comments', 'CommentController@showComments')->name('show.comments');
Route::post('/posts/{post}/comment', 'CommentController@store')->name('post.comment');