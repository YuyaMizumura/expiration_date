<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Consts\Constants;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModels;
use App\Models\Category as CategoryModels;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $otherUserData = Auth::user();
        $shareId = json_decode($otherUserData->sharer_ids);

        // parentCategories
        $categories = Constants::parentCatAry;

        foreach($categories as $parentCatKey => $parentCatData)
        {
            $tmpCatData = CategoryModels::select('id', 'u_id', 'name')->whereIn('u_id', $shareId)->where('parent', $parentCatData['id'])->get();

            if($tmpCatData)
            {
                foreach($tmpCatData as $catKey => $catData)
                {
                    // categories
                    $categories[$parentCatKey]['cat'][] = [
                        'id'    => $catData->id,
                        'u_id'  => $catData->u_id,
                        'name'  => $catData->name,
                    ];
                }
            }
        }

        return Inertia::render('Category/Index', [
            'categories' => $categories,
            'success' => session('success'),
            'mineId' => Auth::id(),
        ]);
    }
}