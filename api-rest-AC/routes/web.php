<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    echo "Hola mundo";
});

Route::get('/test/{name?}', function($name = null) {
    $texto = '<h2>Mi nombre '.$name.'</h2>';

    return view('test', array(
        'texto' => $texto
    ));
});

Route::get('/test2/movies', [TestController::class, 'index']);

// Probando el ORM
Route::get('/testorm', [TestController::class, 'testOrm']);