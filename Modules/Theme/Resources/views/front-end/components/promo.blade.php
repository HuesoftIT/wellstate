<section class="relative overflow-hidden bg-[#6f7f2a] py-20">
    {{-- White curved background --}}
    <div class="absolute top-0 left-0 h-full w-full lg:w-[40%]  bg-[#ebece7]
            rounded-r-full">
    </div>


    <div class="relative container mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 items-center px-6">

        {{-- LEFT CONTENT --}}
        <div class="lg:col-span-5">
            <img src="/logo-sana.svg" class="w-28 mb-6" alt="">

            <h3 class="text-[#7a8f2c] text-[30px] uppercase font-open-sans font-semibold mb-4">
                ƯU ĐÃI SIÊU HẤP DẪN<br>
                NHẬN ƯU ĐÃI NGAY HÔM NAY
            </h3>

            <p class="text-[#383E1A] font-open-sans font-medium mb-6">
                Số lượng có hạn - Đặt lịch ngay kẻo lỡ
            </p>

            <a href="/lien-he"
                class="inline-flex items-center gap-2
                      bg-[#7a8f2c] text-white px-8 py-4 rounded">
                ĐẶT LỊCH TƯ VẤN →
            </a>
        </div>

        {{-- RIGHT SLIDER --}}
        <div class="lg:col-span-7">
            <div class="swiper promoSwiper hidden lg:flex lg:items-center  h-[480px]">
                <div class="swiper-wrapper py-[65px]">

                    @foreach ($promotion_images as $item)
                        <div
                            class="swiper-slide promo-slide rotate-left
                            h-full flex items-center justify-center">
                            <img src="{{ Storage::url($item->image) }}"
                                class="max-h-full w-auto rounded-lg
                                object-contain
                             "
                                alt="">

                        </div>
                    @endforeach

                </div>
            </div>
        </div>


    </div>
</section>
