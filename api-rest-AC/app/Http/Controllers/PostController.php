<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Managers\PostManager;

class PostController extends Controller
{

    public function __construct() {
        // Cargar el middleware de autenticacion, para la creacion de una categoria
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    public function index() {
        // Instanciamos el gestor y delegamos la tarea de obtener los posts
        $post_manager = new PostManager();
        $data = $post_manager->get_posts();

        return response()->json($data, $data['code']);
    }

    public function show($id) {
        // Instanciamos el gestor y delegamos la tarea de obtener un post en concreto
        $post_manager = new PostManager();
        $data = $post_manager->get_specific_post($id);

        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $token = $request->header('Authorization', null);

        // Instanciamos el gestor y delegamos la tarea de guardar un post
        $post_manager = new PostManager();
        $data = $post_manager->store_post($params_array, $token);

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $token = $request->header('Authorization', null);

        // Instanciamos el gestor y delegamos la tarea de actualizar un post
        $post_manager = new PostManager();
        $data = $post_manager->update_post($id, $params_array, $token);

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function destroy() {
        // 45 en eliminar entrada
    }

}
