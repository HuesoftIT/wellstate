<section class="w-full bg-[#f4f8ea] py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- LEFT: Images --}}
            <div class="relative flex gap-6 justify-center">

                <div class="w-[220px] h-[320px] rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ asset('images/about_us1.png') }}" alt="About image 1" class="w-full h-full object-cover">
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
                    <img src="{{ asset('images/wellstate-logo.png') }}" alt="Wellstate logo"
                        class="w-12 h-12 object-contain">
                </div>
            </div>

            {{-- RIGHT: Content --}}
            <div>
                <p class="text-[16px] text-[#383E1A] tracking-widest  font-semibold mb-2">
                    WELLSTATE
                </p>

                <h2 class="text-[58px] lg:text-4xl font-bold text-[#6b7c2f] mb-6">
                    VỀ CHÚNG TÔI
                </h2>

                <p class="text-gray-600 leading-relaxed mb-10">
                    WELLSTATE là điểm đến chăm sóc sức khỏe và sắc đẹp mang tinh thần nghỉ dưỡng cao cấp.
                    Chúng tôi kết hợp giữa liệu trình thư giãn chuyên sâu và không gian thiên nhiên,
                    mang đến trải nghiệm cân bằng thân – tâm – trí.
                </p>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Vision --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#6b7c2f]" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5
                             c4.478 0 8.268 2.943 9.542 7
                             -1.274 4.057-5.064 7-9.542 7
                             -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>

                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Tầm nhìn</h4>
                            <p class="text-gray-600 text-[16px]">
                                Trở thành hệ thống spa nghỉ dưỡng hàng đầu Việt Nam,
                                là biểu tượng của chăm sóc sức khỏe chuẩn mực dưới thương hiệu WELLSTATE.
                            </p>
                        </div>
                    </div>

                    {{-- Mission --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#e3edd0] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#6b7c2f]" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 20h14" />
                            </svg>
                        </div>

                        <div>
                            <h4 class="font-semibold text-[#6b7c2f] mb-2">Sứ mệnh</h4>
                            <p class="text-gray-600 text-[16px]">
                                Mang đến hành trình chăm sóc sức khỏe – sắc đẹp toàn diện,
                                nuôi dưỡng năng lượng tích cực từ bên trong cùng WELLSTATE.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
