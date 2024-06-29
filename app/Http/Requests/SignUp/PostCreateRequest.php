<?php

namespace App\Http\Requests\SignUp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use App\Models\Item as ItemModels;

class PostCreateRequest extends FormRequest
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
            's_date'        => 'required|date',
            's_cat'         => 'required|integer',
            's_tax'         => 'numeric',
            's_description' => '',
            's_filePath'    => '',
        ];

        // 商品入力
        $rules['s_items'] = [
            function ($attribute, $values, $fail)
            {
                foreach($values as $key => $value) {

                    $checkItemModel = ItemModels::where([['id','=', $value['id']],['cat', '=', $this->input('s_cat')]])->first();

                    // 商品が存在しない場合
                    if(!$checkItemModel){ $fail('存在しない商品があります'); }
                    // もし商品に賞味期限が必要だった場合でdateがない場合
                    if($checkItemModel->ex_date && !$value['date']) { $fail('賞味期限を設定してください'); }
                    // 個数設定が整数ではない場合
                    if(!is_int(intval($value['stock']))) { $fail('個数は整数で指定してください'); }
                }
            },
        ];

        // s_fileが存在する場合のみ、バリデーションルールを追加
        if($this->file('s_file'))
        {
            $rules['s_file'] = [
                File::image()
                    ->max(10 * 1024)  // 最大サイズ 10MB
                    ->dimensions(Rule::dimensions()->maxWidth(4000)->maxHeight(4000))
            ];
        }
        else
        {
            $rules['s_file'] = '';
        }
    
        return $rules;
    }
}
