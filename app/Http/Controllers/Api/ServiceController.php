<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Lấy tất cả service theo category
     */
    public function byCategory(Request $request)
    {
        // Kiểm tra category có tồn tại
        $category = ServiceCategory::find($request->service_category_id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục dịch vụ không tồn tại',
                'data' => []
            ], 404);
        }

        $services = Service::active()
            ->where('service_category_id', $request->service_category_id)
            ->select('id', 'title', 'duration', 'price', 'sale_price')
            ->orderBy('title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }
}
