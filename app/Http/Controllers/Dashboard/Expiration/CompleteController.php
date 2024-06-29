<?php

namespace App\Http\Controllers\Dashboard\Expiration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CompleteRequest;
use App\Models\Expiration as ExpirationModels;

class CompleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CompleteRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        $completeData = ExpirationModels::find($validatedData['id']);
        $completeData->complete_flg = 1;
        $completeData->save();

        // レスポンスを返す
        return redirect()->route('dashboard.expiration')->with('success', '完了処理されました。');
    }
}
