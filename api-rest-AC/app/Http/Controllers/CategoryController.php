<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Managers\CategoryManager;

// use Illuminate\Http\Response;
// use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct() {
        // Cargar el middleware de autenticacion, para la creacion de una categoria
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    public function test(Request $request) {
        return "Accion de pruebas de category controller";
    }

    public function index() {
        // Instanciamos el gestor y delegamos la tarea de obtener las categorias
        $category_manager = new CategoryManager();
        $data = $category_manager->get_categories();

        return response()->json($data, $data['code']);
    }

    public function show($id) {
        // Instanciamos el gestor y delegamos la tarea de obtener una categoria en concreto
        $category_manager = new CategoryManager();
        $data = $category_manager->get_specific_category($id);

        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Instanciamos el gestor y delegamos la tarea de guardar una categoria
        $category_manager = new CategoryManager();
        $data = $category_manager->store_category($params_array);

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        // Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Instanciamos el gestor y delegamos la tarea de actualizar una categoria en concreto
        $category_manager = new CategoryManager();
        $data = $category_manager->update_category($id, $params_array);

        // Devolver el resultado
        return response()->json($data, $data['code']);

    }

    public function destroy($id) {
        // Instanciamos el gestor y delegamos la tarea de eliminar una categoria en concreto
        $category_manager = new CategoryManager();
        $data = $category_manager->delete_category($id);

        return response()->json($data, $data['code']);
    }

}
