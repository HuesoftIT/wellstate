<div class="bg-[#f4f8ea]">
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start bg-[#fafcf5] py-4 px-8 rounded-md">

            <!-- LEFT: IMAGE -->
            <div class="lg:col-span-6">
                <div class="rounded-[20px] overflow-hidden">
                    <img src="{{ $post->image ? Storage::url($post->image) : asset('images/default-service.jpg') }}"
                        alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- RIGHT: CONTENT -->
            <div class="lg:col-span-6">

                <p class="text-[20px] font-open-sans font-medium text-[#6f7f3a] mb-2">
                    Sản phẩm: {{ $post->title }}
                </p>

                <h1 class="text-[40px] font-open-sans font-medium text-[#2f3b1c] leading-tight mb-6">
                    {{ $post->title }}
                </h1>

                <p class="text-[34px] font-open-sans font-medium text-[#2f3b1c] mb-8">
                    Liên hệ: <span class="font-semibold">{{ $company_phone }}</span>
                </p>

                <a href="#"
                    class="flex items-center justify-center gap-2 px-6 py-4
                           bg-[#3b4a1d] text-[#f4d27a] rounded-[14px]
                           hover:bg-[#2f3b17] transition text-[16px] uppercase">
                    ĐẶT LỊCH NGAY
                    <span class="text-[12px] bg-[#b57a1a] text-white px-2 py-1 rounded">
                        WELLSTATE HUẾ
                    </span>
                </a>


                <!-- ADDRESS -->
                <ul class="space-y-4 text-[#2f3b1c] text-[18px] mt-10">
                    @foreach ($branches as $index => $branch)
                        <li>📍 <span>Cơ sở {{ $index }}:</span> {{ $branch->name }}<br>
                            Địa chỉ: {{ $branch->address }}</li>
                    @endforeach



                </ul>

            </div>
        </div>


    </div>

    <div class="container mx-auto px-6 pb-4">
        <div class="bg-[#fafcf5] rounded-md py-4 px-8">
            <h3 class="font-open-sans text-[32px] font-semibold text-[#2f3b1c] mb-6">{{ $post_category->name }}</h3>
            <div class="max-w-none list-decimal">
                {!! $post->content !!}
            </div>
        </div>

    </div>
</div>
