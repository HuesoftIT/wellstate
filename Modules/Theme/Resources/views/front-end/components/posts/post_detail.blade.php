<section class="bg-[#f7faec] py-16">
    <div class="container mx-auto px-6">

        {{-- IMAGE FULL WIDTH --}}
        @if ($data->image)
            <div class="mb-12">
                <img src="{{ Storage::url($data->image) }}" alt="{{ $data->title }}"
                    class="w-full h-auto max-h-[70vh] object-contain rounded-2xl 
                   transition-transform duration-500 ease-out hover:scale-[1.01]"
                    loading="lazy" decoding="async">
            </div>
        @endif

        {{-- TEXT CONTENT --}}

        <h1 class="text-[40px] md:text-3xl font-semibold text-[#3c4a1a] mb-2 uppercase">
            {{ $data->title }}
        </h1>
        @if ($data->published_at)
            <div class="flex items-center gap-2 mb-6 text-[#829137] text-[16px]">
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-calendar"></i>
                    <span>{{ $data->published_at->format('d/m/Y') }}</span>
                </div>
            </div>
        @endif



        <article class="prose prose-lg max-w-none">
            {!! $data->content !!}
        </article>

    </div>


</section>
