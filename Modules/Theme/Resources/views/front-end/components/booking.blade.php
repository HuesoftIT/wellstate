<section class="bg-[#383e1a] py-20">
    <!-- WRAPPER CHUNG -->
    <div class="mx-auto max-w-4xl px-6 font-open-sans">

        <!-- HEADER -->
        <div class="mb-10">
            <p class="text-xs tracking-widest text-[#c7d48b] mb-2">
                WELLSTATE
            </p>
            <h2 class="text-[58px] mt-4 font-medium text-[#c7d48b]">
                ĐẶT LỊCH NGAY
            </h2>
        </div>

        <!-- BOOKING BOX -->
        <div
            class="bg-[#fbf6e8]
                   rounded-xl
                   shadow-xl
                   p-6 md:p-10">

            <!-- TABS -->
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach ($branches as $index => $branch)
                    <button
                        class="branch-tab
                   px-[35px] py-[15px] text-sm rounded
                   transition-all duration-300 ease-in-out
                   hover:bg-[#2f3320] hover:text-white
                   {{ $index === 0 ? 'bg-[#383e1a] text-white' : 'bg-[#e8e1cc] text-[#4c4c4c]' }}"
                        data-name="{{ $branch->name }}"
                        data-image="{{ $branch->image ? Storage::url($branch->image) : '' }}"
                        data-lat="{{ $branch->latitude }}" data-lng="{{ $branch->longitude }}">
                        {{ $branch->name }}
                    </button>
                @endforeach
            </div>


            <!-- CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">

                <!-- IMAGE -->
                <img id="branchImage"
                    src="{{ $branches->first() && $branches->first()->image ? Storage::url($branches->first()->image) : '' }}"
                    class="rounded-lg w-full h-[260px] object-cover" />

                <!-- MAP -->
                <div class="space-y-4">
                    <iframe id="branchMap" class="w-full h-[250px] rounded-lg border" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="
                @if ($branches->first()) https://www.google.com/maps?q={{ $branches->first()->latitude }},
                {{ $branches->first()->longitude }}&output=embed @endif
            ">
                    </iframe>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <p class="font-medium text-[#4c4c4c] text-[36px] font-cormorant text-center">
                    Book lịch ngay:
                </p>

                <a href="/dat-lich" class="bg-[#7a8f2c] font-cormorant text-white px-5 py-2 text-[15px]">
                    BOOK NGAY →
                </a>
            </div>
        </div>

    </div>
</section>
