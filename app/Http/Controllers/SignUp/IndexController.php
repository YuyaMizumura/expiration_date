<?php

namespace App\Http\Controllers\SignUp;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Template as TemplateModels;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // テンプレートテーブル
        $templates = TemplateModels::where([
            ['u_id', '=', Auth::id()]
        ])->get();

        // 画像のパスの変更
        foreach($templates as $key => $item)
        { 
            $templates[$key]->img = ($item['img']) ? asset('storage/'.$item['img']) : '';
        }

        return Inertia::render('SignUp/Index', [
            'templates' => $templates,
        ]);
    }
}
