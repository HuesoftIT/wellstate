<section class="w-full bg-[#f4f8ea] py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- LEFT: Content --}}
            <div>
                <p class="text-[16px] text-[#383E1A] tracking-widest font-semibold mb-2">
                    SANA HOLDING
                </p>

                <h2 class="text-[58px] font-open-sans italic font-medium text-[#6b7c2f] mb-6">
                    GIÁ TRỊ CỐT LÕI
                </h2>

                <p class="text-gray-600 leading-relaxed mb-10 text-[16px]">
                    Mỗi trải nghiệm tại Sana Holding được xây dựng dựa trên những giá trị cốt lõi,
                    nhằm mang đến hành trình chăm sóc sức khỏe và tinh thần một cách trọn vẹn,
                    tinh tế và bền vững.
                </p>

                <div class="grid md:grid-cols-2 gap-8">

                    {{-- Tinh tế --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <span class="text-[#6b7c2f] font-semibold">01</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Tinh tế</h4>
                            <p class="text-gray-600 text-[16px]">
                                Chú trọng từng chi tiết nhỏ trong không gian, liệu trình
                                và cảm xúc của khách hàng.
                            </p>
                        </div>
                    </div>

                    {{-- Cá nhân hóa --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <span class="text-[#6b7c2f] font-semibold">02</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Cá nhân hóa</h4>
                            <p class="text-gray-600 text-[16px]">
                                Mỗi liệu trình được thiết kế riêng, phù hợp với thể trạng
                                và nhu cầu của từng khách hàng.
                            </p>
                        </div>
                    </div>

                    {{-- Chữa lành --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <span class="text-[#6b7c2f] font-semibold">03</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Chữa lành</h4>
                            <p class="text-gray-600 text-[16px]">
                                Tái tạo năng lượng thân – tâm – trí thông qua liệu pháp
                                trị liệu chuyên sâu và tự nhiên.
                            </p>
                        </div>
                    </div>

                    {{-- Cao cấp --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <span class="text-[#6b7c2f] font-semibold">04</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Cao cấp</h4>
                            <p class="text-gray-600 text-[16px]">
                                Dịch vụ, công nghệ và không gian đạt chuẩn wellness
                                cao cấp, an toàn và hiệu quả.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT: Images --}}
            <div class="relative flex gap-6 justify-center">

                <div class="w-[220px] h-[320px] rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ asset('images/about_us1.png') }}" alt="About image 1"
                        class="w-full h-full object-cover">
                </div>

                <div class="w-[220px] h-[320px] rounded-2xl overflow-hidden shadow-lg mt-12">
                    <img src="{{ asset('images/about_us2.png') }}" alt="About image 2"
                        class="w-full h-full object-cover">
                </div>

                {{-- Rotating logo --}}
                <div
                    class="absolute top-1/2 left-1/2
                           -translate-x-1/2 -translate-y-1/2
                           w-20 h-20 rounded-full bg-white shadow-xl
                           flex items-center justify-center
                           [animation:spin_10s_linear_infinite]">
                    <img src="{{ asset('images/wellstate-logo.png') }}" alt="Sana logo"
                        class="w-12 h-12 object-contain">
                </div>
            </div>

        </div>
    </div>
</section>
