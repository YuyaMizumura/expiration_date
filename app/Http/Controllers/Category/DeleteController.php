<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category as CategoryModels;
use App\Http\Requests\Category\DeleteRequest;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeleteRequest $request, $id)
    {
        $deleteData = CategoryModels::find($id);
        $deleteData->delete();

        // レスポンスを返す
        return redirect()->route('category')->with('success', 'カテゴリーが削除されました。');
    }
}
