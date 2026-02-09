<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">


        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

            @forelse ($promotions as $item)
                <article class="group">

                    <!-- IMAGE -->
                    <a href="" class="block overflow-hidden bg-[#f3f5e8]">
                        <img src="{{ $item->image ? Storage::url($item->image) : asset('images/default-service.jpg') }}"
                            alt="{{ $item->title }}"
                            class="w-full h-[320px] object-cover
                                group-hover:scale-105
                                transition duration-500">
                    </a>

                    <!-- CONTENT -->
                    <div class="mt-5">

                        <!-- TITLE -->
                        <h3 class="text-[16px] md:text-[17px] font-semibold uppercase text-[#2f3e1f] leading-snug">
                            <a href="" class="hover:text-[#8aa24a] transition font-open-sans text-[18px]">
                                {{ $item->title }}
                            </a>
                        </h3>

                        <!-- META -->
                        <p class="text-[12px] text-[#829137] text-gray-500 mt-2">
                            {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('d \\t\\h\\á\\n\\g m, Y') }}
                            / Không có bình luận
                        </p>

                        <!-- DESCRIPTION -->
                        <p class="mt-4 text-[14px] text-gray-600 leading-relaxed line-clamp-3">
                            {{ $item->description }}
                        </p>

                        <!-- READ MORE -->
                        <a href=""
                            class="inline-block mt-3 text-[28px] font-cormorant text-[#873D00] underline underline-offset-4
                              hover:text-[#6e451f] transition">
                            Đọc tiếp
                        </a>

                    </div>
                </article>
            @empty
                <div class="col-span-12 flex flex-col items-center justify-center py-20 text-center">


                    <h3 class="text-[36px] md:text-[22px] font-semibold text-[#2f3e1f] mb-2">
                        Chưa có sự kiện nào
                    </h3>

                    <p class="text-[20px] text-gray-500 max-w-md">
                        Hiện tại chưa có ưu đãi nào được cập nhật.
                        Vui lòng quay lại sau để không bỏ lỡ những chương trình mới nhất 🌿
                    </p>

                </div>
            @endforelse

        </div>
        <div class="lg:col-span-4 p-4 bg-transparent border border-red-600">

            <h3 class="text-[32px] font-open-sans font-medium text-[#829137] mb-4">Bài viết mới</h3>

            <div class="flex flex-col  gap-4">

                @foreach ($new_posts as $post)
                    <div class="flex items-start gap-6">
                        <!-- IMAGE: 30% -->
                        <div class="w-[30%]">
                            <img src="{{ $post->image ? Storage::url($post->image) : asset('images/default-service.jpg') }}"
                                alt="" class="w-full h-[100px] object-cover">
                        </div>

                        <!-- CONTENT: 70% -->
                        <div class="w-[70%] flex flex-col gap-3">
                            <h4 class="text-[18px] font-medium text-[#829137]">
                                {{ $post->title }}
                            </h4>
                            <span class="text-[12px] text-[#adadad] font-cormorant">
                                {{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('d \\t\\h\\á\\n\\g m, Y') }}
                                · Không có bình luận
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="flex items-start gap-6">
                <img src="{{ asset('images/doi-ngu.jpeg') }}" alt="" class="w-[40%] h-[100px] object-cover text-center">

                <div class="flex flex-col gap-3 items-start">
                    <h4 class="text-[18px] font-medium text-[#829137]">SANA WELLNESS SEASON – ƯU ĐÃI CHẠM ĐỈNH THƯ GIÃN 20%</h4>
                    <span class="text-[12px] text-[#adadad] font-cormorant">15 Tháng 12, 2025 Không có bình luận</span>
                </div>
            </div> --}}
        </div>

    </div>
</div>
