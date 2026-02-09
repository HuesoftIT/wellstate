<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-12 gap-8 items-center">

        <!-- LEFT: Image (3) -->
        <div class="col-span-12 md:col-span-3 slide-left">
            <img src="{{ asset('images/service-introduce-1.png') }}" alt="Spa hallway"
                class="w-full h-auto object-cover rounded-md">
        </div>

        <!-- CENTER: Content (6) -->
        <div class="col-span-12 md:col-span-6 text-center md:text-left">
            <h2 class="text-[40px] uppercase text-center font-semibold font-open-sans tracking-wide text-[#3b4a2f] mb-6">
                {{ $post_category->name }}
            </h2>

            <p class="text-[#4a4a4a] leading-relaxed text-[16px]">
                {{ $post_category->description }}
            </p>
        </div>

        <!-- RIGHT: Logo + Image (3) -->
        <div class="col-span-12 md:col-span-3 flex flex-col items-center gap-6">
            <img src="{{ asset('images/wellstate-logo.png') }}" alt="SANA Holding" class="w-40 float-y">

            <img src="{{ asset('images/service-introduce-2.jpg') }}" alt="Spa interior"
                class="w-full rounded-[20px] slide-right">
        </div>

    </div>
</div>
