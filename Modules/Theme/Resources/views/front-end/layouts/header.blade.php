<header class="fixed top-0 right-0 left-0 z-20 bg-[#3b401c] h-[100px] flex items-center font-inter">


    <div class="container mx-auto h-full flex items-center justify-between px-6">

        <!-- LEFT: Logo + Menu -->
        <div class="flex items-center gap-10 h-full">

            <!-- Logo -->
            <a href="{{ route('page.home')}}" class="flex items-center h-full">
                <img src="{{ asset($logo) }}" alt="SANA Holding" class="h-[60px] object-contain">
            </a>

            <!-- Menu -->
            <nav class="h-full hidden lg:flex">
                <ul class="flex items-center h-full gap-6 uppercase text-[16px] font-medium text-white">
                    <li>
                        <a href="{{ route('page.home')}}" class="hover:text-[#c6d36a] transition font-light">Trang chủ</a>
                    </li>

                    <li>
                        <a href="{{ route('page.introduce')}}" class="hover:text-[#c6d36a] transition font-light">Về chúng tôi</a>
                    </li>


                    @foreach ($post_categories as $parent)
                        <li class="relative group">
                            {{-- MENU CHA --}}
                            <a href="/{{ $parent->slug }}"
                                class="flex items-center gap-1 hover:text-[#c6d36a] transition font-light">
                                {{ $parent->name }}
                                @if ($parent->children->count())
                                    <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </a>


                            {{-- khoảng hover --}}
                            <div class="absolute h-[40px] w-full top-[100%] left-0"></div>


                            {{-- DROPDOWN CON --}}
                            @if ($parent->children->count())
                                <div
                                    class="absolute left-0 top-full mt-4
                                    w-[300px]
                                    bg-white text-black
                                    shadow-xl rounded-md
                                    opacity-0 invisible
                                    translate-y-2
                                    group-hover:opacity-100
                                    group-hover:visible
                                    group-hover:translate-y-0
                                    transition-all duration-200
                                    z-50">

                                    <ul class="py-2">
                                        @foreach ($parent->children as $child)
                                            <li>
                                                <a href="/{{ $parent->slug }}/{{ $child->slug }}"
                                                    class="block px-5 py-3 text-[15px]
                                                    hover:bg-[#f5f8eb]
                                                    hover:text-[#7a8f2c]
                                                    transition">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach



                    <li>
                        <a href="{{ route('page.contact') }}" class="hover:text-[#c6d36a] transition font-light">Liên
                            hệ</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- RIGHT: Language + Button -->
        <div class="hidden lg:flex items-center justify-content-end gap-4 ">

            <!-- Button -->
            <a href="{{ route('page.booking') }}"
                class="inline-block ml-4 px-6 py-2 bg-[#8ea33a] text-white font-semibold rounded-sm hover:bg-[#a5bc45] transition text-[15px] font-bold ">
                ĐẶT LỊCH
            </a>

        </div>
        <div class="inline-block lg:hidden">
            <button id="mobile-menu-button" class="text-white focus:outline-none w-6 h-6">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<div class="h-[100px]"></div>

<!-- Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/60 z-40 hidden transition-opacity font-inter"></div>


<!-- Mobile Menu Panel -->
<div id="mobile-menu"
    class="fixed top-0 right-0 w-[280px] h-screen bg-[#3b401c] z-50
           translate-x-full transition-transform duration-300
           font-inter flex flex-col">


    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b border-white/20 relative">
        <img src="{{ asset($logo) }}" alt="SANA Holding" class="h-[45px] mx-auto">

        <button id="mobile-menu-close" class="text-white text-xl absolute top-3 right-4">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto overscroll-contain">
        <!-- Menu -->
        <nav class="p-6">
            <ul class="flex flex-col gap-4 uppercase text-white text-[16px] font-medium">

                <!-- Trang chủ -->
                <li>
                    <a href="{{ route('page.home') }}" class="hover:text-[#c6d36a] font-light">
                        Trang chủ
                    </a>
                </li>

                <!-- Giới thiệu -->
                <li>
                    <a href="{{ route('page.introduce') }}" class="hover:text-[#c6d36a] font-light">
                        Về chúng tôi
                    </a>
                </li>

                @foreach ($post_categories as $parent)
                    <li>
                        {{-- MENU CHA --}}
                        <button
                            class="flex items-center uppercase justify-between w-full hover:text-[#c6d36a] font-light"
                            onclick="toggleSubMenu(this)">
                            <span>{{ $parent->name }}</span>

                            @if ($parent->children->count())
                                <i class="fa-solid fa-chevron-down text-[13px] transition-transform"></i>
                            @endif
                        </button>

                        {{-- MENU CON --}}
                        @if ($parent->children->count())
                            <ul class="mt-2 ml-4 hidden flex-col gap-2 text-[15px] normal-case font-normal">
                                @foreach ($parent->children as $child)
                                    <li>
                                        <a href="/{{ $parent->slug }}/{{ $child->slug }}"
                                            class="block py-2 hover:text-[#c6d36a]">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach


                <!-- Liên hệ -->
                <li>
                    <a href="{{ route('page.contact') }}" class="hover:text-[#c6d36a] font-light">
                        Liên hệ
                    </a>
                </li>
            </ul>

            <!-- Info -->
            <div class="mt-8 text-white text-sm leading-relaxed flex flex-col items-center gap-1 text-center">
                <p class="font-semibold">
                    CONTACT FOR BOOKING +84 3653 1863
                </p>
                <p class="text-xs">
                    OPERATING HOURS 9:00 AM - 11:00 PM DAILY
                </p>
            </div>

            <!-- CTA -->
            <a href="{{ route('page.booking') }}"
                class="inline-block mt-8 px-6 py-2 bg-[#8ea33a] text-white font-semibold
                  rounded-sm hover:bg-[#a5bc45] transition text-[15px]">
                ĐẶT LỊCH
            </a>
        </nav>
    </div>

</div>
