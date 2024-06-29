<?php

namespace App\Http\Controllers\Dashboard\Expiration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dashboard\GetRequest;

use App\Models\Expiration as ExpirationModels;
use App\Models\Expense as ExpenseModels;
use App\Models\Category as CategoryModels;
use App\Models\User as UserModels;

use App\Consts\Constants;

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

        // 賞味期限テーブル
        $now_year = date('Y');
        $now_month = date('n');

        if($request->query())
        {
            $getExpirations = ExpirationModels::select('id','u_id','exp_id','name','cat','date','complete_flg','updated_at')->orderBy('date', 'asc')
                ->whereIn('u_id', $shareId)
                ->whereYear('date', $request->input('year'))
                ->whereMonth('date', $request->input('month'))
                ->where('complete_flg', 0);
        }
        else
        {
            $getExpirations = ExpirationModels::select('id','u_id','exp_id','name','cat','date','complete_flg','updated_at')->orderBy('date', 'asc')
                ->whereIn('u_id', $shareId)
                ->whereYear('date', $now_year)
                ->whereMonth('date', $now_month)
                ->where('complete_flg', 0);
        }

        // リクエストの値でフィルタリング
        $getAry = [
            'searchDate' => [
                'now'  => ['year' => $now_year, 'month' => $now_month],
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

            // カテゴリー
            if($request->has('cat') && $request->input('cat') !== null) { $getExpirations->where('cat', $request->input('cat')); }
        }

        $getExpirations = $getExpirations->get();

        $expirations = [];
        foreach($getExpirations as $key => $item)
        {
            // 日付をパースして取得
            $date = Carbon::parse($item['date']);
            $month = $date->year.'年'.$date->month;
            $day = $date->day;

            // 今日から何日間離れているのかを計算
            $untilDay['day'] = Carbon::today()->diffInDays($date);

            if     ($untilDay['day'] >= 7) { $untilDay['color'] = 'black'; } 
            elseif ($untilDay['day'] >= 3) { $untilDay['color'] = 'yellow'; } 
            else                           { $untilDay['color'] = 'red'; }

            // 出費データの登録日
            // $expense_date = ExpenseModels::select('date')->where('id', $item['exp_id'])->first()->date;

            $expirations[$item['id']] = [
                'user'          => UserModels::where('id', $item['u_id'])->first()['name'],
                'name'          => $item['name'],
                'until_day'     => $untilDay,
                // 'cat'        => CategoryModels::find($item['cat'])->name,
                'price'         => number_format($item['price']),
                'total_price'   => number_format($item['total_price']),
                'date'          => [
                    'day' => $day,
                ],
                'expense_date'  => date('n月j日', strtotime($item['updated_at'])),
            ];
        }

        // 検索用 カテゴリー
        $searchAry  = [];
        $constYear = Constants::Year;
        $searchAry['category'] = CategoryModels::select('id','name')->whereIn('u_id', $shareId)->get();

        foreach(range(0, 1) as $i) { $searchAry['date']['year'][] = ['id' => $constYear + $i, 'name' => ($constYear + $i).'年']; };
        foreach(range(1, 12) as $i) { $searchAry['date']['month'][] = ['id' => $i, 'name' => $i.'月']; }

        return Inertia::render('Dashboard/Expiration', [
            'success'       => session('success'),  // session successデータ
            'expirations'   => $expirations,        // 賞味期限データ
            'searchAry'     => $searchAry,          // 検索用 配列
            'getAry'        => $getAry,             // getデータ配列
            'mineId'        => Auth::id(),          // 自分のid
        ]);
    }
}
