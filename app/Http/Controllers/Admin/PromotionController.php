<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Models\Customer;
use App\Models\Membership;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Foundation\Http\FormRequest;

class PromotionController extends Controller
{
    /**
     * Danh sách khuyến mãi
     */
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $promotions = $query->paginate(config('settings.perpage', 10));

        return view('admin.promotions.index', compact('promotions'));
    }


    /**
     * Form tạo mới
     */
    public function create()
    {
        $services = Service::where('is_active', true)
            ->orderBy('title')
            ->pluck('title', 'id');



        $memberships = Membership::where('is_active', true)->get();

        return view('admin.promotions.create', compact('services', 'memberships'));
    }


    /**
     * Lưu khuyến mãi mới
     */
    public function store(StorePromotionRequest $request)
    {
        DB::transaction(function () use ($request) {

            $promotion = $this->createPromotion($request);
            $this->savePromotionRules($promotion, $request);
        });

        Alert::success('Tạo khuyến mãi thành công');
        return redirect()->route('promotions.index');
    }
    protected function createPromotion(StorePromotionRequest $request): Promotion
    {
        $data = $request->validated();

        unset(
            $data['service_ids'],
            $data['membership_levels'],
            $data['user_ids'],
        );

        return Promotion::create($data);
    }

    protected function savePromotionRules(Promotion $promotion, FormRequest $request): void
    {
        /**
         * 1. Rule dịch vụ
         */
        if ($request->service_rule === 'only' && $request->filled('service_ids')) {
            $promotion->rules()->create([
                'type'   => 'service',
                'order'  => 1,
                'config' => [
                    'mode' => 'only',
                    'ids'  => array_map('intval', $request->service_ids),
                ],
            ]);
        }

        /**
         * 2. Rule membership
         * Chỉ lưu khi KHÔNG phải "toàn bộ thành viên"
         */
        if (
            !$request->boolean('membership_all') &&
            $request->filled('membership_levels')
        ) {
            $promotion->rules()->create([
                'type'   => 'membership',
                'order'  => 2,
                'config' => [
                    'ids' => array_map('intval', $request->membership_levels),
                ],
            ]);
        }

        /**
         * 3. Rule user
         */
        if ($request->user_rule === 'only' && $request->filled('user_ids')) {
            $promotion->rules()->create([
                'type'   => 'user',
                'order'  => 3,
                'config' => [
                    'mode' => 'only',
                    'ids'  => array_map('intval', $request->user_ids),
                ],
            ]);
        }

        /**
         * 4. Rule sinh nhật
         */
        if ($request->boolean('rule_birthday')) {
            $promotion->rules()->create([
                'type'   => 'birthday',
                'order'  => 4,
                'config' => [
                    'enabled' => true,
                ],
            ]);
        }
    }



    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $promotion = Promotion::with('rules')->findOrFail($id);

        $services = Service::where('is_active', true)
            ->orderBy('title')
            ->pluck('title', 'id');

        $memberships = Membership::where('is_active', true)->get();

        $rules = $promotion->rules->keyBy('type');

        $userIds = $rules['user']->config['ids'] ?? [];

        $customers = Customer::whereIn('id', $userIds)
            ->select('id', 'name', 'phone')
            ->get()
            ->keyBy('id');
        return view(
            'admin.promotions.edit',
            compact('promotion', 'services', 'memberships', 'rules', 'customers')
        );
    }


    /**
     * Cập nhật khuyến mãi
     */
    public function update(UpdatePromotionRequest $request, $id)
    {
        $promotion = Promotion::with('rules')->findOrFail($id);
        DB::transaction(function () use ($request, $promotion) {

            /**
             * 1. Update promotion info
             */
            $data = $request->validated();

            unset(
                $data['service_ids'],
                $data['membership_levels'],
                $data['user_ids'],
            );

            $promotion->update($data);

            $promotion->rules()->delete();

            /**
             * 3. Lưu lại rule mới (reuse logic)
             */
            $this->savePromotionRules($promotion, $request);
        });

        Alert::success('Cập nhật khuyến mãi thành công');

        return redirect()->route('promotions.index');
    }


    /**
     * Xem chi tiết khuyến mãi
     */
    public function show($id, Request $request)
    {
        $promotion = Promotion::with('rules')->findOrFail($id);

        $userIds = $promotion->rules
            ->where('type', 'user')
            ->pluck('config.ids')
            ->flatten()
            ->unique()
            ->toArray();

        $users = \App\Models\Customer::whereIn('id', $userIds)->get();

        $serviceIds = $promotion->rules
            ->where('type', 'service')
            ->pluck('config.ids')
            ->flatten()
            ->unique()
            ->toArray();

        $services = \App\Models\Service::whereIn('id', $serviceIds)->get();

        $membershipIds = $promotion->rules
            ->where('type', 'membership')
            ->pluck('config.ids')
            ->flatten()
            ->unique()
            ->toArray();

        $memberships = \App\Models\Membership::whereIn('id', $membershipIds)->get();

        $backUrl = $request->get('back_url');

        return view('admin.promotions.show', compact(
            'promotion',
            'users',
            'services',
            'memberships',
            'backUrl'
        ));
    }



    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        DB::transaction(function () use ($promotion) {
            $promotion->rules()->delete();
            $promotion->delete();
        });

        Alert::success(__('Đã xóa khuyến mãi'));
        return redirect()->route('promotions.index');
    }

    /**
     * Danh sách khuyến mãi đã xóa
     */
    public function trash()
    {
        $promotions = Promotion::onlyTrashed()->paginate(10);
        return view('admin.promotions.trash', compact('promotions'));
    }

    /**
     * Khôi phục khuyến mãi
     */
    public function restore($id)
    {
        Promotion::onlyTrashed()->findOrFail($id)->restore();
        Alert::success(__('Khôi phục khuyến mãi thành công'));
        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($promotion) {
            $promotion->services()->detach();
            $promotion->forceDelete();
        });

        Alert::success(__('Đã xóa vĩnh viễn khuyến mãi'));
        return redirect()->back();
    }
}
