<div class="container mx-auto px-4 md:px-6 py-12 md:py-16 overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 items-center">

        <!-- Left Image -->
        <div class="md:col-span-3 w-full slide-left opacity-0">
            <img src="{{ asset("images/service-introduce-1.png")}}" alt="Spa hallway"
                class="w-full max-w-full h-auto object-cover rounded-md">
        </div>

        <!-- Content -->
        <div class="md:col-span-6 text-center md:text-left">
            <h2
                class="text-[28px] md:text-[40px] uppercase font-semibold font-open-sans tracking-wide text-[#3b4a2f] mb-4 md:mb-6">
                {{ $service_category->name }}
            </h2>

            <p class="text-[#4a4a4a] leading-relaxed text-[15px] md:text-[16px]">
                {{ $service_category->description}}
            </p>
        </div>

        <!-- Right Images -->
        <div class="md:col-span-3 flex flex-col items-center gap-6 w-full slide-right opacity-0">
            <img src="{{ asset($logo)}}" alt="Wellstate Logo" class="w-32 md:w-40 h-auto">

            <img src="{{ asset("images/service-introduce-2.jpg")}}" alt="Spa interior"
                class="w-full max-w-full h-auto rounded-[20px]">
        </div>

    </div>
</div>