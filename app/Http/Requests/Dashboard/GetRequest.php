<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
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
        $rules = [];
        if($this->query())
        {
            $rules = [
                'year' => 'required|numeric',
                'month' => 'required|numeric|min:1|max:12',
                'cat' => 'nullable|integer',
            ];
        }

        return $rules;
    }
}
