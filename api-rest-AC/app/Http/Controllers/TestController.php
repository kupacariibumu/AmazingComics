<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

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

    public function testOrm() {

        $categories = Category::all();
        foreach ($categories as $category) {
            echo "{$category->name}<br>";
            foreach ($category->posts as $post) {
                echo $post->title.'<br>';
                echo "{$post->user->name}<br>";
                echo "{$post->user->email}<br>";
                echo "{$post->category->name}<br>";
                echo $post->content.'<br>';
                echo '<br>';
            }
            echo "<hr>";
        }

        die();
    }

}
