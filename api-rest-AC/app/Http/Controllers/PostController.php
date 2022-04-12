<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function test(Request $request) {
        return "Accion de pruebas de post controller";
    }
}
