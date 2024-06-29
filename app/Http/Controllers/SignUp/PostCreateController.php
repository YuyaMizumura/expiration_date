<?php

namespace App\Http\Controllers\SignUp;

use App\Http\Controllers\Controller;
use App\Models\Expense as ExpenseModels;
use App\Models\Item as ItemModels;
use App\Models\StockItem as StockItemModels;
use App\Models\Expiration as ExpirationModels;
use Illuminate\Http\Request;
use App\Http\Requests\SignUp\PostCreateRequest;
use Illuminate\Support\Facades\Auth;

class PostCreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostCreateRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        // 現在の日時を使用してYmdHis形式のファイル名を生成
        $timestamp = now()->format('YmdHis');
        $file = $validatedData['s_file'];

        if($file)
        {
            // ファイルの拡張子を取得
            $extension = $file->getClientOriginalExtension();

            // 新しいファイル名を生成
            $newFileName = $timestamp.'.'.$extension;

            // ファイルをアップロードするディレクトリのパスを設定
            $uploadDirectory = 'uploads/signup/';

            // ファイルを指定されたディレクトリに新しいファイル名で保存
            $file->storeAs($uploadDirectory, $newFileName);

            $dbUpDirectoryPath = $uploadDirectory.$newFileName;
        }
        else
        {
            $dbUpDirectoryPath = null;
        }

        // itemsのidと合計金額を取得・計算する
        $tmpPrice = 0;
        foreach($validatedData['s_items'] as $key => $item)
        { 
            $tmpItemData = ItemModels::where('id', $item['id'])->first();

            // expenseに値を保存する用
            $tmpPrice += $tmpItemData->price * $item['stock'];
        }

        // stock_itemに保存
        foreach($validatedData['s_items'] as $key => $item)
        { 
            $tmpItemData = ItemModels::where('id', $item['id'])->first();

            $stockItemTable             = new StockItemModels();
            $stockItemTable->u_id       = Auth::id();
            $stockItemTable->i_id       = $item['id'];
            $stockItemTable->name       = $tmpItemData->name;
            $stockItemTable->cat        = $tmpItemData->cat;
            $stockItemTable->stock      = $item['stock'];
            $stockItemTable->price      = $tmpItemData->price;
            $stockItemTable->ex_date    = (isset($item['date'])) ? $item['date'] : NULL;
            $stockItemTable->save();

            $tmpStockItems[] = $stockItemTable->id; // 保存したデータのidを代入
        }

        // expenseテーブルに保存
        $expenseTable               = new ExpenseModels();
        $expenseTable->u_id         = Auth::id();
        $expenseTable->date         = $validatedData['s_date'];
        $expenseTable->cat          = $validatedData['s_cat'];
        $expenseTable->price        = $tmpPrice;
        $expenseTable->total_price  = $tmpPrice + ($tmpPrice * $validatedData['s_tax'] / 100);
        $expenseTable->tax          = $validatedData['s_tax'];
        $expenseTable->stock_item   = json_encode($tmpStockItems, true);
        $expenseTable->description  = $validatedData['s_description'];
        $expenseTable->img          = ($validatedData['s_filePath']) ? $validatedData['s_filePath'] : $dbUpDirectoryPath;
        $expenseTable->save();

        // stockItemデータにexp_idを保存
        foreach($tmpStockItems as $key => $s_id)
        {
            $stockItemTable = StockItemModels::where('id', $s_id)->first();
            $stockItemTable->exp_id = $expenseTable->id;
            $stockItemTable->save();
        }

        // 賞味期限があったitem
        foreach($validatedData['s_items'] as $key => $item)
        {
            $ItemData = ItemModels::where('id', $item['id'])->first();

            if($ItemData->ex_date_flg)
            {
                $expirationTable = new ExpirationModels;
                $expirationTable->u_id      = Auth::id();
                $expirationTable->i_id      = $item['id'];
                $expirationTable->exp_id    = $expenseTable->id;
                $expirationTable->name      = $ItemData['name'];
                $expirationTable->cat       = $validatedData['s_cat'];
                $expirationTable->date      = ($item['date']) ? $item['date']: NULL;
                $expirationTable->save();
            }
        }

        // レスポンスを返す
        return redirect()->route('dashboard')->with('success', 'データが登録されました。');
    }
}