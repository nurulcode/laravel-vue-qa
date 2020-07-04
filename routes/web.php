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
Auth::routes();

Route::view('/{any}', 'spa')->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionsController')->except(['show']);
Route::get('/questions/{slug}', 'QuestionsController@show')->name('questions.show');
Route::resource('questions.answers', 'AnswerController')->except(['create', 'show']);
Route::post('/answers/{answer}/accept', 'AcceptAnswerController')->name('answers.accept');


Route::post('/questions/{question}/favorites', 'FavoriteController@store')->name('questions.favorite');
Route::delete('/questions/{question}/favorites', 'FavoriteController@destroy')->name('questions.unFavorite');

Route::post('/questions/{question}/vote', 'VoteQuestionController');
Route::post('/answers/{answer}/vote', 'VoteAnswerController');


// Route::resource('questions', 'QuestionsController')->except(['destroy']);
