<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
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
            'payment_method_id' => ['required'], // 支払い方法が必須であることを確認
        ];
    }
    
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user(); // 現在ログインしているユーザーを取得

            // users テーブルの配送先情報を確認
            if (empty($user->postal_code)) {
                $validator->errors()->add('delivery_postal_code', '配送先の郵便番号が必要です');
            }

            if (empty($user->address)) {
                $validator->errors()->add('delivery_address', '配送先の住所が必要です');
            }

            if (empty($user->building)) {
                $validator->errors()->add('delivery_building', '配送先の建物名が必要です');
            }
        });
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'payment_method_id.required' => '支払い方法を選択してください',
        ];
    }
}