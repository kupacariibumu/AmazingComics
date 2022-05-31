<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

// Cargando middleware para verificar que este identificado un usuario
use App\Http\Middleware\ApiAuthMiddleware;

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

// ------> Rutas testing
Route::get('/', function () {
    // return view('welcome');
    echo "Amazing Comics!";
});

Route::get('/welcome', function () {
    echo "Hello world!";
});

// ------> Primeras rutas de prueba
// Route::get('/test/{name?}', function($name = null) {
//     $texto = '<h2>Mi nombre '.$name.'</h2>';

//     return view('test', array(
//         'texto' => $texto
//     ));
// });

// Route::get('/test2/movies', [TestController::class, 'index']);

// ------> Probando el ORM
// Route::get('/testorm', [TestController::class, 'testOrm']);

// ------> Metodos HTTP comunes
// GET: Conseguir datos o recursos
// POST: Guardar datos o recursos, hacer logica desde un formulario
// PUT: Actualizar datos o recursos
// DELETE: Eliminar datos o recursos

// ------> Rutas de API
// Rutas de prueba
// Route::get('/user/test', [UserController::class, 'test']);
// Route::get('/post/test', [PostController::class, 'test']);
// Route::get('/category/test', [CategoryController::class, 'test']);

// Rutas de usuario
Route::post('/api/register', [UserController::class, 'register']);
Route::post('/api/login', [UserController::class, 'login']);
Route::put('/api/user/update', [UserController::class, 'update']);
Route::post('/api/user/upload',[UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/image/{file_name}', [UserController::class, 'get_image']);
Route::get('/api/user/detail/{id}', [UserController::class, 'user_detail']);

// Rutas de categoria
// Rutas de tipo resource, son rutas automaticas, no tenemos que definir ruta por ruta
Route::resource('/api/category', CategoryController::class);
