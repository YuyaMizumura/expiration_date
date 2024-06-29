<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\GetJudgeRequest;
use App\Models\User as UserModels;
use Illuminate\Support\Facades\Auth;

class GetJudgeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetJudgeRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        // $mineUserModel = UserModels::where('id', Auth::id())->first();
        $mineUserModel = Auth::user();
        $appliUserModel = UserModels::where('id', $validatedData['id'])->first();

        $appliAry = json_decode($mineUserModel->applicant_ids, true);

        if(in_array($validatedData['id'], $appliAry)) { $deleteAppliAry = array_values(array_diff($appliAry, [$validatedData['id']])); }
        if($validatedData['ans']) // 承諾
        {
            $shareAry = ($mineUserModel->sharer_ids) ? json_decode($mineUserModel->sharer_ids) : [];
            $shareAry[] = intval($validatedData['id']);

            $mineUserModel->applicant_ids = json_encode($deleteAppliAry);
            $mineUserModel->sharer_ids = json_encode($shareAry);
            $mineUserModel->save();

            $appliShareAry = ($appliUserModel->sharer_ids) ? json_decode($appliUserModel->sharer_ids) : [];
            $appliShareAry[] = intval(Auth::id());
            $appliUserModel->sharer_ids = json_encode($appliShareAry);
            $appliUserModel->save();

            return redirect()->route('share')->with('success', '共有を承認しました');
        }
        else // 拒否
        {
            $mineUserModel->applicant_ids = json_encode($deleteAppliAry);
            $mineUserModel->save();
            return redirect()->route('share')->with('success', '共有を拒否しました');
        }

    }
}
