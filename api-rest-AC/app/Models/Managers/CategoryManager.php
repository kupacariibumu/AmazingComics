<?php

namespace App\Models\Managers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Response;
use App\Models\Category;


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

}
