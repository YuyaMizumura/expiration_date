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

class PostEditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PostCreateRequest $request)
    {
        // バリデーション後データ
        $validatedData = $request->validated();

        // 編集時用データ ////////////////////////////////////////////////////////////////////////////////////////////////
        $editData['id']             = $request->input('id');
        $editData['itemAry']        = $request->input('itemAry');
        $editData['expirationAry']  = $request->input('expirationAry') ? $request->input('expirationAry') : [];

        // ファイル ////////////////////////////////////////////////////////////////////////////////////////////////

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

        // 項目 ////////////////////////////////////////////////////////////////////////////////////////////////

        // itemsのidと合計金額を取得・計算する
        $tmpPrice = 0;
        foreach($validatedData['s_items'] as $key => $item)
        {
            // diff用配列
            $diffItems[] = $item['id'];

            // expenseに値を保存する用
            $tmpItemData = ItemModels::where('id', $item['id'])->first();
            $tmpPrice += $tmpItemData->price * $item['stock'];
        }

        $editItemAry = array_intersect($diffItems, $editData['itemAry']); // 変更するitem
        $addItemAry  = array_diff($diffItems, $editData['itemAry']);      // 追加するitem
        $delItemAry  = array_diff($editData['itemAry'], $diffItems);      // 削除するitem

        // 送信されたitemのループ
        $newItemIds = [];
        foreach($validatedData['s_items'] as $key => $item)
        {
            // stock_itemに保存
            if(in_array($item['id'], $addItemAry)) // stock_item 新規保存処理
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

                $newStockItemIds[] = $stockItemTable->id; // 新しいstockItemId配列
            }
            elseif(in_array($item['id'], $editItemAry)) // stock_item 既存変更処理
            {
                $stockItemTable             = StockItemModels::where('exp_id', $editData['id'])->where('i_id', $item['id'])->first();
                $stockItemTable->stock      = $item['stock'];
                $stockItemTable->ex_date    = (isset($item['date'])) ? $item['date'] : NULL;
                $stockItemTable->save();
            }

            // expenseテーブルに保存するidを代入
            $tmpStockItems[] = $stockItemTable->id;
        }

        // stock_item 削除処理 
        foreach($editData['itemAry'] as $key => $id)
        {
            if(in_array($id, $delItemAry))
            {
                $delItemTable = StockItemModels::where('exp_id', $editData['id'])->where('i_id', $id)->first();
                $delItemTable->delete();
            }
        }

        // expenseテーブルに保存 ////////////////////////////////////////////////////////////////////////////////////////////////
        $expenseTable               = ExpenseModels::findOrFail($editData['id']);
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

        // 新しいstockItemのexp_idの保存
        if(!empty($newStockItemIds))
        {
            foreach($newStockItemIds as $key => $s_id)
            {
                $addStockItemTable          = StockItemModels::where('id', $s_id)->first();
                $addStockItemTable->exp_id  = $expenseTable->id;
                $addStockItemTable->save();
            }
        }

        // 賞味期限があったitem ////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($validatedData['s_items'] as $key => $item)
        {
            $ItemData = ItemModels::where('id', $item['id'])->first();

            if($ItemData->ex_date_flg)
            {

                if(in_array($item['id'], $addItemAry)) // expiration 新規保存処理
                {
                    $expirationTable = new ExpirationModels;
                    $expirationTable->u_id      = Auth::id();
                    $expirationTable->i_id      = $item['id'];
                    $expirationTable->exp_id    = $expenseTable->id;
                    $expirationTable->name      = $ItemData['name'];
                    $expirationTable->cat       = $validatedData['s_cat'];
                    $expirationTable->date      = $item['date'];
                    $expirationTable->save();
                }
                elseif(in_array($item['id'], $editItemAry)) // expiration 既存変更処理
                {
                    $expirationTable = ExpirationModels::where([
                        ['exp_id', '=', $editData['id']],
                        ['i_id', '=', $item['id']],
                    ])->first();
                    
                    $expirationTable->date = $item['date'];
                    $expirationTable->save();
                }
            }
        }

        // expiration 削除処理 
        foreach($editData['itemAry'] as $key => $id)
        {
            if(in_array($id, $delItemAry))
            {
                $delItemTable = ExpirationModels::where('exp_id', $editData['id'])->where('i_id', $id)->first();
                $delItemTable->delete();
            }
        }

        // レスポンスを返す
        return redirect()->route('dashboard')->with('success', 'データが登録されました。');
    }
}