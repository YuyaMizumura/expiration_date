<?php

namespace App\Http\Controllers\SignUp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category as CategoryModels;
use App\Models\Expense as ExpenseModels;
use App\Models\Item as ItemModels;
use App\Models\StockItem as StockItemModels;
use App\Models\Expiration as ExpirationModels;
use Inertia\Inertia;
use App\Consts\Constants;

class MakeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if($request->input('id'))
        {
            $editData['id'] = $request->input('id');
            $editData['expense'] = ExpenseModels::find($request->input('id'));
            // dateの整形
            $editData['expense']->date = date('Y-n-j', strtotime($editData['expense']->date));

            // 商品ストック データ取得
            $editData['items'] = StockItemModels::whereIn('id', json_decode($editData['expense']['stock_item']))->get();

            // dateの整形
            foreach($editData['items'] as $key => $item)
            {
                $editData['itemAry'][] = $item['i_id'];                                         // i_id保存
                $editData['items'][$key]['ex_date'] = $editData['items'][$key]['ex_date'];      // dateの形式変更

                // expirationを取得
                $tmpExpiration = ExpirationModels::where([
                    ['exp_id', '=', $request->input('id')],
                    ['i_id', '=', $item['i_id']],
                ])->get();
            }

            // expirationのi_id取得
            foreach($tmpExpiration as $key => $item) { $editData['expirationAry'][] = $item->i_id; }

            $tempImgPath = ($editData['expense']->img) ? asset('storage/'.$editData['expense']->img) : '';

            // カテゴリー項目　select用
            $categories = CategoryModels::where([
                ['u_id', '=', Auth::id()],
                ['id', '=', $editData['expense']->cat],
            ])->get();
        }
        else
        {
            $editData = null;
            $tempImgPath = '';

            // カテゴリー項目　select用
            $categories = CategoryModels::where([
                ['u_id', '=', Auth::id()],
            ])->get();
        }

        $items = $calcItems = $searchItems = [];
        $tmpItems = ItemModels::all();
        foreach($tmpItems as $key => $item)
        {
            $items[$item['cat']][] = [
                'id'    => $item['id'],
                'name'  => $item['name'].' （'.number_format($item['price']).'円）',
                'price' => $item['price'],
            ];

            $searchItems[$item['id']] = [
                'price' => $item['price'],
                'ex_date' => $item['ex_date_flg'],
            ];
        }

        $defData = [
            'date' => date('Y-m-d'),
            'tax' => Constants::Tax,
        ];

        return Inertia::render('SignUp/Make', [
            'categories'    => $categories,
            'editData'      => $editData,
            'items'         => $items,
            'searchItems'   => $searchItems,
            'tempImgPath'   => $tempImgPath,
            'defData'       => $defData,
        ]);
    }
}