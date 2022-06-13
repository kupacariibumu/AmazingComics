<?php

namespace App\Models\Managers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Post;


class CategoryManager extends Model
{
    use HasFactory;

    public function __construct() {}

    public function get_categories() {
        $categories = Category::all();

        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Categorias obtenidas.',
            'categories' => $categories
        );

        return $data;
    }

    public function get_specific_category($id) {
        $category = Category::find($id);

        if(is_object($category)) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Categoria obtenida correctamente.',
                'category' => $category
            );

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: La categoria no existe.'
            );

        }

        return $data;
    }

    public function store_category($params_array) {

        if(!is_null($params_array)) {
            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            // Guardar la categoria
            if($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: No se ha guardado la categoria.'
                );

            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Categoria almacenada correctamente.',
                    'category' => $category
                );

            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: No se ha guardado la categoria.'
            );

        }

        return $data;
    }

    public function update_category($id, $params_array) {

        if(!is_null($params_array) && !is_null($id)) {
            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            if($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: No se ha actualizado la categoria.'
                );

            } else {
                // Quitar lo que no quiero actualizar
                unset($params_array['id']);
                unset($params_array['created_at']);

                // Actualizar la categoria
                $category = Category::where('id', $id)->update($params_array);

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Categoria actualizada correctamente.',
                    'category' => $category
                );

            }


        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: No se ha actualizado la categoria.'
            );

        }

        return $data;

    }

    public function delete_category($id) {

        if(!is_null($id)) {

            $category = Category::find($id);

            if(is_object($category)) {

                $posts = Post::where('category_id', $id)->get();

                if( $posts->isEmpty() ){
                    $category = Category::where('id', $id)->delete();
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Categoria eliminada correctamente.',
                        'category' => $category
                    );

                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'ERROR: La categoria tiene posts asociados.'
                    );

                }

            } else {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'ERROR: La categoria no existe.'
                );
            }

        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'ERROR: No se ha eliminado la categoria.'
            );
        }

        return $data;
    }

}
