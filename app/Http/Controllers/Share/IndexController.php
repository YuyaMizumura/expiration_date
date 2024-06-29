<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $dd = User::where('id', 3)->first();
        // $dd->sharer_ids = [3];
        // $dd->save();

        $mineApplicant = json_decode(Auth::user()->applicant_ids, true);
        $mineSharer = json_decode(Auth::user()->sharer_ids, true);

        $applicants = $shares = [];
        if($mineApplicant)
        {
            foreach($mineApplicant as $id) { $applicants[] = User::find($id); }
        }
        if($mineSharer)
        {
            foreach($mineSharer as $id) {
                if(Auth::id() !== $id) { $shares[] = User::find($id); }
            }
        }

        return Inertia::render('Share/Index',[
            'applies' => $applicants,
            'shares' => $shares,
            'success' => session('success'),
        ]);
    }
}