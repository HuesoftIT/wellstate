<div class="w-full overflow-hidden">
    <div class="swiper mySwiper w-full max-w-full h-[80vh] slideSwiper">
        <div class="swiper-wrapper h-full">

            @foreach ($slides as $slide)
                <div class="swiper-slide w-full max-w-full">
                    @if (!empty($slide->link))
                        <a href="{{ $slide->link }}" class="block w-full h-full">
                    @endif

                    <img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title ?? '' }}"
                        class="w-full h-full  object-cover">

                    @if (!empty($slide->link))
                        </a>
                    @endif
                </div>
            @endforeach

        </div>

    
    </div>
</div>
