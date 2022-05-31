<?php

namespace App\Models\Managers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Validation\Rule;

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

    public function update_user($token, $params_array) {
        if( !is_null($token) ) {
            $jwt_auth = new \JwtAuth();
            $check_token = $jwt_auth->check_token($token);

            if($check_token) {

                if(!is_null($params_array) && !empty($params_array)){
                    // Sacar usuario identificado
                    $user = $jwt_auth->check_token($token, true);

                    // Validar los datos
                    $validate = \Validator::make($params_array, [
                        'name'      => 'required|alpha',
                        'surname'   => 'required|alpha',
                        'email'     => [
                            'required',
                            'email',
                            Rule::unique('users')->ignore($user->sub)
                        ]
                    ]);

                    if($validate->fails()) {
                        $data = array(
                            'status' => 'error',
                            'code' => 400,
                            'message' => 'ERROR: Los datos a actualizar son erroneos o faltan datos',
                            'errors' => $validate->errors()
                        );

                    } else {
                        // Quitar los campos que no se quieren actualizar
                        unset($params_array['id']);
                        unset($params_array['role']);
                        unset($params_array['created_at']);
                        unset($params_array['remember_token']);

                        // Actualizar el usuario en la base de datos
                        $user_update = User::where('id', $user->sub)->update($params_array);

                        // Devolver array con el resultado
                        $data = array(
                            'status' => 'success',
                            'code' => 200,
                            'user' => $user,
                            'changes' => $params_array
                        );
                    }

                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'ERROR: No se enviaron datos para actualizar'
                    );

                }

            } else {
                // Devolver mensaje de error
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'ERROR: El usuario no esta identificado.'
                );
            }
        } else {
            // Devolver mensaje de error
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'ERROR: No se ha enviado un token.'
            );
        }

        return $data;
    }

    public function upload_user_image($request) {
        // Recoger los datos de la peticion (Archivo imagen)
        $image = $request->file('file0');

        // Validacion de la imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        if(!$image || $validate->fails()){
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'ERROR: Error al subir la imagen.'
            );

        } else {
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );

        }

        return $data;
    }

}
