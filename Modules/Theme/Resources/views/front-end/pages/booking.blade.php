@extends('theme::front-end.master')

@section('content')
    @php
        $selectClass = 'w-full h-11 rounded-lg border border-slate-300 bg-white px-4 text-[16px] text-slate-700
                    focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20';

        $inputClass = 'w-full h-11 rounded-lg border border-slate-300 bg-white px-4 text-[16px] text-slate-700
                   placeholder:text-slate-400
                   focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20';
    @endphp

    <form method="POST" action="{{ route('post.booking') }}" id="booking" class="min-h-screen py-10 bg-[#f4f8ea]">
        @csrf
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-6">
                        1. Thông tin người đặt lịch
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- Họ tên -->
                        <div>
                            <label class="block text-[16px] font-medium text-slate-600 mb-1">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="booker_name" required=true placeholder="Ví dụ: Nguyễn Văn A"
                                class="input h-11">
                        </div>

                        <!-- Số điện thoại -->
                        <div>
                            <label class="block text-[16px] font-medium text-slate-600 mb-1">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="booker_phone" required=true placeholder="0123 456 789"
                                class="input h-11">
                        </div>

                    </div>

                    <!-- Số khách -->
                    <div class="mt-5 w-20">
                        <label class="block text-[16px] font-medium text-slate-600 mb-1">
                            Số khách
                        </label>
                        <input type="number" name="guest_count" required=true min="1" max="100" value="1"
                            id="guest-count" class="input h-11 text-center">
                    </div>
                </section>


                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-4">
                        2. Chọn chi nhánh & ngày
                    </h2>

                    <div class="space-y-4">
                        <!-- Item -->
                        @foreach ($branches_available as $item)
                            <label
                                class="branch-item flex items-center gap-4 p-4 border rounded-lg cursor-pointer
                        transition
                        has-[:checked]:border-blue-500
                        has-[:checked]:bg-blue-50">

                                <input type="radio" name="branch_id" value="{{ $item->id }}"
                                    class="w-5 h-5 text-blue-600 focus:ring-blue-500">

                                <span class="text-[18px] font-medium text-gray-800">
                                    {{ $item->address }}
                                </span>
                            </label>
                        @endforeach




                    </div>
                    <div class="mt-6">
                        <label class="block text-[18px] font-medium text-slate-600 mb-1">
                            Ngày đặt lịch <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input id="booking-date" type="text" placeholder="Chọn thời điểm"
                                class="input h-11 w-full pr-10 cursor-pointer" name="booking_date">


                            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                </section>

                <!-- ===== STEP 3: SERVICES ===== -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-6">
                        3. Chọn dịch vụ
                    </h2>




                    <!-- Guest list -->
                    <div id="guest-services" class="space-y-6"></div>

                    <!-- ===== GUEST TEMPLATE ===== -->
                    <template id="guest-template">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 space-y-5 guest-item">

                            <!-- Header -->
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-[18px] text-[#383e1a]">
                                    Khách <span class="guest-index"></span>
                                </h3>

                                <input type="hidden" name="guests[__index__][uid]" class="guest-uid-input">
                            </div>

                            <!-- Guest name -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-600">
                                    Tên khách
                                </label>
                                <input type="text" name="guests[__index__][name]" placeholder="Tên khách (tuỳ chọn)"
                                    class="input h-11">
                            </div>

                            <!-- Services list -->
                            <div class="services-wrapper space-y-4"></div>

                            <!-- Add service -->
                            <button type="button" class="add-service text-sm text-blue-600 hover:underline">
                                + Thêm dịch vụ
                            </button>
                        </div>
                    </template>

                    <!-- ===== SERVICE TEMPLATE ===== -->
                    <template id="service-template">
                        <div class="service-item relative rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                         

                            <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-4 items-end">

                                <!-- Nhóm dịch vụ -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">
                                        Nhóm dịch vụ
                                    </label>
                                    <select name="__SERVICE_NAME__[service_category_id]"
                                        class="service-category select h-11 w-full">
                                        <option value="">Chọn nhóm</option>
                                        @foreach ($serviceCategories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Dịch vụ (RỘNG HƠN) -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">
                                        Dịch vụ
                                    </label>
                                    <select name="__SERVICE_NAME__[service_id]" class="service-select select h-11 w-full"
                                        disabled>
                                        <option value="">Chọn dịch vụ</option>
                                    </select>
                                </div>

                                <!-- Remove (HẸP) -->
                                <div class="flex justify-end">
                                    <button type="button"
                                        class="remove-service inline-flex items-center gap-2 px-4 h-11 rounded-lg
                                        border border-red-200 text-red-600
                                        hover:bg-red-50 hover:border-red-300
                                        transition text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Xóa
                                    </button>
                                </div>

                            </div>

                        </div>
                    </template>

                </section>


                <!-- ===== STEP 3.5: ROOM TYPE ===== -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-4">
                        3.5. Chọn loại phòng
                    </h2>

                    <div id="room-type-container">
                        <!-- EMPTY STATE -->
                        <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
                            Vui lòng chọn chi nhánh để hiển thị loại phòng
                        </div>
                    </div>
                </section>



                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-4">
                        4. Chọn khung giờ
                    </h2>

                    <div id="time-slots" class="grid grid-cols-4 sm:grid-cols-6 gap-3"></div>

                    <input type="hidden" name="booking_time" id="booking-time">
                </section>


                <!-- ===== STEP 4.5: PROMO CODE ===== -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-[24px] text-[#383e1a] font-semibold mb-4">
                        5. Mã giảm giá
                    </h2>

                    <div class="flex gap-3">
                        <input id="promo-code" type="text" name="discount_code" placeholder="Nhập mã giảm giá"
                            class="input h-11 flex-1 uppercase">

                        <button type="button" id="apply-promo"
                            class="px-5 h-11 bg-slate-800 text-white rounded-lg
                                hover:bg-slate-900 transition">
                            Áp dụng
                        </button>
                    </div>
                    <p id="promo-message" class="mt-2 text-sm hidden"></p>
                    <!-- Message -->
                   
                </section>

            </div>

            <!-- ================= RIGHT: SUMMARY ================= -->
            <aside class="bg-white rounded-xl shadow-sm p-6 h-fit sticky top-[140px]">
                <h3 class="text-[26px] font-semibold mb-6 text-[#383e1a]">
                    Thông tin đặt lịch
                </h3>

                <!-- INFO -->
                <ul class="space-y-3 text-[16px] text-slate-700">
                    <li class="flex justify-between">
                        <span class="font-medium">Chi nhánh</span>
                        <span id="summary-branch"></span>
                    </li>

                    <li class="flex justify-between">
                        <span class="font-medium">Ngày</span>
                        <span id="summary-date"></span>
                    </li>

                    <li class="flex justify-between">
                        <span class="font-medium">Giờ</span>
                        <span id="summary-time"></span>
                    </li>

                    <li class="flex justify-between">
                        <span class="font-medium">Số khách</span>
                        <span id="summary-guests"></span>
                    </li>

                    <li class="flex justify-between">
                        <span class="font-medium">Loại phòng</span>
                        <span id="summary-room"></span>
                    </li>
                </ul>

                <!-- DIVIDER -->
                <hr class="my-5">

                <!-- SERVICES -->
                <div class="space-y-2 text-[15px] text-slate-600">
                    <div class="flex justify-between">
                        <span>Tạm tính dịch vụ</span>
                        <span id="summary-subtotal"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Phụ thu phòng VIP</span>
                        <span id="summary-room-fee"></span>
                    </div>

                    <div class="flex justify-between text-green-600">
                        <span>Giảm giá</span>
                        <span id="summary-discount"></span>
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-[18px] font-semibold text-slate-700">
                            Tổng thanh toán
                        </span>
                        <span id="summary-total" class="text-[24px] font-bold text-blue-600">
                        </span>
                    </div>
                </div>

                <!-- CTA -->
                <button type="submit"
                    class="mt-6 w-full bg-blue-600 text-white py-3 rounded-lg
               text-[18px] font-semibold hover:bg-blue-700 transition">
                    Xác nhận đặt lịch
                </button>

                <p class="mt-3 text-[13px] text-slate-400 text-center leading-relaxed">
                    Bằng việc đặt lịch, bạn đồng ý với
                    <a href="#" class="underline">điều khoản dịch vụ</a>
                </p>
            </aside>


        </div>
    </form>
@endsection
