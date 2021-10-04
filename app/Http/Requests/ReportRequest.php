<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReportRequest extends FormRequest
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

    public function validationData()
    {
        $all =  $this->all();
        if (isset($all['visit_contents'])) {
            $all['visit_contents'] = preg_replace("/\r\n/", "\n", $all['visit_contents']);
        }
        if (isset($all['next_step'])) {
            $all['next_step'] = preg_replace("/\r\n/", "\n", $all['next_step']);
        }
        
        return $all;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $today = Carbon::now()->format('Y-m-d\TH:i');

        return [
            // 日報記入事項のバリデーション
            'visit_date' => 'required | date | before:' . $today,
            'client' => 'required',
            'client_dep' => 'required | max:20',
            'client_name' => 'required | max:20',
            'title' => 'required | max:60',
            'visit_contents' => 'required | max:360',
            'next_step' => 'required | max:360',
            
        ];
    }

    //[ *3.追加：Validationメッセージを設定（省略可） ]
    //function名は必ず「messages」となります。
    public function messages()
    {
        return [
            'visit_date.before' => '訪問日時には、現在時刻より前の日付をご利用ください。',
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
