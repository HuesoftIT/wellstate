<section class="bg-[#ecf4dd] py-[120px]">
    <div class="container mx-auto px-6">

        <!-- TITLE -->
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h2 class="text-[58px] font-open-sans font-medium text-[#4C4C4C] mb-4">
                Không gian sang trọng & an yên
            </h2>

            <p class="text-[#4C4C4C] leading-relaxed text-sm md:text-base">
                Bước vào Sana Spa, bạn như lạc vào một ốc đảo bình yên giữa nhịp sống hối hả.
                Chính sự êm ái, tĩnh lặng đó sẽ vỗ về mọi giác quan, nâng niu từng khoảnh khắc
                trải nghiệm, giúp mỗi khách hàng trút bỏ âu lo và đắm chìm trong sự an yên tuyệt đối.
            </p>
        </div>

        <!-- SWIPER -->
        <div class="swiper spaceSwiper">
            <div class="swiper-wrapper">

                <!-- ITEM -->
                @foreach ($customer_review_images as $item)
                      <div class="swiper-slide">
                        <img src="{{ Storage::url($item->image) }}" class="w-full h-[260px] object-cover rounded-xl" alt="{{ $item->name}}">
                    </div>
                @endforeach
                  

            </div>
        </div>

    </div>
</section>
