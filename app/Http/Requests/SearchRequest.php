<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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

        $today = date("Y-m-d");
              
        return [
            // 日報記入事項のバリデーション
            'visit_contents' => 'nullable | max:100',
            'before' => 'nullable | date | before_or_equal:' . $today,
            'after' => 'nullable | date | before_or_equal:' . $today,
            'newdays' => 'nullable | integer | min:-1 | max:30',
        ];
    }

    //[ *3.追加：Validationメッセージを設定（省略可） ]
    //function名は必ず「messages」となります。
    public function messages()
    {
        return [
            // 日本語バリデーションのエラーメッセージ

        ];
    }

    //400エラーを返したい場合

    // protected function failedValidation(Validator $validator)
    // {
    //     $res = response()->json([
    //         'status' => 400,
    //         'errors' => $validator->errors(),
    //     ], 400);
    //     throw new HttpResponseException($res);
    // }
}
