<?php

namespace App\Http\Controllers\Dashboard\Expense;

use App\Http\Controllers\Controller;
use DateTime;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\GetRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense as ExpenseModels;
use App\Models\Category as CategoryModels;
use App\Models\User as UserModels;

use App\Consts\Constants;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetRequest $request)
    {
        // シェアids
        $shareUserData = Auth::user();
        $shareId = json_decode($shareUserData->sharer_ids);

        // 年月
        $now_year = date('Y');
        $now_month = date('n');

        // 支出管理テーブル
        if($request->query())
        {
            $getExpenses = ExpenseModels::select('id','u_id','price','total_price','cat','date')->orderBy('date', 'asc')
                ->whereIn('u_id', $shareId)
                ->whereYear('date', $request->input('year'))
                ->whereMonth('date', $request->input('month'));
        }
        else
        {
            $getExpenses = ExpenseModels::orderBy('date', 'asc')
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
                    'now' => ['year' => $now_year, 'month' => $now_month],
                    'prev' => ['year' => $prevMonth->format('Y'), 'month' => $prevMonth->format('n')],
                    'next' => ['year' => $nextMonth->format('Y'), 'month' => $nextMonth->format('n')],
                ],
            ];

            // カテゴリー
            if($request->has('cat') && $request->input('cat') !== null) { $getExpenses->where('cat', $request->input('cat')); }
        }

        $getExpenses = $getExpenses->get();

        $expenses = [];
        foreach($getExpenses as $key => $item)
        {
            // 日付をパースして取得
            $date = Carbon::parse($item['date']);

            $month = $date->year.'年'.$date->month;
            $day = $date->day;

            $expenses[$item['id']] = [
                'id'    => $item['id'],
                'user'  => UserModels::where('id', $item['u_id'])->first()['name'],
                'cat'   => CategoryModels::find($item['cat'])->name,
                'price' => number_format($item['price']),
                'total_price' => number_format($item['total_price']),
                'date' => [
                    'day' => $day,
                ],
            ];

            // 画像のパスの変更
            $expenses[$item['id']]['img'] = ($item['img']) ? asset('storage/'.$item['img']) : '';
        }

        // 検索用 カテゴリー
        $searchAry  = [];
        $constYear = Constants::Year;
        $searchAry['category'] = CategoryModels::select('id','name')->whereIn('u_id', $shareId)->get();

        foreach(range(0, 1) as $i) { $searchAry['date']['year'][] = ['id' => $constYear + $i, 'name' => ($constYear + $i).'年']; };
        foreach(range(1, 12) as $i) { $searchAry['date']['month'][] = ['id' => $i, 'name' => $i.'月']; }

        return Inertia::render('Dashboard/Expense', [
            'success'       => session('success'),  // session successデータ
            'expenses'      => $expenses,           // 賞味期限データ
            'searchAry'     => $searchAry,          // 検索用 配列
            'getAry'        => $getAry,             // getデータ配列
            'mineId'        => Auth::id(),          // 自分のid
        ]);
    }
}
