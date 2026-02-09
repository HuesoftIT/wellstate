<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll() {
        
    }
    /**
     * Lấy tất cả service theo category
     */
    public function getByCategory($categoryId)
    {
        // Kiểm tra category có tồn tại
        $category = ServiceCategory::find($categoryId);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục dịch vụ không tồn tại',
                'data' => []
            ], 404);
        }

        // Lấy tất cả service đang active trong category
        $services = Service::where('service_category_id', $categoryId)
            ->where('is_active', true)
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }
}
