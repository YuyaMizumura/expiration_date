<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use App\Models\Item as ItemModels;
use App\Http\Requests\Template\DeleteRequest;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeleteRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        $deleteData = ItemModels::find($validatedData['id']);
        $deleteData->delete();

        // レスポンスを返す
        return redirect()->route('template')->with('success', '商品テンプレートが削除されました。');
    }
}
