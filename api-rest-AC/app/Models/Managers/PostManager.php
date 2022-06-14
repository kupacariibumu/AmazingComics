<?php

namespace App\Models\Managers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Helpers\JwtAuth;

class PostManager extends Model
{
    use HasFactory;

    public function get_posts() {
        $posts = Post::all()->load('category');

        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Posts obtenidos.',
            'posts' => $posts
        );

        return $data;
    }

    public function get_specific_post($id) {
        $post = Post::find($id);

        if(is_object($post)) {
            $post = Post::find($id)->load('category');
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Post obtenido correctamente.',
                'post' => $post
            );

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: El post no existe.'
            );

        }

        return $data;
    }

    public function store_post($params_array, $token) {
        if(!is_null($params_array) && !empty($params_array)) {

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required'
            ]);

            if($validate->fails()) {
                // Error
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: Faltan datos del post.'
                );

            } else {
                // -> Guardar post
                // Conseguir usuario identifado
                $jwt_auth = new JwtAuth();
                $user = $jwt_auth->check_token($token, true);

                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params_array['category_id'];
                $post->title = $params_array['title'];
                $post->content = $params_array['content'];
                $post->image = $params_array['image'];

                $post->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Post almacenado correctamente.',
                    'post' => $post
                );

            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: No se ha guardado el post.'
            );

        }

        return $data;
    }

}
