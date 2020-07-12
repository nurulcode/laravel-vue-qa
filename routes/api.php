<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/token', 'Auth\LoginController@getToken');
Route::post('/login', 'Api\Auth\LoginController@store');
Route::post('/register','Api\Auth\RegisterController');


Route::get('/questions', 'Api\QuestionsController@index');
Route::get('/questions/{question}/answers', 'Api\AnswerController@index');

Route::get('/questions/{question}-{slug}', 'Api\QuestionDetailsController');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/questions', 'Api\QuestionsController')->except('index');
    Route::apiResource('/questions.answers', 'Api\AnswerController')->except('index');
    Route::post('/questions/{question}/vote', 'VoteQuestionController');
    Route::post('/answers/{answer}/vote', 'VoteAnswerController');
    Route::post('/answers/{answer}/accept', 'AcceptAnswerController');
    Route::post('/questions/{question}/favorites', 'FavoritesController@store');
    Route::delete('/questions/{question}/favorites', 'FavoritesController@destroy');
    Route::delete('/logout', 'Api\Auth\LoginController@destroy');

    Route::get('/my-posts', 'Api\MyPostsController');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('questions/{question}/destroy', 'QuestionsController@destroy')->name('question.destroy');
