<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index() {
        $title = 'Movies';
        $movies = ['The batman', 'Spiderman', 'Sherk'];

        return view('test.index', array(
            'title' => $title,
            'movies' => $movies
        ));
    }
}
