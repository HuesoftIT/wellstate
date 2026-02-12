<section class="w-full bg-[#3f4a1e]">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[700px]">

        {{-- LEFT IMAGE --}}
        <div class="lg:col-span-5 relative">
            <img src="{{ asset('images/featured_servicesg.jpg') }}" alt="Sana Spa"
                class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="lg:col-span-7 px-6 lg:px-14 py-16 text-white relative bg-[#383e1a]">

            {{-- HEADER --}}
            <p class="text-[16px] tracking-widest text-[#FFDC97] mb-3">
                WELLSTATE
            </p>

            <h2 class="font-open-sans text-[58px] font-medium text-[#cddc8c] mb-4">
                Dịch vụ nổi bật
            </h2>

            <p class="text-[16px]  text-[#FFDC97] max-w-xl mb-6 leading-relaxed">
                WELLSTATE
                mang đến giải pháp chăm sóc sức khỏe khoa học, an toàn,
                tối ưu và cá nhân hóa dựa trên y học cổ truyền Trung Hoa
                và công nghệ hiện đại.
            </p>

            <a href="/lien-he"
                class="inline-flex items-center gap-2
          bg-[#829137]
          px-6 py-3 text-sm text-white rounded-sm
          hover:bg-[#8fa63b] transition mb-14 mt-10">
                ĐẶT LIỆU TRÌNH
                <span>→</span>
            </a>


            {{-- SERVICES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- ITEM --}}
                <div class="bg-[#88a44d] rounded-lg overflow-hidden">
                    <div class="relative">
                        <span
                            class="absolute top-3 left-3
           text-5xl md:text-4xl
           font-extrabold
           text-white leading-none tracking-tight">
                            01
                        </span>

                        <img src="{{ asset('images/featured_services.jpg') }}" class="w-full h-44 object-cover" />
                    </div>
                    <div class="p-4 text-sm">
                        <h3 class="font-medium mb-2 text-[20px]">
                            Massage trị liệu & Thư giãn
                        </h3>
                        <p class="text-[16px] text-gray-100 leading-relaxed">
                            Kết hợp dưỡng sinh cổ truyền và công nghệ cao giúp
                            giải phóng căng thẳng, phục hồi năng lượng.
                        </p>
                    </div>
                </div>

                {{-- ITEM --}}
                <div class="bg-[#88a44d] rounded-lg overflow-hidden">
                    <div class="relative">
                        <span
                            class="absolute top-3 left-3
           text-5xl md:text-4xl
           font-extrabold
           text-white leading-none tracking-tight">
                            01
                        </span>

                        <img src="{{ asset('images/featured_services.jpg') }}" class="w-full h-44 object-cover" />
                    </div>
                    <div class="p-4 text-sm">
                        <h3 class="font-medium mb-2 text-[20px]">
                            Gội đầu dưỡng sinh
                        </h3>
                        <p class="text-[16px] text-gray-100 leading-relaxed">
                            Làm sạch sâu da đầu, thư giãn thần kinh, cải thiện
                            tuần hoàn máu và giảm stress.
                        </p>
                    </div>
                </div>

                {{-- ITEM --}}
                <div class="bg-[#88a44d] rounded-lg overflow-hidden">
                    <div class="relative">
                        <span
                            class="absolute top-3 left-3
           text-5xl md:text-4xl
           font-extrabold
           text-white leading-none tracking-tight">
                            01
                        </span>

                        <img src="{{ asset('images/featured_services.jpg') }}" class="w-full h-44 object-cover" />
                    </div>
                    <div class="p-4 text-sm">
                        <h3 class="font-medium mb-2 text-[20px]">
                            Chăm sóc da công nghệ cao
                        </h3>
                        <p class="text-[16px] text-gray-100 leading-relaxed">
                            Ứng dụng công nghệ hiện đại giúp trẻ hóa làn da,
                            làm sáng da và tăng độ đàn hồi.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
