<section class="w-full bg-[#f4f8ea] py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-start">

            {{-- LEFT: Images --}}
            <div class="relative">

                {{-- IMAGE LIST --}}
                <div
                    class="
        grid grid-cols-1 gap-4
        sm:grid-cols-2
        lg:flex lg:justify-center lg:gap-6
    ">

                    @forelse($images_about_us as $image)
                        <div
                            class="
                w-full
                h-[260px]
                sm:h-[320px]
                md:h-[420px]
                lg:w-[300px] lg:h-[640px]
                rounded-2xl overflow-hidden shadow-lg
            ">
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $image->title ?? 'About image' }}"
                                class="w-full h-full object-cover">
                        </div>
                    @empty
                    @endforelse

                </div>

                {{-- Rotating logo --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">

                    <div
                        class="
            w-14 h-14
            sm:w-16 sm:h-16
            lg:w-20 lg:h-20
            rounded-full bg-white shadow-xl
            flex items-center justify-center
            animate-spin-slow
        ">
                        <img src="{{ asset('images/wellstate-logo.png') }}" alt="Wellstate logo"
                            class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 object-contain">
                    </div>

                </div>

            </div>

            {{-- RIGHT: Content --}}
            <div class="align-self-start">
                <p class="text-[16px] text-[#383E1A] tracking-widest  font-semibold mb-2">
                    WELLSTATE
                </p>

                <h2 class="text-[58px] font-light text-[#6b7c2f] mb-6">
                    VỀ CHÚNG TÔI
                </h2>

                <p class="text-gray-600 leading-relaxed mb-10">
                    WELLSTATE là điểm đến chăm sóc sức khỏe và sắc đẹp mang tinh thần nghỉ dưỡng cao cấp.
                    Chúng tôi kết hợp giữa liệu trình thư giãn chuyên sâu và không gian thiên nhiên,
                    mang đến trải nghiệm cân bằng thân – tâm – trí.
                </p>

                <div class="grid md:grid-cols-2 gap-12 relative">

                    {{-- Vertical divider --}}
                    <div class="hidden md:block absolute top-0 bottom-0 left-1/2 w-px bg-[#8a9b3a] opacity-40"></div>

                    {{-- Vision --}}
                    <div class="text-left pr-6">
                        <div class="flex items-center gap-3 mb-4 text-[#6b7c2f]">
                            <div
                                class="w-10 h-10 rounded-full border-2 border-[#6b7c2f] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h4 class="text-[22px] font-semibold">Tầm nhìn</h4>
                        </div>

                        <p class="text-gray-600 leading-relaxed text-[16px]">
                            Trở thành hệ thống spa nghỉ dưỡng hàng đầu Việt Nam, là biểu tượng
                            của chuẩn mực thư giãn – trị liệu cao cấp, không ngừng nâng chuẩn
                            dịch vụ phát triển theo tiêu chuẩn wellness quốc tế.
                        </p>
                    </div>

                    {{-- Mission --}}
                    <div class="text-left pl-0 md:pl-6">
                        <div class="flex items-center gap-3 mb-4 text-[#6b7c2f]">
                            <div
                                class="w-10 h-10 rounded-full border-2 border-[#6b7c2f] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 20h14" />
                                </svg>
                            </div>
                            <h4 class="text-[22px] font-semibold">Sứ mệnh</h4>
                        </div>

                        <p class="text-gray-600 leading-relaxed text-[16px]">
                            Mang đến hành trình chăm sóc sức khỏe – sắc đẹp toàn diện & cá nhân hóa
                            giúp mỗi khách hàng cảm nhận sự thư giãn sâu, phục hồi và nguồn năng lượng
                            tích cực trong từng khoảnh khắc.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
