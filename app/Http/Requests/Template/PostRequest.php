<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;
// use Illuminate\Validation\Rules\File;

class PostRequest extends FormRequest
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
        $rules = [
            'name'   => 'required|max:30',
            'cat'    => 'required|integer',
            'price'  => 'required|integer|min:1',
            'action' => 'required|string',
            'ex_date' => 'required',
        ];

        return $rules;
    }
}