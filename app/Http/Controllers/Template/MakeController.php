<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Category as CategoryModels;
use App\Models\Item as ItemModels;
use Inertia\Inertia;

class MakeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $editData = null;
        if($request->input())
        {
            $editData = ItemModels::select('id','u_id','name','cat','price','ex_date_flg')->
                where([
                    ['u_id', '=', Auth::id()],
                    ['id', '=', $request->input('id')],
                ])->first();
        }

        // カテゴリー項目　select用
        $categories = CategoryModels::select('id','name')->
            where([['u_id', '=', Auth::id()]])->get();

        return Inertia::render('Template/Make', [
            'categories' => $categories,
            'editData'   => $editData,
        ]);
    }
}
