<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category as CategoryModels;
use App\Http\Requests\Category\PostRequest;
use Illuminate\Support\Facades\Auth;

class PostCreateController extends Controller
{
    public function __invoke(PostRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        // カテゴリテーブル
        $table          = new CategoryModels();
        $table->u_id    = Auth::id();
        $table->name    = $validatedData['c_name'];
        $table->parent  = $validatedData['c_parent'];
        $table->save();

        // レスポンスを返す
        return redirect()->route('category')->with('success', 'カテゴリーが登録されました。');
    }
}
