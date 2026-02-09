<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" style="background : url()">

        @forelse ($posts as $post)
            <div class="bg-[#e6f0cf] rounded-[14px] overflow-hidden text-center flex flex-col h-full">

                <!-- IMAGE -->
                <img src="{{ $post->image ? Storage::url($post->image) : asset('images/default-service.jpg') }}"
                    alt="{{ $post->title }}" class="w-full h-[280px] object-cover">

                <!-- CONTENT -->
                <div class="p-6 flex flex-col flex-1">

                    <h3 class="uppercase text-[28px] font-open-sans text-[#3b4a2f] mb-3">
                        {{ $post->title }}
                    </h3>

                    <p class="text-[16px] font-open-sans text-[#4a4a4a] mb-3">
                        {{ $post_category->name }}
                    </p>

                    <p class="text-[#3b4a2f] text-[28px] font-open-sans mb-6">
                        Liên hệ: {{ $company_phone }}
                    </p>

                    <!-- BUTTON -->
                    <a href="/san-pham/{{ $post->slug }}"
                        class="mt-auto inline-block px-6 py-4 text-[18px] font-cormorant
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
