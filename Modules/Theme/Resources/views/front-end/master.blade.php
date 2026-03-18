<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @section('title')
        <title>{{ !empty($settings['meta_title']) ? $settings['meta_title'] : trans('frontend.title') }}</title>
        <meta name="description"
            content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}" />
        <meta name="keywords"
            content="{{ !empty($settings['meta_keyword']) ? $settings['meta_keyword'] : trans('frontend.keyword') }}" />
    @show
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('sitemap.xml') }}" />
    <meta content="INDEX,FOLLOW" name="robots" />
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes, minimal-ui">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="google" value="notranslate">
    <meta name="google-site-verification" content="">
    <meta name="copyright" content="{{ $settings['meta_title'] }}" />
    <meta name="author" content="{{ $settings['meta_title'] }}" />
    <meta name="GENERATOR" content="{{ $settings['meta_title'] }}" />
    <meta http-equiv="audience" content="General" />
    <meta name="resource-type" content="Document" />
    <meta name="distribution" content="Global" />
    <meta name="geo.position" content="Huế" />
    <meta name="geo.region" content="VN" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}" />
    <meta property="fb:app_id" content="678581042953588" />
    <meta property="og:site_name" content="{{ $settings['company_link'] }}" />
    <meta property="og:type" content="product" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:url" itemprop="url" content="{{ Request::fullUrl() }}" />
    @section('facebook')
        <meta property="og:title"
            content="{{ !empty($settings['meta_title']) ? $settings['meta_title'] : trans('frontend.title') }}" />
        <meta property="og:description"
            content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}" />
        <meta property="og:image" content="{{ asset(Storage::url($settings['company_logo'])) }}" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="315" />
    @show
    <meta name="twitter:card" content="article" />
    <meta name="twitter:description" content="{{ $settings['meta_description'] }}" />
    <meta name="twitter:title" content="{{ $settings['meta_title'] }}" />
    <meta name="twitter:image" content="{{ asset(Storage::url($settings['company_logo'])) }}" />
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="shortcut icon" href="{{ asset($settings['company_logo'] ?? 'img/favicon.ico') }}" />

    <link rel="canonical" href="{{ Request::fullUrl() }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/huesoft_css/swiper.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

    <style>
        @font-face {
            font-family: 'Cormorant Upright';
            src: url('/fonts/cormorant-upright/CormorantUpright-Light.ttf') format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cormorant Upright';
            src: url('/fonts/cormorant-upright/CormorantUpright-Regular.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cormorant Upright';
            src: url('/fonts/cormorant-upright/CormorantUpright-Medium.ttf') format('truetype');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cormorant Upright';
            src: url('/fonts/cormorant-upright/CormorantUpright-SemiBold.ttf') format('truetype');
            font-weight: 600;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cormorant Upright';
            src: url('/fonts/cormorant-upright/CormorantUpright-Bold.ttf') format('truetype');
            font-weight: 700;
            font-style: normal;
        }



        @font-face {
            font-family: 'Lora';
            src: url('/fonts/lora/Lora-Italic-VariableFont_wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Open Sans';
            src: url('/fonts/open-sans/OpenSans-VariableFont_wdth,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Open Sans';
            src: url('/fonts/open-sans/OpenSans-Italic-VariableFont_wdth,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Inter';
            src: url('/fonts/inter/Inter-VariableFont_opsz,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Inter';
            src: url('/fonts/inter/Inter-Italic-VariableFont_opsz,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-style: italic;
            font-display: swap;
        }

        .font-inter {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .font-open-sans {
            font-family: 'Open Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }


        .font-cormorant {
            font-family: 'Cormorant Upright', serif;
        }

        body {
            font-family: 'Lora', serif;
            line-height: 1.15 !important;
        }

        ol {
            list-style-type: decimal !important;
            padding: 0 0 0 40px !important;
        }

        body {
            color: #4C4C4C;
        }

        .customerSwiper {
            padding-bottom: 40px;
        }

        .customerSwiper .swiper-slide {
            height: auto;
        }


        .swiper {
            width: 100%;
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 300px;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
        }

        .float-y {
            animation: floatY 4s ease-in-out infinite;
            will-change: transform;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-80px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(80px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .slide-left {
            animation: slideInLeft 0.9s ease-out forwards;
        }

        .slide-right {
            animation: slideInRight 0.9s ease-out forwards;
        }

        @keyframes floatY {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        #booking .input {
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            padding-left: 1rem;
            padding-right: 1rem;
            width: 100%;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;

        }

        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;

            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #ffffff;
            color: #16a34a;

            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

            cursor: pointer;

            opacity: 0;
            visibility: hidden;

            transition: all 0.3s ease;
        }

        .scroll-to-top:hover {
            transform: translateY(-3px);
        }

        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
    </style>

    {!! str_replace('<br />', '', $settings['google_analytics']) !!}
</head>

<body>
    {!! str_replace('<br />', '', $settings['fanpage_facebook_body']) !!}
    @section('schema')
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "{{ $settings['company_website'] }}",
                "alternateName": "{{ $settings['meta_title'] }}",
                "url": "{{ url('/') }}",
                "logo": "{{ asset(Storage::url($settings['company_logo'])) }}",
                "sameAs": [
                    "{{ $settings['follow_facebook'] }}",
                    "{{ $settings['follow_twitter'] }}",
                    "{{ $settings['follow_linked'] }}",
                    "{{ $settings['follow_google'] }}"
                ],
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $settings['company_address'] }}",
                    "addressRegion": "Hue",
                    "postalCode": "49000",
                    "addressCountry": "VN"
                }
            }

    </script>
        <div class="app">
            @include('theme::front-end.layouts.header')
        @section('content')
        @show
        @include('theme::front-end.layouts.footer')
        @include('theme::front-end.components.scrollToTop')
    </div>

    <script>
        const menuBtn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        const closeBtn = document.getElementById('mobile-menu-close');

        menuBtn.addEventListener('click', () => {
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);

        function closeMenu() {
            menu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }

        function toggleSubMenu(button) {
            const submenu = button.nextElementSibling;
            submenu.classList.toggle('hidden');
        }
    </script>

</body>

<script src="{{ asset('js/huesoft_js/jquery.js') }}"></script>
<script src="{{ asset('js/huesoft_js/swiper.js') }}"></script>
<script src="{{ asset('js/sweetalert2/sweetalert2@11.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const scrollBtn = document.getElementById("scrollToTopBtn");
        window.addEventListener("scroll", function() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add("show");
            } else {
                scrollBtn.classList.remove("show");
            }
        });

        scrollBtn.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

    });
</script>
<script>
    function formatVnd(price) {
        return Number(price).toLocaleString('vi-VN') + 'đ';
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.slideSwiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },

        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        const section = document.getElementById('stats-section');
        const numbers = document.querySelectorAll('.stat-number');
        let started = false;
        if (!section || numbers.length === 0) return;
        const countUp = (el) => {
            const target = +el.dataset.target;
            const duration = 2000;
            const startTime = performance.now();

            const update = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                el.textContent = Math.floor(progress * target).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            };

            requestAnimationFrame(update);
        };

        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !started) {
                numbers.forEach(countUp);
                started = true;
                observer.disconnect();
            }
        }, {
            threshold: 0.4
        });

        observer.observe(section);
    });

    const promoSwiper = new Swiper(".promoSwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 2,
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true,
        },

    });
    const spaceSwiper = new Swiper(".spaceSwiper", {
        loop: true,
        spaceBetween: 16,
        grabCursor: true,

        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            640: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 5,
            },
        },
    });

    new Swiper('.customerSwiper', {
        slidesPerView: 5,
        spaceBetween: 24,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1.3,
            },
            640: {
                slidesPerView: 2.5,
            },
            1024: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 4,
            },
        }
    });

    document.querySelectorAll('.branch-tab').forEach(tab => {
        tab.addEventListener('click', function() {

            document.querySelectorAll('.branch-tab').forEach(btn => {
                btn.classList.remove('bg-[#383e1a]', 'text-white');
                btn.classList.add('bg-[#e8e1cc]', 'text-[#4c4c4c]');
            });

            this.classList.remove('bg-[#e8e1cc]', 'text-[#4c4c4c]');
            this.classList.add('bg-[#383e1a]', 'text-white');

            const image = this.dataset.image;
            if (image) {
                document.getElementById('branchImage').src = image;
            }

            const lat = this.dataset.lat;
            const lng = this.dataset.lng;
            if (lat && lng) {
                document.getElementById('branchMap').src =
                    `https://www.google.com/maps?q=${lat},${lng}&output=embed`;
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const guestCountInput = document.getElementById("guest-count");
        const guestContainer = document.getElementById("guest-services");
        const guestTemplate = document.getElementById("guest-template");
        const serviceTemplate = document.getElementById("service-template");

        function generateUID() {
            return "g_" + Math.random().toString(36).substring(2, 10);
        }

        function renderGuests(count) {
            if (!guestContainer) return;

            guestContainer.innerHTML = "";

            for (let i = 0; i < count; i++) {
                const guestFragment = guestTemplate.content.cloneNode(true);

                guestFragment.querySelector(".guest-index").textContent = i + 1;
                guestFragment.querySelector(".guest-uid-input").value =
                    generateUID();

                guestFragment.querySelectorAll("[name]").forEach((el) => {
                    el.name = el.name.replace("__index__", i);
                });

                const guestEl = guestFragment.querySelector(".guest-item");
                const servicesWrapper = guestEl.querySelector(".services-wrapper");

                addService(servicesWrapper, i);

                guestEl
                    .querySelector(".add-service")
                    .addEventListener("click", () => {
                        addService(servicesWrapper, i);
                    });

                guestContainer.appendChild(guestFragment);
            }
        }

        function addService(wrapper, guestIndex) {
            const serviceIndex = wrapper.children.length;
            const serviceFragment = serviceTemplate.content.cloneNode(true);

            serviceFragment
                .querySelector(".service-select")
                .addEventListener("change", function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;

                    const priceInput =
                        this.closest(".service-item").querySelector(
                            ".service-price",
                        );

                    priceInput.value = price;
                });
            serviceFragment.querySelectorAll("[name]").forEach((el) => {
                el.name = el.name.replace(
                    "__SERVICE_NAME__",
                    `guests[${guestIndex}][services][${serviceIndex}]`,
                );
            });

            serviceFragment
                .querySelector(".remove-service")
                .addEventListener("click", function() {
                    const serviceItem =
                        this.closest(".service-item").querySelector(
                            ".service-select",
                        );
                    removeService(
                        serviceItem.value,
                        serviceItem.selectedOptions[0]?.dataset.price || 0,
                    );
                    this.closest(".service-item").remove();
                });

            function removeService(serviceId, servicePrice) {
                state.services = state.services.filter((s) => s.id !== serviceId);
                state.subtotal -= +servicePrice;

                renderSummary();
            }

            wrapper.appendChild(serviceFragment);
        }

        renderGuests(parseInt(guestCountInput?.value) || 1);

        if (!guestCountInput) return;

        guestCountInput.addEventListener("input", function() {
            const count = Math.max(1, parseInt(this.value, 10) || 1);
            renderGuests(count);
        });
    });

    document.addEventListener("click", function(e) {
        if (!e.target.closest(".remove-service")) return;

        const button = e.target.closest(".remove-service");
    });
</script>
<script>
    document.addEventListener("change", function(e) {
        if (!e.target.classList.contains("service-category")) return;

        const categorySelect = e.target;
        const serviceItem = categorySelect.closest(".service-item");
        const serviceSelect = serviceItem.querySelector(".service-select");

        const categoryId = categorySelect.value;

        serviceSelect.innerHTML = '<option value="">Đang tải...</option>';
        serviceSelect.disabled = true;

        if (!categoryId) {
            serviceSelect.innerHTML = '<option value="">Chọn dịch vụ</option>';
            return;
        }

        fetch(`/api/services?service_category_id=${categoryId}`)
            .then((res) => res.json())
            .then((services) => {
                let options = '<option value="">Chọn dịch vụ</option>';

                services.data.forEach((service) => {
                    const price = service.sale_price ?? service.price;

                    options += `
                            <option value="${service.id}" data-price="${price}" data-duration="${service.duration}"
                                >
                                ${service.title}
                                (${service.duration} phút)
                                – ${formatVnd(price)}
                            </option>
                        `;
                });

                serviceSelect.innerHTML = options;
                serviceSelect.disabled = false;
            });
    });
</script>
<script>
    document.addEventListener("change", function(e) {
        if (e.target.name !== "branch_id") return;

        const branchId = e.target.value;
        const container = document.getElementById("room-type-container");

        container.innerHTML = `
        <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
            Đang tải loại phòng...
        </div>
        `;

        fetch(`/api/branches/${branchId}/room-types`)
            .then((res) => res.json())
            .then((res) => {
                const roomTypes = res.data;

                if (!roomTypes.length) {
                    container.innerHTML = `
                    <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
                        Chi nhánh này chưa cấu hình loại phòng
                    </div>
                `;
                    return;
                }

                container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${roomTypes.map((rt) => renderRoomType(rt)).join("")}
                </div>
            `;
            })
            .catch(() => {
                container.innerHTML = `
                <div class="text-red-500 text-sm">
                    Không thể tải loại phòng. Vui lòng thử lại.
                </div>
            `;
            });
    });

    function renderRoomType(room) {
        const priceText =
            room.price > 0 ? `+${room.price.toLocaleString()}đ` : "0đ";

        return `
        <label
            class="flex items-center justify-between p-4 border rounded-lg cursor-pointer
            transition
            has-[:checked]:border-blue-600
            has-[:checked]:bg-blue-50">

            <div class="flex items-center gap-3">
                <input type="radio"
                    name="room_type_id"
                    data-name="${room.name}"
                    data-fee="${room.price}"
                    value="${room.id}"
                    class="w-5 h-5 text-blue-600">

                <div>
                    <p class="font-medium text-gray-800">${room.name}</p>

                </div>
            </div>

            <span class="font-medium text-slate-700">
                ${priceText}
            </span>
        </label>
    `;
    }
</script>
<script>
    const state = {
        branch: null,
        date: null,
        time: null,
        guests: 1,
        room: null,
        roomFee: 0,
        subtotal: 0,
        total: 0,
        discount: 0,
        promotionId: null,
        services: [],
        branchId: null,
        branchRoomTypeId: null,
        phone: null,
        apply_scope: null,
    };
    const qs = (s, p = document) => p.querySelector(s);
    const qsa = (s, p = document) => [...p.querySelectorAll(s)];
    const money = (v) => new Intl.NumberFormat("vi-VN").format(v) + "đ";

    function setText(selector, value) {
        const el = qs(selector);
        if (el) el.textContent = value;
    }



    function renderSummary() {
        setText("#summary-branch", state.branch || "-");
        setText("#summary-date", state.date || "-");
        setText("#summary-time", state.time || "-");
        setText("#summary-guests", state.guests + " khách");
        setText("#summary-room", state.room || "-");

        setText("#summary-subtotal", money(state.subtotal));
        setText(
            "#summary-room-fee",
            state.roomFee ? `+${money(state.roomFee)}` : "0đ",
        );

        setText(
            "#summary-discount",
            state.discount ? `-${money(state.discount)}` : "0đ",
        );

        let total = state.subtotal + state.roomFee;

        if (state.discount) {

            if (state.apply_scope === "booking") {
                const discount = Math.min(state.discount + state.roomFee, total);
                total = total - discount;
            }

            if (state.apply_scope === "room") {
                const discount = Math.min(state.discount, state.roomFee);
                total = state.subtotal + (state.roomFee - discount);
            }

            if (state.apply_scope === "service") {
                const discount = Math.min(state.discount, state.subtotal);
                total = (state.subtotal - discount) + state.roomFee;
            }
        }

        state.total = total;
        setText("#summary-total", money(state.total));
    }


    document.addEventListener("DOMContentLoaded", () => {
        qsa("input[name='branch_id']").forEach((radio) => {
            radio.addEventListener("change", () => {
                const label = radio
                    .closest(".branch-item")
                    .querySelector("span")
                    .innerText.trim();

                state.branch = label;
                state.branchId = radio.value;
                invalidatePromotion();
                renderSummary();
            });
        });

        qs("#booker_phone")?.addEventListener("input", (e) => {
            state.phone = e.target.value.trim();
            invalidatePromotion();

        });
        qs("#booking-date")?.addEventListener("change", (e) => {
            state.date = e.target.value;

            invalidatePromotion();
            renderSummary();
        });

        qs("#time-slots")?.addEventListener("click", (e) => {
            const slot = e.target.closest("[data-time]");
            if (!slot) return;

            qsa("[data-time]").forEach((el) => el.classList.remove("active"));
            slot.classList.add("active");

            state.time = slot.dataset.time;
            qs("#booking-time").value = state.time;

            invalidatePromotion();
            renderSummary();
        });

        qs("#guest-count")?.addEventListener("change", (e) => {
            state.guests = parseInt(e.target.value, 10) || 1;
            invalidatePromotion();
            renderSummary();
        });

        document.addEventListener("change", (e) => {
            if (!e.target.classList.contains("service-select")) return;

            let subtotal = 0;
            state.services = [];
            qsa(".service-select").forEach((select) => {
                const opt = select.selectedOptions[0];
                const servicePrice = opt.dataset.price;

                if (opt && opt.dataset.price) {
                    state.services.push({
                        id: opt.value,
                        price: +servicePrice,
                    });
                    const price = Number(opt?.dataset?.price || 0);
                    subtotal += price;
                }
            });

            if (state.subtotal !== subtotal) {
                state.subtotal = subtotal;

                invalidatePromotion();
                renderSummary();
            }
        });

        qs("#room-type-container")?.addEventListener("change", (e) => {
            if (!e.target.matches("input[name='room_type_id']")) return;

            state.room = e.target.dataset.name;
            state.roomFee = Number(e.target.dataset.fee || 0);
            state.branchRoomTypeId = e.target.value;
            invalidatePromotion();
            renderSummary();
        });

        qs("#apply-promo")?.addEventListener("click", () => {
            state.discount = 0;
            renderSummary();
        });

        renderSummary();
    });
</script>



@if (session('success_booking'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Đặt lịch thành công 🎉',
                html: `
                <div style="font-size:15px; line-height:1.6;">
                    <p>
                        Chúng tôi đã ghi nhận lịch hẹn của bạn.
                    </p>
                    <p>
                        📞 Nhân viên của chúng tôi sẽ gọi đến 
                        <strong>số điện thoại bạn đã cung cấp</strong> 
                        để xác nhận lịch hẹn trong thời gian sớm nhất.
                    </p>
                    <p style="color:#666;">
                        Vui lòng giữ máy để chúng tôi có thể liên hệ xác nhận.
                        Xin cảm ơn!
                    </p>
                </div>
            `,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#d4a373'
            });
        });
    </script>
@endif



<script>
    flatpickr("#booking-date", {
        dateFormat: "d/m/Y",
        minDate: "today",
        locale: "vn",
        disableMobile: true,
        todayBtn: true,
        allowInput: false,
    });
</script>

<script>
    function showPromoMessage(text, success = true) {
        const el = document.getElementById('promo-message');
        const promoCodeEL = document.getElementById('promo-code');

        if (!el) return;

        el.classList.remove('hidden');

        el.classList.remove('text-green-600', 'text-red-500');

        if (success) {
            el.classList.add('text-green-600');
        } else {
            el.classList.add('text-red-500');
        }

        if (!success && promoCodeEL) {
            promoCodeEL.value = "";
        }

        el.textContent = text;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const applyPromoBtn = document.getElementById('apply-promo');
        const codeInput = document.getElementById('promo-code');
        const messageEl = document.getElementById('promo-message');
        let debounceTimer = null;
        let isApplyingPromo = false;
        if (applyPromoBtn) {
            applyPromoBtn.addEventListener('click', function() {
                clearTimeout(debounceTimer);
                applyPromotion();
            });
        }

        if (codeInput) {
            codeInput.addEventListener('input', function() {

                clearTimeout(debounceTimer);

                const code = this.value.trim();

                if (!code) {
                    resetPromotion(false);
                    return;
                }

                debounceTimer = setTimeout(() => {
                    applyPromotion();
                }, 2000);
            });
        }


        async function applyPromotion() {
            clearTimeout(debounceTimer);

            const code = codeInput.value.trim();
            if (!code) {
                showPromoMessage('Vui lòng nhập mã giảm giá', false);
                return;
            }
            if (isApplyingPromo) return;
            isApplyingPromo = true;


            try {
                const res = await fetch('/api/promotions/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        services: state.services || [],
                        membership_id: {{ optional(auth()->guard('customer')->user())->membership_id ?? 'null' }},
                        customer_id: {{ auth()->guard('customer')->check() ? auth()->guard('customer')->user()->id : 'null' }},
                        discount_code: code,
                        subtotal: state.subtotal,
                        room_fee: state.roomFee,
                        branch_id: state.branchId,
                        room_type_id: state.branchRoomTypeId,
                        booking_date: state.date || document.getElementById('booking-date')?.value || null,
                        total_guests: state.guests,
                        phone: state.phone || document.getElementById('booker_phone')
                            ?.value || null,
                    })
                });


                const data = await res.json();
                console.log(data);
                if (!res.ok) throw data;

                state.discount = data.discount;
                state.apply_scope = data.promotion.apply_scope;
                // state.promotionId = data.promotion_id;
                // state.total = data.total_after_discount;

                renderSummary();

                showPromoMessage(data.message || 'Áp dụng mã giảm giá thành công', true);

            } catch (err) {
                resetPromotion();
                console.log('Error nha: ', err?.message);
                showPromoMessage(
                    err?.message || 'Mã giảm giá không hợp lệ',
                    false
                );
            } finally {
                isApplyingPromo = false;
            }
        }



        function resetPromotion() {
            state.discount = 0;
            state.promotionId = null;
            state.total = state.subtotal + state.roomFee;

            renderSummary();

            messageEl.classList.add('hidden');

        }





    });

    function invalidatePromotion() {
        if (!state.discount) return;
        state.discount = 0;
        state.promotionId = null;
        state.total = state.subtotal + state.roomFee;

        renderSummary();
        showPromoMessage('Mã giảm giá đã bị hủy do thay đổi thông tin đặt lịch', false);
    }

    const bookingForm = document.getElementById('booking');

    if (bookingForm) {
        bookingForm.addEventListener('change', function(e) {
            if (e.target.closest('#promo-code')) return;

            if (e.target.closest('#apply-promo')) return;

            invalidatePromotion();
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const container = document.getElementById('time-slots');
        const input = document.getElementById('booking-time');
        const dateInput = document.getElementById('booking-date');
        const branchInputs = document.querySelectorAll('input[name="branch_id"]');
        const roomTypeInputs = document.querySelectorAll('input[name="room_type_id"]');

        const step = 15;

        function pad(n) {
            return n.toString().padStart(2, '0');
        }

        function timeToMinutes(time) {
            const [h, m] = time.split(':').map(Number);
            return h * 60 + m;
        }

        function isToday(selectedDate) {
            const today = new Date();
            const [d, m, y] = selectedDate.split('/');
            const selected = new Date(y, m - 1, d);

            return (
                selected.getFullYear() === today.getFullYear() &&
                selected.getMonth() === today.getMonth() &&
                selected.getDate() === today.getDate()
            );
        }

        function getCurrentMinutes() {
            const now = new Date();
            return now.getHours() * 60 + now.getMinutes();
        }

        function render(openTime, closeTime, disabledTimes = []) {

            container.innerHTML = '';

            const startMinutes = timeToMinutes(openTime);
            const endMinutes = timeToMinutes(closeTime);

            const isTodaySelected = isToday(dateInput.value);
            const currentMinutes = getCurrentMinutes();

            for (let minutes = startMinutes; minutes <= endMinutes; minutes += step) {

                const h = Math.floor(minutes / 60);
                const m = minutes % 60;
                const time = `${pad(h)}:${pad(m)}`;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = time;
                btn.dataset.time = time;

                let disabled = disabledTimes.includes(time);

                if (isTodaySelected && minutes <= currentMinutes) {
                    disabled = true;
                }

                btn.className =
                    'border rounded-lg py-2 text-sm font-medium text-center transition ' +
                    (disabled ?
                        'bg-slate-100 text-slate-300 cursor-not-allowed' :
                        'text-slate-600 hover:border-blue-500 hover:text-blue-600');

                btn.disabled = disabled;

                if (!disabled) {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('[data-time]').forEach(b => {
                            b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        });

                        btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                        input.value = time;
                    });
                }

                container.appendChild(btn);
            }
        }

        function fetchAvailableTimes() {

            const branch = document.querySelector('input[name="branch_id"]:checked');
            const roomType = document.querySelector('input[name="room_type_id"]:checked')
            const date = dateInput.value;

            if (!branch || !date || !roomType) return;

            fetch(`/api/branches/available-slots?date=${date}&room_type_id=${roomType.value}&branch_id=${branch.value}`)
                .then(res => res.json())
                .then(data => {
                    render(data.open_time, data.close_time, data.disabled_times);
                });
        }

        document.addEventListener('change', function(e) {

            if (e.target.name === 'room_type_id') {
                fetchAvailableTimes();
            }

        });


        if (dateInput) {

            dateInput.addEventListener('change', function() {

                const roomType = document.querySelector('input[name="room_type_id"]:checked');

                if (roomType) {
                    fetchAvailableTimes();
                }

            });

        }

    });
</script>




@yield('script')

</html>
