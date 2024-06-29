<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;

class GetJudgeRequest extends FormRequest
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

        $applicantIds = json_decode(auth()->user()->applicant_ids) ?? [];

        return [
            'ans' => [
                'required',
                'in:0,1',  // 0または1の値であること
            ],
            'id' => [
                'required',
                function ($attribute, $value, $fail) use ($applicantIds) {
                    // 'id' が自分の applicant_ids に含まれているかチェック

                    if (!in_array($value, $applicantIds)) {
                        $fail('指定されたIDは不正なIDです。');
                    }
                },
            ],
        ];
    }
}
