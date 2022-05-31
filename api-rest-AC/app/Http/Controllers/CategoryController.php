<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Managers\CategoryManager;

// use Illuminate\Http\Response;
// use App\Models\Category;

class CategoryController extends Controller
{
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

}
