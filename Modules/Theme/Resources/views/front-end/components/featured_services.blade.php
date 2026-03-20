<section class="w-full bg-[#2f3a16]">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[720px]">

        {{-- LEFT IMAGE --}}
        <div class="lg:col-span-5 relative">
            <img src="{{ $images_featured_service->first() ? Storage::url($images_featured_service->first()->image) : asset('images/featured_services.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="lg:col-span-7 px-6 lg:px-16 py-16 relative bg-[#3a451c] text-white overflow-hidden">

            {{-- background pattern --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                style="background-image: url('{{ asset('images/pattern.png') }}'); background-size: cover;">
            </div>

            {{-- HEADER --}}
            <p class="text-[14px] tracking-[3px] text-[#e6d08f] mb-3">
                WELLSTATE
            </p>

            <h2 class="text-[48px] lg:text-[58px] font-light text-[#c9d67a] mb-6">
                Dịch vụ nổi bật
            </h2>

            <p class="text-[15px] text-[#e6d08f] max-w-xl leading-relaxed mb-8">
                WELLSTATE mang đến giải pháp chăm sóc sức khỏe khoa học,
                an toàn, tối ưu và chủ động nhờ sự kết hợp giữa y học cổ truyền
                Trung Hoa và công nghệ hiện đại.
            </p>

            <a href="{{ route('page.contact') }}"
                class="inline-flex items-center gap-2
                      bg-[#8fa63b]
                      px-6 py-3 text-sm text-white
                      hover:bg-[#9fb84a] transition mb-16">
                ĐẶT LỊCH TƯ VẤN →
            </a>

            {{-- SERVICES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- CARD --}}
                @php
                    $images = ['images/featured_services.jpg', 'images/w8.jpg', 'images/w16.jpg'];
                @endphp
                @foreach ([1, 2, 3] as $i)
                    <div class="relative bg-[#88a44d] rounded-xl p-4 overflow-hidden">

                        {{-- big number --}}
                        <span class="absolute top-2 left-4 text-[64px] font-light text-white/70">
                            0{{ $i }}
                        </span>

                        {{-- pattern overlay --}}
                        <div class="absolute inset-0 opacity-10"
                            style="background-image:url('{{ asset('images/pattern.png') }}'); background-size:cover;">
                        </div>

                        {{-- content --}}
                        <div class="relative mt-20">

                            <img src="{{ asset($images[$i - 1]) }}" class="w-full h-44 object-cover rounded-lg mb-4" />

                            <h3 class="text-[18px] font-semibold mb-2">
                                {{ $i == 1 ? 'Massage Trị liệu và Thư giãn' : ($i == 2 ? 'Gội đầu dưỡng sinh' : 'Chăm sóc da công nghệ cao') }}
                            </h3>

                            <p class="text-[16px] text-white/90 leading-relaxed">
                                {{ $i == 1
                                    ? 'Kết hợp dưỡng sinh cổ truyền và công nghệ cao giúp hoạt huyết, lưu thông khí huyết, phục hồi năng lượng.'
                                    : ($i == 2
                                        ? 'Kỹ thuật điều khiển trên giường cong signature giúp thư giãn vùng đầu, cổ, vai gáy.'
                                        : 'Ứng dụng công nghệ hiện đại giúp trẻ hóa làn da, làm sáng và tăng độ đàn hồi.') }}
                            </p>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
