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

        // Recibir los datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar los datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            $data_sing_up = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        } else {
            // Cifra la password
            $pass_hash = hash('SHA256', $params_array['password']);

            // Devolver token o datos
            $jwt_auth = new \JwtAuth();
            $data_sing_up = $jwt_auth->sing_up($params_array['email'], $pass_hash);

            // Si llega get token se devuelven los datos
            if( isset($params_array['get_token']) ) {
                $data_sing_up = $jwt_auth->sing_up($params_array['email'], $pass_hash, true);
            }

        }

        return response()->json($data_sing_up, 200);
    }
}
