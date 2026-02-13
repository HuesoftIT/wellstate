<section class="w-full bg-[#3f4a1f] py-24">
    <div class="container mx-auto px-6">

        {{-- Section heading --}}
        <div class="text-center mb-16">
            <p class="text-xs tracking-widest text-white/80 mb-3">
                Wellstate
            </p>

            <h2 class="font-open-sans text-[48px] italic text-[#f5e6b3]">
                VĂN HÓA DOANH NGHIỆP
            </h2>
        </div>

        {{-- Culture cards --}}
        <div class="flex flex-wrap justify-center gap-8">

            @foreach ($culture_images as $item)
                <div class="group relative w-[220px] h-[320px] rounded-2xl overflow-hidden">

                    {{-- Image --}}
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

                    {{-- Overlay --}}
                    <div
                        class="absolute inset-0 bg-black/40
                               transition-opacity duration-500
                               group-hover:opacity-0">
                    </div>

                    {{-- Title --}}
                    <div class="absolute bottom-4 left-0 right-0 text-center z-10">
                        <p class="text-white font-semibold text-sm drop-shadow text-[16px] font-inter">
                            {{ $item->title }}
                        </p>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
