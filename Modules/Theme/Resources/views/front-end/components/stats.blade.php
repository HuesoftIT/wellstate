<section class="w-full bg-[#ecf4dd] py-[150px]">
    <div class="container mx-auto px-6">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-12 gap-x-12 items-start">

            {{-- LEFT CONTENT --}}
            <div class="lg:col-span-6">
                <p class="text-xs tracking-widest text-gray-600 mb-3">
                    WELLSTATE
                </p>

                <h2 class="font-open-sans font-medium text-[58px] text-[#7a8f2c] mb-6">
                    Những con số ấn tượng
                </h2>

                <p class="text-gray-600 leading-relaxed mb-8 max-w-xl">
                    Hơn 10.000 khách hàng đã lựa chọn WELLSTATE để trải nghiệm, trong đó 99,9% hài lòng với kết quả
                    mang lại. Đây là minh chứng rõ nhất cho chất lượng dịch vụ, sự tận tâm và uy tín của thương hiệu.
                    Tại WELLSTATE, khách hàng được lắng nghe, thấu hiểu và chăm sóc theo cách riêng biệt,
                    để mỗi trải nghiệm đều trọn vẹn và đáng nhớ.
                </p>

                <a href="/gioi-thieu"
                    class="inline-block bg-[#7a8f2c] text-white text-sm px-8 py-3 rounded hover:bg-[#8fa63b] transition">
                    TÌM HIỂU THÊM
                </a>
            </div>


            {{-- RIGHT STATS --}}
            <div id="stats-section"
                class="lg:col-span-6
                       grid grid-cols-2 gap-6
                       mt-8 lg:mt-0">
                @foreach ([10000, 10000, 10000, 10000] as $number)
                    <div class="bg-[#dfecc1] p-8 text-center rounded">
                        <p class="stat-number text-4xl font-semibold text-[#3f4d1c]" data-target="{{ $number }}">
                            0
                        </p>
                        <p class="font-cormorant text-[19px] text-gray-700 mt-2 uppercase tracking-wide font-bold">
                            Khách hàng trên cả nước
                        </p>

                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
