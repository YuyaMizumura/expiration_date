<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Consts\Constants;
use App\Models\Item as ItemModels;
use App\Models\Category as CategoryModels;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $items = [];

        $items = Constants::parentCatAry;

        foreach($items as $parentCatKey => $parentCatData)
        {
            $tmpCatData = CategoryModels::select('id','name')->where('parent', $parentCatData['id'])->get();

            if($tmpCatData)
            {
                foreach($tmpCatData as $catKey => $catData)
                {
                    // categories
                    $items[$parentCatKey]['cat'][] = [
                        'name'  => $catData->name,
                    ];

                    // items
                    $tmpItems = ItemModels::select('id','u_id','name','price','ex_date_flg')->where('cat', $catData->id)->get();

                    if($tmpItems)
                    {
                        foreach($tmpItems as $itemKey => $itemData)
                        {
                            $items[$parentCatKey]['cat'][$catKey]['item'][] = [
                                'id'    => $itemData->id,
                                'u_id'  => $itemData->u_id,
                                'name'  => $itemData->name,
                                'price' => $itemData->price,
                                'exp'   => $itemData->ex_date_flg,
                            ];
                        }
                    }
                }
            }
        }

        return Inertia::render('Template/Index', [
            'items'         => $items,
            'success'       => session('success'),
            'mineId'        => Auth::id(),
        ]);
    }
}
