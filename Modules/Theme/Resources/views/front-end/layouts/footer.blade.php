<footer class="relative overflow-hidden text-base text-white">
    <img src="{{ asset('images/featured_services.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
        alt="">

    <div class="absolute inset-0 bg-gradient-to-b
                from-[#2f3b1f]/80 via-[#2f3b1f]/90 to-[#1f2a14]/95">
    </div>

    <div class="relative z-10">
        <div class="container mx-auto px-6 py-20">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                <!-- LOGO -->
                <div>
                    <img src="{{ asset('images/wellstate-logo.png') }}" class="h-14 mb-6" alt="Wellstate Logo">

                    <p class="leading-relaxed">
                        Wellstate – Đến để tái tạo và cân bằng năng lượng.
                        Giữa nhịp sống hiện đại, Wellstate là điểm đến
                        lý tưởng để bạn dừng lại, thư giãn và lắng nghe cơ thể.
                    </p>

                    <div class="mt-6 space-y-2">
                        @if (!empty($company_phone))
                            <span>Hotline: {{ $company_phone }}</span>
                        @endif
                    </div>

                    <h4 class="mt-8 font-semibold uppercase tracking-wide">
                        Chi nhánh
                    </h4>

                    <ul class="mt-4 space-y-4">
                        @forelse ($branches as $branch)
                            <li>
                                <p class="font-medium">{{ $branch->name }}</p>
                                <p class="text-sm opacity-90">{{ $branch->address }}</p>
                            </li>
                        @empty
                        @endforelse



                    </ul>
                </div>

                <!-- SERVICES -->
                <div>
                    <h4 class="font-semibold uppercase mb-6">
                        Dịch vụ của chúng tôi
                    </h4>

                    <ul class="space-y-4">
                        @foreach ($service_categories as $item)
                            <li class="hover:text-[#FFDC97] transition">
                                <a
                                    href="{{ route('slugDetail.getDetail', ['slugParent' => 'dich-vu', 'slugDetail' => $item->slug]) }}">
                                    {{ $item->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- SOCIAL -->
                <div>
                    <h4 class="font-semibold uppercase mb-6">
                        Mạng xã hội
                    </h4>

                    <div class="flex gap-3 mb-6">
                        @if (!empty($fb_link))
                            <a href="{{ $fb_link }}"
                                class="w-10 h-10 rounded flex items-center justify-center transition">
                                <img src="{{ asset('images/fb.png') }}" alt="Facebook">
                            </a>
                        @else

                        @endif
                    </div>

                </div>

            </div>

            <div
                class="mt-16 pt-6 border-t border-white/20
                    flex flex-col md:flex-row justify-between gap-4">
                <span>© 2025 Wellstate</span>
                @if (!empty($company_phone))
                    <span>Hotline: {{ $company_phone }}</span>
                @endif
            </div>

        </div>
    </div>

</footer>
