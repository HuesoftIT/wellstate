<section class="bg-[#f7faec] py-16">
    <div class="container mx-auto px-6">

        {{-- IMAGE FULL WIDTH --}}
        @if ($data->image)
            <div class="mb-12">
                <img src="{{ Storage::url($data->image) }}" alt="{{ $data->title }}"
                    class="w-full rounded-2xl shadow object-contain max-h-[520px]">
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
