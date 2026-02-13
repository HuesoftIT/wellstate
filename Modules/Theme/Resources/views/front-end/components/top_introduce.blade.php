<section class="relative overflow-hidden bg-[#f5f8ea] py-20">
    {{-- Background SVG --}}
    <div class="absolute inset-0 bg-no-repeat bg-center bg-contain opacity-10"
        style="background-image: url('{{ asset('images/pattern.svg') }}')">
    </div>

    {{-- Content --}}
    <div class="relative container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center text-[#4b5b2a]">

            {{-- Logo --}}
            <img src="{{ asset('images/wellstate-logo.png') }}" alt="Wellstate" class="mx-auto mb-6 w-32 md:w-36 float-y">

            {{-- Title --}}
            <h1 class="text-4xl md:text-5xl font-serif tracking-wide">
                WELLSTATE
            </h1>

            <p class="mt-2 text-sm tracking-[0.3em] text-[#6b7a3a]">
                HOLDING
            </p>

            {{-- Divider --}}
            <div class="flex justify-center my-6">
                <span class="w-16 h-px bg-[#6b7a3a]"></span>
            </div>

            {{-- Description --}}
            <p class="text-base leading-relaxed text-[#4c4c4c]">
                Wellstate là điểm đến chăm sóc sức khỏe và sắc đẹp mang tinh thần
                nghỉ dưỡng cao cấp. Như một thánh địa của sự an yên, Wellstate kết hợp tinh
                hoa trị liệu Á Đông cùng công nghệ hiện đại để tạo nên hành trình thư giãn
                trọn vẹn. Mỗi liệu trình được thiết kế như một tác phẩm nghệ thuật riêng
                biệt, giúp tái tạo nguồn năng lượng mới, để vẻ đẹp bên ngoài thực sự tỏa
                sáng từ sức khỏe bên trong.
            </p>

            <p class="mt-4 font-medium text-[#4b5b2a]">
                Wellstate – Nâng niu giác quan, nuôi dưỡng an lành.
            </p>
        </div>
    </div>

</section>
