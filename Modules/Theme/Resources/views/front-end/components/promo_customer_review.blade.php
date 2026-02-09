<section class="bg-[#ecf4dd] py-20">
    <div class="container mx-auto px-6">

        <h2 class="text-center text-[58px] font-open-sans font-medium text-[#8aa24a] mb-12">
            Sự kiện - Ưu đãi
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- FEATURED POST --}}
            @if (isset($posts[0]))
                <a href="{{ route('detail.post', $posts[0]->slug) }}"
                    class="relative rounded-3xl overflow-hidden group h-[320px] md:h-[420px]">
                    <img src="{{ Storage::url($posts[0]->image) }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>

                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <p class="text-xs uppercase tracking-widest opacity-80 mb-2 text-[#adb5bd]">
                            Ưu đãi nổi bật
                        </p>
                        <h3 class="text-[20px] font-open-sans uppercase font-semibold leading-snug">
                            {{ $posts[0]->title }}
                        </h3>
                        <p class="text-sm opacity-80 mt-2 text-[#adb5bd]">
                            {{ optional($posts[0]->published_at)->format('d/m/Y') ?? $posts[0]->created_at->format('d/m/Y') }}
                        </p>

                    </div>
                </a>
            @endif

            {{-- LIST POSTS --}}
            <div class="space-y-4">
                @foreach ($posts->slice(1, 4) as $post)
                    <a href="{{ route('detail.post', $post->slug) }}"
                        class="flex gap-4 bg-white/70 rounded-2xl p-3 hover:bg-white transition">
                        <img src="{{ Storage::url($post->image) }}"
                            class="w-28 h-20 rounded-xl object-cover flex-shrink-0">

                        <div>
                            <h3 class="font-medium text-[16px] line-clamp-2 text-[#4c4c4c]">
                                {{ $post->title }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ ($post->published_at ?? $post->created_at)->format('d/m/Y') }}
                            </p>

                        </div>
                    </a>
                @endforeach

                <a href="{{ route('posts.index') }}"
                    class="inline-flex items-center text-[16px] font-medium text-[#7a8f2c] hover:underline mt-2">
                    Xem tất cả bài viết →
                </a>
            </div>

        </div>
    </div>
</section>

<section class="bg-[#ecf4dd] py-20">
    <div class="container mx-auto px-6">

        <h2 class="text-center text-[58px] font-open-sans font-medium text-[#8aa24a] mb-12">
            Cảm nhận của khách hàng
        </h2>

        {{-- SWIPER IMAGES --}}
        <div class="swiper customerSwiper">
            <div class="swiper-wrapper">

                {{-- IMAGE ITEM --}}
                @foreach ($customer_review_images as $image)
                    <div class="swiper-slide">
                        <div class="overflow-hidden rounded-2xl shadow">
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $image->title }}"
                                class="w-full h-[320px] object-cover hover:scale-105 transition duration-500">
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6">
            @foreach ($customer_google_review_images as $image)
                <div class="mb-6 break-inside-avoid">
                    <div class="rounded-2xl overflow-hidden shadow-lg bg-white">
                        <img src="{{ Storage::url($image->image) }}" alt="{{ $image->title }}"
                            class="w-full object-cover hover:scale-105 transition duration-500">
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
