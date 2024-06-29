<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModels;
use App\Models\Category as CategoryModels;
use App\Models\ParentCategory as ParentCategoryModels;
use App\Consts\Constants;

class MakeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $otherUserData = Auth::user();
        $shareId = json_decode($otherUserData->sharer_ids);

        // parent category
        $parentCategory = Constants::parentCatAry;

        if($request->input()) // edit
        {
            $editData = CategoryModels::select('id','name','parent')->find($request->input('id'));

            return Inertia::render('Category/Make', [
                'parentCats' => $parentCategory,
                'editData' => $editData,
            ]);
        }
        else // create
        {
            return Inertia::render('Category/Make', [
                'parentCats' => $parentCategory,
            ]);
        }
    }
}
