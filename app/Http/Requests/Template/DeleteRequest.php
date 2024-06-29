<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;

class DeleteRequest extends FormRequest
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
            'id' => [
                'required',
                function ($attribute, $value, $fail)
                {
                    if(!Item::where('id', $value)->exists())
                    {
                        $fail('データが存在しません');
                    }
                },
            ],
            'u_id' => [
                'required',
                function ($attribute, $value, $fail)
                {
                    if($value != Auth::id()) { $fail('このデータは削除できません'); }
                },
            ],
        ];
    }
}
