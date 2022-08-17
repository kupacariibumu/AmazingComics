<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Managers\PostManager;

class PostController extends Controller
{

    public function __construct() {
        // Cargar el middleware de autenticacion, para la creacion de una categoria
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'get_image_post',
            'get_posts_by_category',
            'get_posts_by_user'
        ]]);
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

    public function destroy($id, Request $request) {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $token = $request->header('Authorization', null);

        // Instanciamos el gestor y delegamos la tarea de eliminar un post
        $post_manager = new PostManager();
        $data = $post_manager->delete_post($id, $token);

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function upload_image_post(Request $request) {

        // Instanciamos el gestor y delegamos la tarea de subir una imagen de un post
        $post_manager = new PostManager();
        $data = $post_manager->update_image($request);

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function get_image_post($file_name) {

        // Instanciamos el gestor y delegamos la tarea de subir obtener la imagen de un post
        $post_manager = new PostManager();
        return $post_manager->get_image($file_name);

    }

    public function get_posts_by_category($id) {

        // Instanciamos el gestor y delegamos la tarea de obetener todos los posts por categoria
        $post_manager = new PostManager();
        $data = $post_manager->get_posts_category($id);

        // Devolver el resultado
        return response()->json($data, $data['code']);

    }

    public function get_posts_by_user($id) {

        // Instanciamos el gestor y delegamos la tarea de obetener todos los posts por usuario
        $post_manager = new PostManager();
        $data = $post_manager->get_posts_user($id);

        // Devolver el resultado
        return response()->json($data, $data['code']);

    }

}
