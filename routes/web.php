<?php

use Illuminate\Support\Facades\Route;


// BACK END
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\TopicsController;
use App\Http\Controllers\Admin\WordsController;
use App\Http\Controllers\Admin\BookmarkController;

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
// FRONT END
Route::get('/', function () {
    return view('welcome');
});



// BACK END

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])
    ->name('admin');
    
    Route::post('login', [AdminController::class, 'admin_login'])
    ->name('admin.login');

    Route::get('logout', [AdminController::class, 'admin_logout'])
    ->name('admin.logout');
    
    Route::get('dashboard', [AdminController::class, 'show_dashboard'])
    ->name('admin.dashboard');
    
    Route::get('administrator', [AdminController::class, 'show_dashboard'])
    ->name('admin.administrator');
        
        Route::get('administrator-list', [AdminController::class, 'index'])
        ->name('admin.administrator-list');

        Route::get('administrator-role', [AdminController::class, 'index'])
        ->name('admin.administrator-role');

    Route::get('user', [AdminController::class, 'index'])
    ->name('admin.user');

    Route::get('analysis', [AdminController::class, 'show_dashboard'])
    ->name('admin.analysis');
        
        Route::get('topics', [TopicsController::class, 'show_topics'])
        ->name('admin.topics');

        Route::get('ajax/showtopics', [TopicsController::class, 'ajax_show_topics'])
        ->name('admin.ajax-show-topics');

        Route::get('add-source', [TopicsController::class, 'add_source'])
        ->name('admin.add-source');

        Route::post('save-source', [TopicsController::class, 'save_source'])
        ->name('admin.save-source');

        Route::get('add-topic', [TopicsController::class, 'add_topic'])
        ->name('admin.add-topic');

        Route::post('save-topic', [TopicsController::class, 'save_topic'])
        ->name('admin.save-topic');

        Route::get('titles', [TopicsController::class, 'titles'])
        ->name('admin.titles');

        Route::get('ajax/showtitles', [TopicsController::class, 'ajax_show_titles'])
        ->name('admin.ajax-show-titles');

        Route::get('add-title', [TopicsController::class, 'add_title'])
        ->name('admin.add-title');

        Route::post('save-title', [TopicsController::class, 'save_title'])
        ->name('admin.save-title');

    Route::get('words', [WordsController::class, 'words'])
    ->name('admin.words');

        Route::get('ajax/showwords', [WordsController::class, 'ajax_show_words'])
        ->name('admin.ajax-show-words');
        
        Route::get('add-words', [WordsController::class, 'add_words'])
        ->name('admin.add-words');

        Route::post('save-words', [WordsController::class, 'save_words'])
        ->name('admin.save-words');

        Route::get('sentences', [WordsController::class, 'sentences'])
        ->name('admin.sentences');

        Route::get('ajax/showsentences', [WordsController::class, 'ajax_show_sentences'])
        ->name('admin.ajax-show-sentences');

    Route::get('bookmark', [BookmarkController::class, 'bookmark_topic'])
    ->name('admin.bookmark');

        Route::get('bookmark-topic', [BookmarkController::class, 'bookmark_topic'])
        ->name('admin.bookmark-topic');
        
        Route::get('bookmark-word', [BookmarkController::class, 'bookmark_word'])
        ->name('admin.bookmark-word');


});
