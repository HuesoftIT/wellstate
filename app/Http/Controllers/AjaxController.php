<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\PromotionController;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\BranchRoomType;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EmployeeService;
use App\Models\EmployeeWorkingShift;
use App\Models\Image;
use App\Models\ImageCategory;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Models\Promotion;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Slide;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AjaxController extends Controller
{
    /**
     * Gọi ajax: sẽ gọi đến hàm = tên $action
     * @param Request $action
     * @param Request $request
     * @return mixed
     */
    public function index($action, Request $request)
    {
        return $this->{$action}($request);
    }

    public function scanQR(Request $request)
    {
        $requestData = $request->all();


        $validator = Validator::make(
            $requestData,
            [
                'promotion_id' => 'required'
            ],
            [
                'promotion_id.required' => 'Mã QR không hợp lệ',
            ]
        );

        if (\Auth::id() == null) {
            $errors = "Vui lòng đăng nhập tài khoản nhân viên để quét mã QR";
            return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
        }

        if ($validator->fails()) {
            $errors = "Mã QR không hợp lệ";
            return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
        }

        try {
            $currentDate = Carbon::now()->format('Y-m-d');
            $promotionId = $request->get('promotion_id') ?? 0;
            $customerId = $request->get('customer_id') ?? 0;

            //Kiểm tra QR đã quét chưa
            $promotion = Promotion::find($promotionId);
            $customers = Customer::with(['province', 'district', 'ward'])->where('id', $customerId)->first();

            if ($promotionId == 0 || $customerId == 0 || $promotion == null || $customers == null) {
                $errors = 'Mã QR không hợp lệ';
                return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
            }

            if ($customers->active == 1) {
                $errors = 'Mã QR đã được sử dụng';
                return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
            }

            if ($promotion->date_start != null && $promotion->date_start > $currentDate) {
                $errors = 'Mã QR chưa có hiệu lực';
                return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
            }
            if ($promotion->date_end < $currentDate) {
                $errors = 'Mã QR đã hết hạn!';
                return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
            }

            //Update sử dụng chương trình
            \DB::table('customers')->where('id', $customerId)->update([
                'updated_at' => Carbon::now(),
                'active' => 1
            ]);

            return response()->json([
                'status' => true,
                'view' => view('frontends.scanQrInner', compact('customers', 'promotion'))->render(),
                'message' => 'Quét mã QR thành công'
            ]);
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            return response()->json(['status' => false, 'errors' => $errors, 'view' => view('frontends.scanQrInner', compact('errors'))->render()]);
        }
    }

    public function getStatic(Request $request)
    {
        $type = $request->type ?? 0;
        $provinceId = $request->province_id;
        $districtId = $request->district_id;
        $wardId = $request->ward_id;
        $promotionId = $request->promotion_id;
        //Thống kê theo độ tuổi
        if ($type == 1) {
            $labels = \DB::table('olds')->pluck('name')->toArray();
            $olds = \DB::table('olds')->select(['end_old', 'start_old'])->get();
            $values = [];
            foreach ($olds as $itemO) {
                $startOld = Carbon::now()->subYears($itemO->end_old)->toDateString();
                $endOld = Carbon::now()->subYears($itemO->start_old)->toDateString();
                if (\Auth::user()->isAdminCompany()) {
                    $value = \DB::table('customers')->whereDate('birthday', '>=', $startOld)
                        ->whereDate('birthday', '<=', $endOld)->count('id');
                } else {
                    $promotionIds = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();

                    $value = \DB::table('customers')->whereIn('promotion_id', $promotionIds)->whereDate('birthday', '>=', $startOld)
                        ->whereDate('birthday', '<=', $endOld)->count('id');
                }

                $values[] = $value;
            }
        } elseif ($type == 2) {
            //Thống kê theo địa bàn
            if ($provinceId != 0)
                $labels = \DB::table('districts')->where('province_id', $provinceId)->pluck('name')->toArray();
            elseif ($districtId != 0)
                $labels = \DB::table('wards')->where('district_id', $districtId)->pluck('name')->toArray();
            elseif ($wardId != 0)
                $labels = \DB::table('wards')->where('id', $wardId)->pluck('name')->toArray();
            else
                $labels = \DB::table('provinces')->pluck('name')->toArray();

            if ($wardId != 0)
                $ids = \DB::table('wards')->where('id', $wardId)->pluck('id')->toArray();
            elseif ($districtId != 0)
                $ids = \DB::table('wards')->where('district_id', $districtId)->pluck('id')->toArray();
            elseif ($provinceId != 0)
                $ids = \DB::table('districts')->where('province_id', $provinceId)->pluck('id')->toArray();
            else
                $ids = \DB::table('provinces')->pluck('id')->toArray();
            $values = [];
            foreach ($ids as $itemP) {
                if (\Auth::user()->isAdminCompany()) {
                    if ($districtId != 0 || $wardId != 0)
                        $value = \DB::table('customers')->where('ward_id', $itemP)->count('id');
                    elseif ($provinceId != 0)
                        $value = \DB::table('customers')->where('district_id', $itemP)->count('id');
                    else
                        $value = \DB::table('customers')->where('province_id', $itemP)->count('id');
                } else {
                    $promotionIds = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();

                    if ($districtId != 0 || $wardId != 0)
                        $value = \DB::table('customers')->where('ward_id', $itemP)->whereIn('promotion_id', $promotionIds)->count('id');
                    elseif ($provinceId != 0)
                        $value = \DB::table('customers')->where('district_id', $itemP)->whereIn('promotion_id', $promotionIds)->count('id');
                    else
                        $value = \DB::table('customers')->where('province_id', $itemP)->whereIn('promotion_id', $promotionIds)->count('id');
                }

                $values[] = $value;
            }
        } elseif ($type == 4) {
            //Thống kê theo trạng thái kích hoạt
            $labels = ['Kích hoạt', 'Chưa kích hoạt'];
            $actives = [1, 0];
            $values = [];
            foreach ($actives as $itemA) {
                if (\Auth::user()->isAdminCompany()) {
                    $value = \DB::table('customers')->where('active', $itemA)->count('id');
                } else {
                    $promotionIds = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();
                    $value = \DB::table('customers')->where('active', $itemA)->whereIn('promotion_id', $promotionIds)->count('id');
                }
                $values[] = $value;
            }
        } else {
            //Theo chương trình
            if (\Auth::user()->isAdminCompany()) {
                if ($promotionId != 0) {
                    $labels = \DB::table('olds')->pluck('name')->toArray();
                    $olds = \DB::table('olds')->select(['start_old', 'end_old'])->get();
                    $values = [];
                    foreach ($olds as $itemO) {
                        $startOld = Carbon::now()->subYears($itemO->end_old)->toDateString();
                        $endOld = Carbon::now()->subYears($itemO->start_old)->toDateString();
                        $value = \DB::table('customers')->where('birthday', '>=', $startOld)
                            ->whereDate('birthday', '<=', $endOld)->where('promotion_id', $promotionId)->count('id');
                        $values[] = $value;
                    }
                } else {
                    $labels = \DB::table('promotions')->pluck('name')->toArray();
                    $promotionIds = \DB::table('promotions')->pluck('id')->toArray();
                    $values = [];
                    foreach ($promotionIds as $itemP) {
                        $value = \DB::table('customers')->where('promotion_id', $itemP)->count('id');
                        $values[] = $value;
                    }
                }
            } else {
                if ($promotionId != 0) {
                    $labels = \DB::table('olds')->pluck('name')->toArray();
                    $olds = \DB::table('olds')->select(['end_old', 'start_old'])->get();
                    $values = [];
                    foreach ($olds as $itemO) {
                        $startOld = Carbon::now()->subYears($itemO->end_old)->toDateString();
                        $endOld = Carbon::now()->subYears($itemO->start_old)->toDateString();
                        $value = \DB::table('customers')->whereDate('birthday', '>=', $startOld)
                            ->whereDate('birthday', '<=', $endOld)->where('promotion_id', $promotionId)->count('id');
                        $values[] = $value;
                    }
                } else {
                    $labels = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('name')->toArray();
                    $promotionIds = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();
                    $values = [];
                    foreach ($promotionIds as $itemP) {
                        $value = \DB::table('customers')->where('promotion_id', $itemP)->count('id');
                        $values[] = $value;
                    }
                }
            }
        }
        // Prepare data for Chart.js
        $chartData = [
            'labels' => $labels,
            'dataset' => [
                'label' => 'Khách hàng:' . array_sum($values),
                'data' => $values,
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
        ];

        // Output JSON
        header('Content-Type: application/json');
        return json_encode($chartData);
    }




    public function activeCustomers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $customers = Customer::find($item);
            $active = ($customers->active == 1) ? 0 : 1;
            \DB::table('customers')->where('id', $customers->id)->update([
                'updated_at' => Carbon::now(),
                'active' => $active
            ]);
        }
        alert()->success(__('Cập nhật trạng thái thành công'));
        return \response()->json(['success' => 'ok']);
    }


    public function activeMembers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            $customers = Member::find($item);
            $active = ($customers->active == 1) ? 0 : 1;
            \DB::table('members')->where('id', $customers->id)->update(['active' => $active]);
        }
        alert()->success(__('Cập nhật trạng thái thành công'));
        return \response()->json(['success' => 'ok']);
    }


    public function deleteCustomers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            Customer::destroy($item);
        }
        alert()->success(__('Xóa dữ liệu thành công'));

        return \response()->json(['success' => 'ok']);
    }

    public function deleteMembers(Request $request)
    {
        $ids = $request->ids;
        $arrId = explode(',', $ids, -1);
        foreach ($arrId as $item) {
            Member::destroy($item);
        }
        alert()->success(__('Xóa dữ liệu thành công'));

        return \response()->json(['success' => 'ok']);
    }

    public function getDistricts(Request $request)
    {
        $data = \DB::table('districts')->where('province_id', $request->id)->pluck('name', 'id');
        return \response()->json($data);
    }

    public function getWards(Request $request)
    {
        $data = \DB::table('wards')->where('district_id', $request->id)->pluck('name', 'id');
        return \response()->json($data);
    }

    // Wellbe below here

    public function activeServiceCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        ServiceCategory::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteServiceCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        ServiceCategory::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeServices(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Service::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteServices(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Service::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activePromotions(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Promotion::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deletePromotions(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Promotion::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activePostCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        PostCategory::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deletePostCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        PostCategory::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activePosts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Post::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deletePosts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Post::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activePostComments(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        PostComment::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deletePostComments(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        PostComment::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeMemberships(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Membership::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteMemberships(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Membership::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeBranches(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Branch::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteBranches(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Branch::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeImages(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Image::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteImages(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Image::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeImageCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        ImageCategory::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteImageCategories(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        ImageCategory::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'ok']);
    }

    public function activeRoomTypes(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        RoomType::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteRoomTypes(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        RoomType::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeBranchRoomTypes(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        BranchRoomType::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteBranchRoomTypes(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        BranchRoomType::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeEmployees(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Employee::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteEmployees(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Employee::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeEmployeeServices(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        EmployeeService::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteEmployeeServices(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        EmployeeService::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeWorkingShifts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        WorkingShift::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteWorkingShifts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        WorkingShift::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeEmployeeWorkingShifts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        EmployeeWorkingShift::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteEmployeeWorkingShifts(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        EmployeeWorkingShift::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }

    public function activeBookings(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Booking::whereIn('id', $ids)
            ->update(['is_active' => 1]);

        return response()->json(['success' => 'ok']);
    }

    public function deleteBookings(Request $request)
    {
        $ids = explode(',', rtrim($request->ids, ','));

        Booking::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'ok']);
    }
}
