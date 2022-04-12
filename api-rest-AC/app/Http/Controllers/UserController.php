<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserManager;

class UserController extends Controller
{
    public function test(Request $request) {
        return "Accion de pruebas de user controller";
    }

    public function register(Request $request) {

        // Recoger los datos del usuario por post
        $json = $request->input('json', null);
        // $params = json_decode($json); // Se pueden tener los datos en forma de objeto
        $params_array = json_decode($json, true);

        // Instanciamos el gestor y delegamos la tarea de realizar el registro
        $user_manager = new UserManager();
        $data = $user_manager->register_user($params_array);

        // Devolvemos la respuesta del gestor
        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        return "Accion de registro de login";
    }
}
