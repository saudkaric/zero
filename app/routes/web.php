<?php
declare(strict_types=1);

use Zero\System\Router\Route;
use Zero\Controllers\PageController;

/* 
 * This set page not found countroller
 * You can leave it like this or you can set sour own countroller
 * but uri must be not-found
 * Route::get('Error@notFound', 'not-found');
 */
Route::notFound();

/**
 * Default routes whit example od middleware and  prefix
 */
Route::middleware('guest', function () {
    Route::prefix('/', function () {
        Route::get('', [PageController::class, 'index']);
        
        Route::get('home', function (){
            return view('pages.index');
        });
    });
});