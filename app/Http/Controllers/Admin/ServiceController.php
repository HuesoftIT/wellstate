<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceComboItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Danh sách dịch vụ
     */
    public function index(Request $request)
    {
        $query = Service::with('serviceCategory')->sortable();

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('service_category_id')) {
            $query->where('service_category_id', $request->service_category_id);
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $services = $query->paginate(config('settings.perpage', 10));
        $categories = ServiceCategory::pluck('name', 'id');


        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $categories  = ServiceCategory::where('is_active', 1)->pluck('title', 'id');
        $servicesForCombo = Service::where('is_combo', false)
            ->where('is_active', true)
            ->get(['id', 'title', 'price', 'duration']);
        return view('admin.services.create', compact('categories', 'servicesForCombo'));
    }

    /**
     * Lưu dịch vụ mới
     */
    public function store(StoreServiceRequest $request)
    {
        DB::transaction(function () use ($request) {

            $data = $request->validated();

            $service = Service::create($data);

            if ($service->is_combo && $request->filled('combo_items')) {

                foreach ($request->combo_items as $item) {
                    $service->comboItems()->create([
                        'service_id' => $item['service_id'],
                        'quantity'   => $item['quantity'],
                    ]);
                }
            }
        });

        Alert::success('Tạo dịch vụ thành công');


        return redirect()->route('services.index');
    }


    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $service = Service::with('comboItems.service')->findOrFail($id);

        $servicesForCombo = Service::where('is_combo', false)
            ->where('is_active', true)
            ->get(['id', 'title', 'price', 'duration']);

        $categories = ServiceCategory::pluck('title', 'id');

        return view(
            'admin.services.edit',
            compact('service', 'categories', 'servicesForCombo')
        );
    }


    /**
     * Cập nhật dịch vụ
     */
    public function update(UpdateServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);

        DB::transaction(function () use ($request, $service) {

            $service->update($request->validated());

            $service->comboItems()->forceDelete();

            if ($request->boolean('is_combo')) {
                $comboItems = collect($request->combo_items ?? [])->unique('service_id');

                foreach ($comboItems as $item) {
                    $service->comboItems()->create([
                        'combo_service_id' => $service->id,
                        'service_id'      => $item['service_id'],
                        'quantity'        => $item['quantity'],
                    ]);
                }
            }
        });

        return redirect()->route('services.index');
    }




    public function show($id, Request $request)
    {
        $service = Service::with('serviceCategory')->findOrFail($id);
        $locale = app()->getLocale();

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.services.show', compact('service', 'backUrl', 'locale'));
    }


    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        DB::transaction(function () use ($service) {
            $service->comboItems()->delete();

            $service->delete();
        });
        Alert::success(__('Đã xóa dịch vụ'));

        return redirect()->back();
    }

    /**
     * Danh sách đã xóa
     */
    public function trash()
    {
        $services = Service::onlyTrashed()->paginate(10);

        return view('admin.services.trash', compact('services'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        Service::onlyTrashed()->findOrFail($id)->restore();

        Alert::success(__('Khôi phục dịch vụ thành công'));

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        Service::onlyTrashed()->findOrFail($id)->forceDelete();

        Alert::success(__('Đã xóa vĩnh viễn dịch vụ'));

        return redirect()->back();
    }
}
