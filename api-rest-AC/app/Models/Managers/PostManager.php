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

    public function update_post($id, $params_array, $token) {
        if(!is_null($params_array) && !empty($params_array)) {
            // Validar los datos
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required'
            ]);

            if($validate->fails()) {
                // Error con la validacion de los datos
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: Datos invalidos.'
                );

            } else {
                // Eliminar lo que no se quiere actualizar
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                $post = Post::find($id);

                if( is_object($post) ){
                    // Conseguir usuario identifado
                    $jwt_auth = new JwtAuth();
                    $user = $jwt_auth->check_token($token, true);

                    if( $user->sub == $post->user_id ) {
                        // Actualizar el registro
                        $post->title = $params_array['title'];
                        $post->content = $params_array['content'];
                        $post->category_id = $params_array['category_id'];
                        if( isset($params_array['image']) ) {
                            $post->image = $params_array['image'];
                        }
                        $post->save();

                        $data = array(
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'Post actualizado correctamente.',
                            'changes' => $params_array,
                            'post' => $post
                        );

                    } else {
                        $data = array(
                            'status' => 'error',
                            'code' => 404,
                            'message' => 'ERROR: No eres autor del post.'
                        );

                    }
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'ERROR: El post NO existe.'
                    );

                }

            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: No se ha actualizado el post.'
            );

        }

        return $data;
    }

    public function delete_post($id, $token) {
        // Conseguimos el post
        $post = Post::find($id);

        // Conseguir usuario identifado
        $jwt_auth = new JwtAuth();
        $user = $jwt_auth->check_token($token, true);

        if( is_object($post) ){
            if( $user->sub == $post->user_id ) {
                // Borrar el posts
                $post->delete();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Post eliminado correctamente.',
                    'post' => $post
                );

            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: No eres autor del post.'
                );

            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: El post NO existe.'
            );

        }

        return $data;
    }

    public function update_image($request) {
        // Recorger la imagen de la peticion
        $image = $request->file('file0');

        // Validar la imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|mimes:jpg,jpeg,png,gif'
        ]);

        // Guardar la imagen
        if($validate->fails() || !$image) {
            // Error con la validacion de los datos
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: La imagen no es valida.'
            );

        } else {
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_name, \File::get($image));
            $data = array(
                'status' => 'success',
                'code' => 200,
                'imagen' => $image_name,
                'message' => 'La imagen fue subida correctamente.'
            );

        }

        return $data;
    }

}
