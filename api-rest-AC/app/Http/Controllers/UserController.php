<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        if(!empty($params_array)) {
            // Limpiar los datos
            $params_array = array_map('trim', $params_array);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email',
                'password'  => 'required'
            ]);

            if($validate->fails()) {
                // ERROR: Los datos enviados no son correctos
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Error con los datos',
                    'errors' => $validate->errors()
                );

            } else {
                // Cifrar la contraseña
                // Comprobar si el usuario existe ya (duplicado)
                // Crear el usuario

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente'
                );
            }
        } else {
            // ERROR: No se enviaron datos
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'No hay datos enviados',
            );

        }



        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        return "Accion de registro de login";
    }
}
