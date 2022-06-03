<?php

namespace App\Http\Requests\Categories;

use App\Category;
use Illuminate\Foundation\Http\FormRequest;

class NewCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'is_physical' => 'required|in:0,1',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }

    public function persist($id = null)
    {
        if (is_null($id)) {
            $categoryInsert = new Category;
        } else{
            $categoryInsert = Category::findOrFail($id);
        }
        $categoryInsert -> name = $this -> name;
        $categoryInsert -> is_physical = $this -> is_physical;
        $categoryInsert -> parent_id = $this -> parent_id;
        $categoryInsert -> save();
    }
}
