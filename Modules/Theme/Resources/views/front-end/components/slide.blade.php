<div class="w-full overflow-hidden">
    <div class="swiper mySwiper slideSwiper w-full h-[100vh]">
        <div class="swiper-wrapper h-full">

            @foreach ($slides as $slide)
                <div class="swiper-slide h-full">

                    @if (!empty($slide->link))
                        <a href="{{ $slide->link }}" class="block w-full h-full">
                    @endif

                    <img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title ?? '' }}"
                        class="h-full w-full object-contain">

                    @if (!empty($slide->link))
                        </a>
                    @endif

                </div>
            @endforeach

        </div>
    </div>
</div>
