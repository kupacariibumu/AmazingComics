<?php

namespace App\Models\Managers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserManager extends Model
{
    use HasFactory;

    public function __construct() {}

    public function register_user($params_array) {
        if(!empty($params_array)) {
            // Limpiar los datos
            $params_array = array_map('trim', $params_array);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users',
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
                // $pass_hash = password_hash($params_array['password'], PASSWORD_BCRYPT, ['cost' => 4]);
                $pass_hash = hash('SHA256', $params_array['password']);

                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pass_hash;
                $user->role = 'ROLE_USER';

                // Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
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

        return $data;
    }

    public function login_user($params_array) {
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

        return $data_sing_up;
    }

}
