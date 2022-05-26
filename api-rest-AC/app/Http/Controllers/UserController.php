<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Managers\UserManager;

// use Illuminate\Validation\Rule;
// use App\Models\User;

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
        // Recibir los datos por POST
        $json = $request->input('json', null);
        // $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Instanciamos el gestor y delegamos la tarea de realizar el login
        $user_manager = new UserManager();
        $data = $user_manager->login_user($params_array);

        return response()->json($data, 200);
    }

    public function update(Request $request) {
        // Recoger token de la cabecera
        $token = $request->header('Authorization');

        // Recoger los datos por post
        $json = $request->input('json', null);
        // $params = json_decode($json);
        $params_array = json_decode($json, true);


        // Instanciamos el gestor y delegamos la tarea de realizar el login
        $user_manager = new UserManager();
        $data = $user_manager->update_user($token, $params_array);

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request) {
        // Recoger los datos de la peticion (Archivo imagen)
        $image = $request->file('file0');

        // Guardar la imagen
        if($image) {
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );

        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'ERROR: Error al subir la imagen.'
            );
        }

        return response()->json($data, $data['code']);
    }

}
