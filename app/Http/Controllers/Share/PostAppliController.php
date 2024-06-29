<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\PostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModels;

use App\Mail\Share\AppliMail;
use Illuminate\Support\Facades\Mail;

class PostAppliController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        $editUserData = UserModels::where('email',$validatedData['s_email'])->first();

        // ユーザーIDの取得
        $userId = Auth::id();
        $applicantIds = $editUserData->applicant_ids
            ? json_decode($editUserData->applicant_ids, true)
            : [];
        $sharerIds = $editUserData->sharer_ids
            ? json_decode($editUserData->sharer_ids)
            : [];
        
        if(!in_array($userId, $applicantIds) && !in_array($userId, $sharerIds)) { $applicantIds[] = $userId; }
        $editUserData->applicant_ids = json_encode($applicantIds);

        $editUserData->save();

        // $order['name'] = '田中太郎';
        // Mail::to($editUserData->email)->send(new AppliMail());

        // レスポンスを返す
        return redirect()->route('share')->with('success', 'シェアの申請が完了しました');
    }
}
