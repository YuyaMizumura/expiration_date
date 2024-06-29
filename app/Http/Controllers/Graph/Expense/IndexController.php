<?php

namespace App\Http\Controllers\Graph\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Consts\Constants;
use Illuminate\Support\Facades\Auth;

use App\Models\Expense as ExpenseModels;
use App\Models\Category as CategoryModels;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // シェアids
        $shareUserData = Auth::user();
        $shareId = json_decode($shareUserData->sharer_ids);

        // 年月
        $now_year = date('Y');
        $now_month = date('n');

        if($request->query())
        {
            $getExpenses = ExpenseModels::select('id','u_id','total_price','date','cat','stock_item')->orderBy('date', 'asc')
                ->whereIn('u_id', $shareId)
                ->whereYear('date', $request->input('year'))
                ->whereMonth('date', $request->input('month'));
        }
        else
        {
            $getExpenses = ExpenseModels::select('id','u_id','total_price','date','cat','stock_item')->orderBy('date', 'asc')
                ->whereIn('u_id', $shareId)
                ->whereYear('date', $now_year)
                ->whereMonth('date', $now_month);
        }

        // リクエストの値でフィルタリング
        $getAry = [
            'searchDate' => [
                'now' => ['year' => $now_year, 'month' => $now_month],
                'prev' => ['year' => date('Y', strtotime('-1 month')), 'month' => date('n', strtotime('-1 month'))],
                'next' => ['year' => date('Y', strtotime('+1 month')), 'month' => date('n', strtotime('+1 month'))],
            ],
        ];

        if($request->query())
        {
            $getYear = $request->input('year'); // getで取得したyear
            $getMonth = $request->input('month'); // getで取得したmonth

            // 現在の年月をもとにDateTimeオブジェクトを作成
            $currentDate = DateTime::createFromFormat('Y-n', "$getYear-$getMonth");

            // 前月のDateTimeオブジェクトを作成
            $prevMonth = clone $currentDate;
            $prevMonth->modify('-1 month');

            // 翌月のDateTimeオブジェクトを作成
            $nextMonth = clone $currentDate;
            $nextMonth->modify('+1 month');
            
            // 検索条件配列を作成
            $getAry = [
                'year' => $getYear,
                'month' => $getMonth,
                'cat' => $request->input('cat'),
                'searchDate' => [
                    'now'  => ['year' => $now_year, 'month' => $now_month],
                    'prev' => ['year' => $prevMonth->format('Y'), 'month' => $prevMonth->format('n')],
                    'next' => ['year' => $nextMonth->format('Y'), 'month' => $nextMonth->format('n')],
                ],
            ];
        }

        $getExpenses = $getExpenses->get();

        // 支出管理テーブル
        $tmpExpenses = [];
        foreach($getExpenses as $key => $item)
        {
            $tmpCategory = CategoryModels::select('id','u_id','name','parent')->where('id', $item->cat)->first();

            if (!isset($tmpExpenses[$tmpCategory->parent])) { $tmpExpenses[$tmpCategory->parent] = 0; }
            $tmpExpenses[$tmpCategory->parent] += $item->total_price;
        }

        $expenses = [];
        foreach(Constants::parentCatAry as $key => $item)
        {
            $expenses[] = (isset($tmpExpenses[$key])) ? $tmpExpenses[$key] : 0;
        }

        // 検索用 カテゴリー
        $searchAry  = [];
        $constYear = Constants::Year;
        $searchAry['category'] = CategoryModels::select('id','u_id','name','parent')->whereIn('u_id', $shareId)->get();

        foreach(range(0, 1) as $i) { $searchAry['date']['year'][] = ['id' => $constYear + $i, 'name' => ($constYear + $i).'年']; };
        foreach(range(1, 12) as $i) { $searchAry['date']['month'][] = ['id' => $i, 'name' => $i.'月']; }

        return Inertia::render('Graph/Expense',[
            'parentCat' => Constants::parentCatAry,
            'expenses'  => $expenses,                   // 支出データ
            'searchAry' => $searchAry,                  // 検索用 配列
            'getAry'    => $getAry,                     // getデータ配列
        ]);
    }
}
