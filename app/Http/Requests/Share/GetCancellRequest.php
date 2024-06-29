<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;

class GetCancellRequest extends FormRequest
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
        $sharerIds = json_decode(auth()->user()->sharer_ids) ?? [];

        return [
            'id' => [
                'required',
                function ($attribute, $value, $fail) use ($sharerIds)
                {
                    // 'id' が自分の sharer_ids に含まれているかチェック
                    if (!in_array($value, $sharerIds)) { $fail('指定されたIDは不正なIDです。'); }
                },
            ],
        ];
    }
}