<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'product_image' => ['required', 'mimes:jpeg,png'],
            'category_id' => ['required'],
            'condition_id' => ['required'],
            'product_name' => ['required'],
            'description' => ['required'],
            'price' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'product_image.required' => '商品画像をアップロードしてください',
            'product_image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'product_name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'price.required' => '商品名を入力してください',
        ];
    }
}
