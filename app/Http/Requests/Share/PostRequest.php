<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PostRequest extends FormRequest
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
        return [
            's_email' => [
                'required',
                'email',
                'exists:users,email',
                function ($attribute, $value, $fail) {

                    // 自分のメールアドレスを無効とする
                    if ($value === auth()->user()->email) {
                        $fail('自分のメールアドレスは無効です。');
                    }

                    // 認証されたユーザーのIDを取得
                    $authUserId = Auth::id();

                    // `email` に対応するユーザーを取得
                    $fromUser = Auth::user();
                    $toUser = User::where('email', $value)->first();

                    // `applicant_ids` と `sharer_ids` を配列として取得
                    $fromApplicantIds = $fromUser->applicant_ids ? json_decode($fromUser->applicant_ids) : [];
                    $fromSharerIds = $fromUser->sharer_ids ? json_decode($fromUser->sharer_ids) : [];

                    $toApplicantIds = $toUser->applicant_ids ? json_decode($toUser->applicant_ids) : [];
                    $toSharerIds = $toUser->sharer_ids ? json_decode($toUser->sharer_ids) : [];

                    // 認証されたユーザーのIDが `applicant_ids` または `sharer_ids` に存在するか確認
                    if (
                        in_array($toUser->id, $fromApplicantIds) ||
                        in_array($toUser->id, $fromSharerIds) ||
                        in_array($authUserId, $toApplicantIds) ||
                        in_array($authUserId, $toSharerIds)
                    ) {
                        // バリデーションエラーを発生させます。
                        $fail('あなたは既に共有申請済です。');
                    }
                },
            ],
        ];
    }
}
