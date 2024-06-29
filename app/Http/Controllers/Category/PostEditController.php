<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category as CategoryModels;
use App\Http\Requests\Category\PostRequest;

class PostEditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostRequest $request)
    {
        $validateData = $request->validated();

        // カテゴリテーブル 変更
        $editData = CategoryModels::findOrFail($request->input('id'));
        $editData->name = $validateData['c_name'];
        $editData->parent = $validateData['c_parent'];
        $editData->save();

        // レスポンスを返す
        return redirect()->route('category')->with('success', 'カテゴリーが変更されました。');
    }
}
