<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">

        @forelse ($services as $service)
            <div class="bg-[#e6f0cf] rounded-xl overflow-hidden flex flex-col">

                <!-- IMAGE -->
                <img src="{{ $service->image ? Storage::url($service->image) : asset('images/default-service.jpg') }}"
                    alt="{{ $service->title }}" class="w-full h-[140px] md:h-[200px] lg:h-[260px] object-cover">

                <!-- CONTENT -->
                <div class="p-3 md:p-5 flex flex-col flex-1 text-center">

                    <!-- TITLE -->
                    <h3
                        class="uppercase text-[14px] md:text-[18px] lg:text-[22px] font-open-sans text-[#3b4a2f] mb-1 line-clamp-2 font-medium">
                        {{ $service->title }}
                    </h3>

                    <!-- CATEGORY -->
                    <p class="text-[14px] md:text-[18px] text-[#4a4a4a] mb-1">
                        {{ $service_category->name }}
                    </p>

                    <!-- PHONE -->
                    <p class="text-[#3b4a2f] text-[18px] md:text-[20px] font-medium mb-3 font-medium mt-4">
                        Liên hệ: {{ $company_phone }}
                    </p>

                    <!-- BUTTON -->
                    <a href="{{ route('page.service.detail', ['service_category_slug' => $service_category->slug, 'slug' => $service->slug]) }}"
                        class="mt-auto text-[12px] md:text-[14px] px-3 py-1.5 md:px-4 md:py-2
                           bg-[#6f7f3a] text-white rounded
                           hover:bg-[#5f6e31] transition">
                        Xem chi tiết
                    </a>

                </div>
            </div>

        @empty
            <div class="col-span-full">
                <div
                    class="bg-[#f1f6dd] border border-[#d7e3a3]
                            rounded-[20px] p-12 text-center max-w-xl mx-auto">

                    <div class="text-[#6f7f3a] mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 mx-auto" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3C7 3 3 7 3 12s4 9 9 9
                                   9-4 9-9-4-9-9-9z" />
                        </svg>
                    </div>

                    <h3 class="text-[26px] font-open-sans text-[#3b4a2f] mb-3">
                        Hiện chưa có dịch vụ nào
                    </h3>

                    <p class="text-[#4a4a4a] mb-6 text-[16px]">
                        Chúng tôi đang cập nhật thêm các liệu trình mới.
                        Vui lòng quay lại sau hoặc liên hệ để được tư vấn.
                    </p>

                    <a href="tel:{{ $company_phone }}"
                        class="inline-block px-8 py-3 text-[16px]
                               bg-[#6f7f3a] text-white rounded
                               hover:bg-[#5f6e31] transition">
                        Liên hệ tư vấn
                    </a>
                </div>
            </div>
        @endforelse

    </div>
</div>
