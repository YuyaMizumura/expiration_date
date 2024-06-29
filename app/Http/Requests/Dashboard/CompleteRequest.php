<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'u_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    // 現在のユーザーを取得
                    $mineData = Auth::user();

                    // 現在のユーザーの sharer_ids カラムを配列に変換
                    $sharerIds = json_decode($mineData->sharer_ids, true);

                    // 配列に u_id が含まれているかどうかを判定
                    if (!in_array($value, $sharerIds)) {
                        $fail('指定された u_id は sharer_ids 内に存在しません。');
                    }
                },
            ],
            'id' => [
                'required',
            ]
        ];
    }
}
