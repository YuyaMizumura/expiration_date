<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\GetCancellRequest;
use App\Models\User as UserModels;
use Illuminate\Support\Facades\Auth;

class GetCancellController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetCancellRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        $mineUser = UserModels::where('id', Auth::id())->first();
        $shareUser = UserModels::where('id', $validatedData['id'])->first();

        $mineUser->sharer_ids = array_diff(json_decode($mineUser->sharer_ids, true), [$validatedData['id']]);
        $mineUser->save();

        $shareUser->sharer_ids = array_diff(json_decode($shareUser->sharer_ids, true), [Auth::id()]);
        $shareUser->save();

        return redirect()->route('share')->with('success', '共有を解除しました');
    }
}
