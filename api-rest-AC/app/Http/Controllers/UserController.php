<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function test(Request $request) {
        return "Accion de pruebas de user controller";
    }

    public function register(Request $request) {
        return "Accion de registro de usuario";
    }

    public function login(Request $request) {
        return "Accion de registro de login";
    }
}
