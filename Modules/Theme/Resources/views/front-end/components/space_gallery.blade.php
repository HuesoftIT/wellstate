<section class="bg-[#ecf4dd] py-[120px]">
    <div class="container mx-auto px-6">

        <!-- TITLE -->
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h2 class="text-[58px] font-open-sans font-medium text-[#4C4C4C] mb-4">
                Không gian sang trọng & chuẩn mực nghỉ dưỡng
            </h2>

            <p class="text-[#4C4C4C] leading-relaxed text-sm md:text-base">
                Bước vào WELLSTATE, bạn như bước vào một không gian chăm sóc sức khỏe
                được thiết kế tinh tế và chuẩn mực. Sự yên tĩnh, riêng tư và hài hòa
                trong từng chi tiết giúp đánh thức mọi giác quan, mang lại cảm giác
                thư thái sâu bên trong, để mỗi trải nghiệm đều trọn vẹn và đáng nhớ.
            </p>
        </div>


        <!-- SWIPER -->
        <div class="swiper spaceSwiper">
            <div class="swiper-wrapper">

                <!-- ITEM -->
                @foreach ($customer_review_images as $item)
                    <div class="swiper-slide">
                        <img src="{{ Storage::url($item->image) }}" class="w-full h-[260px] object-cover rounded-xl"
                            alt="{{ $item->name }}">
                    </div>
                @endforeach


            </div>
        </div>

    </div>
</section>
