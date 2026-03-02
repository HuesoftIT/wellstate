<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    //
    public function getAvailableTimes(Request $request)
    {
        $branch = Branch::findOrFail($request->branch_id);
        

        return response()->json([
            'open_time'  => substr($branch->open_time, 0, 5),
            'close_time' => substr($branch->close_time, 0, 5),
        ]);
    }
}
