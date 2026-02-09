<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchRoomType;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getRoomTypesByBranch($branchId)
    {
        $roomTypes = BranchRoomType::query()
            ->where('branch_id', $branchId)
            ->where('is_active', 1)
            ->with('roomType')
            ->get();

        return response()->json($roomTypes);
    }
}
