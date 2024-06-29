<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use App\Models\Item as ItemModels;
use App\Http\Requests\Template\PostRequest;

class PostEditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostRequest $request)
    {
        $validatedData = $request->validated();

        // カテゴリテーブル 変更
        $editData = ItemModels::findOrFail($request->input('id'));
        $editData->name         = $validatedData['name'];
        $editData->cat          = $validatedData['cat'];
        $editData->price        = $validatedData['price'];
        $editData->ex_date_flg  = $validatedData['ex_date'];
        $editData->save();

        // レスポンスを返す
        return redirect()->route('template')->with('success', '項目が変更されました。');
    }
}
