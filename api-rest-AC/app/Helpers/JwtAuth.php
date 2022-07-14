<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth {

    private $key;

    public function __construct() {
        $this->key = "AMAZING_COMICS_33282986";
    }

    public function sing_up($email, $password, $get_token = null) {
        // Buscar si existe el usuario con sus credenciales y solo obtiene el primero que coincida
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        // Comprobar si son correctas (objeto)
        $sing_up = false;
        if(is_object($user)) {
            $sing_up = true;
        }

        // Generar el token con los datos del usuario identificado
        if($sing_up) {
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time() + ( 7 * 24 * 60 *60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

            // Devolver los datos decodificados o el token, en funcion de un parametro
            if(is_null($get_token)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }

        } else {
            $data = array(
                'status' => 'error',
                'message' => 'ERROR: Los datos ingresados no existen, o no son correctos.'
            );
        }

        return $data;
    }

    public function check_token($jwt, $get_identity=false) {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if($get_identity) {
            return $decoded;
        }

        return $auth;

    }

}

?>