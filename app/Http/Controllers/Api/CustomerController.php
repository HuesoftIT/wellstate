<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $membershipIds = $request->input('membership_levels', []);

        $query = Customer::query()
            ->active();

        if (!empty($membershipIds)) {
            $query->whereIn('membership_id', $membershipIds);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        return $query
            ->orderBy('name')
            ->limit(20)
            ->get([
                'id',
                'name',
                'phone',
                'membership_id',
            ]);
    }
}
