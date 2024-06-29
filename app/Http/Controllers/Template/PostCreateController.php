<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use App\Http\Requests\Template\PostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item as ItemeModels;
use Inertia\Inertia;

class PostCreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        $table              = new ItemeModels;
        $table->u_id        = Auth::id();
        $table->name        = $validatedData['name'];
        $table->cat         = $validatedData['cat'];
        $table->price       = $validatedData['price'];

        // レスポンスを返す
        switch($validatedData['action'])
        {
            case 'manu':

                // 賞味期限の有無とデータの保存
                $table->ex_date_flg = ($validatedData['ex_date']) ? 1 : 0;
                $table->save();

                // アイテム
                $addItem = [
                    'id'    => $table->id,
                    'name'  => $table->name.' （'.number_format($table->price).'円）',
                    'price' => $table->price,
                ];

                if($validatedData['ex_date']) { $addItem['ex_date'] = date('Y-n-j', strtotime($validatedData['ex_date'])); }

                return response()->json([
                    'catId'         => $validatedData['cat'],
                    'addItem'       => $addItem,
                ]);

            break;
            case 'temp':

                // 賞味期限の有無とデータの保存
                $table->ex_date_flg = $validatedData['ex_date'];
                $table->save();

                return redirect()->route('template')->with('success', '項目が登録されました。');
            break;
        }
    }
}
