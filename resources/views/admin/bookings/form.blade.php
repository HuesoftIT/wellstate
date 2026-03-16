<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table class="table table-condensed">

        {{-- Họ tên --}}
        <tr>
            <td width="30%">
                <label class="control-label label-required">Họ tên</label>
            </td>
            <td>
                <input type="text" name="booker_name" class="form-control input-sm" value="{{ old('booker_name') }}"
                    required>
            </td>
        </tr>

        {{-- Số điện thoại --}}
        <tr>
            <td>
                <label class="control-label label-required">Số điện thoại</label>
            </td>
            <td>
                <input id="booker_phone" type="text" name="booker_phone" class="form-control input-sm"
                    value="{{ old('booker_phone') }}" required>
            </td>
        </tr>

        {{-- Email --}}
        <tr>
            <td>
                <label class="control-label">Email (không bắt buộc)</label>
            </td>
            <td>
                <input type="email" name="booker_email" class="form-control input-sm"
                    value="{{ old('booker_email') }}">
            </td>
        </tr>

        {{-- Số khách --}}
        <tr>
            <td>
                <label class="control-label label-required">Số khách</label>
            </td>
            <td>
                <input type="number" id="guest_count" name="guest_count" min="1"
                    value="{{ old('guest_count', 1) }}" class="form-control input-sm">
            </td>
        </tr>

        {{-- Chi nhánh --}}
        <tr>
            <td>
                <label class="control-label label-required">Chi nhánh</label>
            </td>
            <td>
                <select name="branch_id" id="branch_id" class="form-control input-sm" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    @foreach ($branches as $id => $name)
                        <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>

        {{-- Ngày --}}
        <tr>
            <td>
                <label class="control-label label-required">Ngày</label>
            </td>
            <td>
                <input id="booking_date" type="date" name="booking_date" min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('booking_date') }}" class="form-control input-sm" required>
            </td>
        </tr>

        {{-- Giờ --}}
        <tr>
            <td>
                <label class="control-label label-required">Giờ</label>
            </td>
            <td>
                <div id="time-slots" class="grid grid-cols-4 sm:grid-cols-6 gap-3"></div>

                <input type="hidden" name="booking_time" id="booking_time" value="{{ old('booking_time') }}">
            </td>
        </tr>

        {{-- Loại phòng --}}
        <tr>
            <td class="align-middle">
                <label class="control-label label-required">Loại phòng</label>
            </td>
            <td>
                <select name="room_type_id" id="room_type_id" class="form-control input-sm" required>

                    <option value="">-- Vui lòng chọn chi nhánh --</option>

                    {{-- Nếu bạn có truyền $roomTypes khi validation fail --}}
                    @isset($roomTypes)
                        @foreach ($roomTypes as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}"
                                {{ old('room_type_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} - {{ number_format($room->price) }}đ
                            </option>
                        @endforeach
                    @endisset

                </select>
            </td>
        </tr>

        {{-- Dịch vụ theo khách --}}
        <tr id="guest-services-row">
            <td class="align-top">
                <label class="control-label">Dịch vụ theo khách</label>
            </td>
            <td>
                <div id="guests-container">
                    {{-- JS sẽ render lại dựa theo old guest_count --}}
                </div>

                <div class="text-left">
                    <button type="button" id="add-guest" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Thêm khách
                    </button>
                </div>
            </td>
        </tr>

        {{-- Khuyến mãi --}}
        <tr id="promotion-row">
            <td class="align-middle">
                <label class="control-label">Khuyến mãi</label>
            </td>
            <td>
                <select name="promotion_id" id="promotion_id" class="form-control input-sm">
                    <option value="">-- Không áp dụng --</option>

                    {{-- Nếu có truyền lại promotions --}}
                    @isset($availablePromotions)
                        @foreach ($availablePromotions as $promo)
                            <option value="{{ $promo->id }}" {{ old('promotion_id') == $promo->id ? 'selected' : '' }}>
                                {{ $promo->title }}
                            </option>
                        @endforeach
                    @endisset
                </select>

                <div id="promotion-info" class="mt-2 text-success" style="display:none;">
                    <small>
                        Giảm:
                        <strong id="discount-amount">0</strong> VND <br>
                        Tổng sau giảm:
                        <strong id="final-total">0</strong> VND
                    </small>
                </div>
            </td>
        </tr>

    </table>
    <template id="guest-template">
        <div class="guest-item panel panel-default p-3 mb-3">

            <h4>Khách <span class="guest-index"></span></h4>

            <div class="form-group">
                <label>Tên khách</label>
                <input type="text" class="form-control guest-name">
            </div>

            <div class="services-container"></div>

            <button type="button" class="btn btn-primary btn-sm add-service">
                <i class="fa fa-plus"></i> Thêm dịch vụ
            </button>

            <button type="button" class="btn btn-danger btn-sm remove-guest">
                <i class="fa fa-trash"></i> Xóa khách
            </button>

        </div>
    </template>
    <template id="service-template">
        <div class="service-item row mb-2">

            <div class="col-sm-4">
                <select class="form-control service-category">
                    <option value="">Chọn loại dịch vụ</option>
                    @foreach ($serviceCategories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-6">
                <select class="form-control service-select">
                    <option value="">Chọn dịch vụ</option>
                </select>
            </div>

            <div class="col-sm-2 text-right">
                <button type="button" class="btn btn-danger btn-sm remove-service">
                    <i class="fa fa-times"></i>
                </button>
            </div>

        </div>
    </template>
    <div id="booking-summary" class="panel panel-info mt-4">
        <div class="panel-heading mb-4">
            <strong>TÓM TẮT ĐẶT LỊCH</strong>
        </div>
        <div class="panel-body">
            <div id="summary-content">
                Chưa có thông tin
            </div>
            <hr>
            <h4 class="text-right">
                Tổng tiền: <span id="summary-total">0đ</span>
            </h4>
        </div>
    </div>
</div>



<div class="box-footer">
    {!! Form::button(
        '<i class="fa fa-check-circle"></i> ' . ($text = isset($submitButtonText) ? $submitButtonText : __('message.save')),
        [
            'class' => 'btn btn-info mr-2',
            'type' => 'submit',
        ],
    ) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/services') }}" class="btn btn-default"><i
            class="fas fa-times"></i>
        {{ __('message.close') }}</a>
</div>

@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script>
        CKFinder.config({
            connectorPath: '/ckfinder/connector'
        });
    </script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
    </script>
    @include('ckfinder::setup')
    <script type="text/javascript">
        $(function() {
            $('#image').change(function() {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                    reader.addEventListener("load", function() {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display', 'block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                } else {
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function() {
                var preview = document.querySelector('img.img-preview');

                if (confirm('{{ __('message.confirm_delete') }}')) {
                    preview.src = '';
                    $('.imgprev-wrap').css('display', 'none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>
    <script>
        flatpickr("#booking_date", {
            dateFormat: "d/m/Y",
            minDate: "today",
            locale: "vn",
            disableMobile: true,
            todayBtn: true,
            allowInput: false
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* =====================================================
                STATE
            ===================================================== */

            let bookingState = {
                roomPrice: 0,
                servicesTotal: 0,
                subtotal: 0,
                discount: 0,
                finalTotal: 0,
                discountScope: null,

            };

            let servicesCache = {};

            const bookerPhone = document.getElementById('booker_phone');
            const branchSelect = document.getElementById('branch_id');
            const roomTypeSelect = document.getElementById('room_type_id');
            const container = document.getElementById('time-slots');
            const dateInput = document.getElementById('booking_date');
            const timeInput = document.getElementById('booking_time');
            const guestsContainer = document.getElementById('guests-container');
            const guestTemplate = document.getElementById('guest-template');
            const serviceTemplate = document.getElementById('service-template');
            const addGuestBtn = document.getElementById('add-guest');
            const guestCountInput = document.getElementById('guest_count');

            const summaryContent = document.getElementById('summary-content');
            const summaryTotal = document.getElementById('summary-total');

            const promotionSelect = document.getElementById('promotion_id');
            const promotionInfo = document.getElementById('promotion-info');
            const discountAmountEl = document.getElementById('discount-amount');
            const finalTotalEl = document.getElementById('final-total');

            bookerPhone.addEventListener('blur', function() {
                loadPromotions();
            });
            dateInput.addEventListener('change', function() {
                loadPromotions();
            })
            branchSelect.addEventListener('change', loadPromotions);

            const step = 15;

            /* =====================================================
                UTIL
            ===================================================== */

            function formatMoney(price) {
                return Number(price || 0).toLocaleString('vi-VN') + 'đ';
            }

            function pad(n) {
                return n.toString().padStart(2, '0');
            }

            function timeToMinutes(time) {
                const [h, m] = time.split(':').map(Number);
                return h * 60 + m;
            }

            function isToday(selectedDate) {
                if (!selectedDate) return false;

                const today = new Date();

                const [day, month, year] = selectedDate.split('/');

                const selected = new Date(year, month - 1, day);

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
            document.querySelectorAll(
                'input[name="booker_name"], input[name="booker_phone"], input[name="booker_email"]'
            ).forEach(input => {
                input.addEventListener('input', renderSummaryDetails);
            });

            function renderSummaryDetails() {

                const bookerName = document.querySelector('input[name="booker_name"]').value || '-';
                const bookerPhone = document.querySelector('input[name="booker_phone"]').value || '-';
                const bookerEmail = document.querySelector('input[name="booker_email"]').value || '-';

                const branchText =
                    branchSelect.options[branchSelect.selectedIndex]?.text || '-';

                const roomText =
                    roomTypeSelect.options[roomTypeSelect.selectedIndex]?.text || '-';

                const date = dateInput.value || '-';
                const time = timeInput.value || '-';

                const guests = guestsContainer.querySelectorAll('.guest-item');

                let html = `
                    <p><strong>Tên:</strong> ${bookerName}</p>
                    <p><strong>SĐT:</strong> ${bookerPhone}</p>
                    <p><strong>Email:</strong> ${bookerEmail}</p>
                    <p><strong>Chi nhánh:</strong> ${branchText}</p>
                    <p><strong>Ngày:</strong> ${date}</p>
                    <p><strong>Giờ:</strong> ${time}</p>
                    <p><strong>Loại phòng:</strong> ${roomText}</p>
                    <p><strong>Số khách:</strong> ${guests.length}</p>
                    <hr>
                `;

                guests.forEach((guest, index) => {

                    const guestName =
                        guest.querySelector('.guest-name')?.value || `Khách ${index + 1}`;

                    html += `<p><strong>${guestName}</strong></p><ul>`;

                    guest.querySelectorAll('.service-select').forEach(select => {

                        const id = select.value;
                        if (!id || !servicesCache[id]) return;

                        const service = servicesCache[id];
                        const price = service.sale_price || service.price || 0;

                        html += `
                <li>
                    ${service.title}
                    - ${formatMoney(price)}
                </li>
            `;
                    });

                    html += `</ul>`;
                });

                summaryContent.innerHTML = html;
            }
            /* =====================================================
                CORE CALCULATION
            ===================================================== */

            function recalculateTotals() {

                // ROOM
                bookingState.roomPrice = parseInt(
                    roomTypeSelect.options[roomTypeSelect.selectedIndex]?.dataset.price || 0
                );

                // SERVICES
                let servicesTotal = 0;
                guestsContainer.querySelectorAll('.service-select').forEach(select => {
                    const id = select.value;
                    if (!id || !servicesCache[id]) return;

                    const service = servicesCache[id];
                    servicesTotal += parseInt(service.sale_price || service.price || 0);
                });

                bookingState.servicesTotal = servicesTotal;

                // SUBTOTAL
                bookingState.subtotal =
                    bookingState.roomPrice +
                    bookingState.servicesTotal;

                // FINAL
                bookingState.finalTotal =
                    bookingState.subtotal -
                    bookingState.discount;

                if (bookingState.finalTotal < 0) {
                    bookingState.finalTotal = 0;
                }

                updateSummaryUI();
                renderSummaryDetails();
            }

            function updateSummaryUI() {

                summaryTotal.innerText = formatMoney(bookingState.finalTotal);
                discountAmountEl.innerText = formatMoney(bookingState.discount);
                finalTotalEl.innerText = formatMoney(bookingState.finalTotal);

                promotionInfo.style.display =
                    bookingState.discount > 0 ? 'block' : 'none';
            }

            /* =====================================================
                TIME SLOT RENDER
            ===================================================== */

            function render(openTime, closeTime, disabledTimes = []) {

                container.innerHTML = '';

                const startMinutes = timeToMinutes(openTime);
                const endMinutes = timeToMinutes(closeTime);

                const todaySelected = isToday(dateInput.value);
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

                    if (todaySelected && minutes <= currentMinutes) {
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
                                b.classList.remove('bg-primary', 'text-white', 'border-blue-600');
                            });

                            btn.classList.add('bg-primary', 'text-white', 'border-blue-600');
                            timeInput.value = time;

                            loadPromotions();
                        });
                    }

                    container.appendChild(btn);
                }
            }

            function fetchAvailableTimes() {

                const branch = branchSelect.value;
                const date = dateInput.value;

                if (!branch || !date) {
                    container.innerHTML = '';
                    return;
                }

                fetch(`/api/ajax/branch-available-times?branch_id=${branch}&date=${date}`)
                    .then(res => res.json())
                    .then(data => {
                        render(data.open_time, data.close_time, data.disabled_times);
                    });
            }

            branchSelect.addEventListener('change', fetchAvailableTimes);
            dateInput.addEventListener('change', fetchAvailableTimes);

            /* =====================================================
                ROOM TYPE LOAD
            ===================================================== */

            branchSelect.addEventListener('change', function() {

                const branchId = this.value;
                roomTypeSelect.innerHTML = '<option value="">Đang tải...</option>';

                if (!branchId) {
                    roomTypeSelect.innerHTML = '<option value="">-- Chọn loại phòng --</option>';
                    return;
                }

                fetch(`/api/ajax/branches/${branchId}/room-types`)
                    .then(res => res.json())
                    .then(data => {

                        roomTypeSelect.innerHTML = '<option value="">-- Chọn loại phòng --</option>';

                        (data.data || []).forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent =
                                `${room.name} - ${room.price.toLocaleString()}đ`;
                            option.dataset.price = room.price;
                            roomTypeSelect.appendChild(option);
                        });
                    });

                bookingState.discount = 0;
                recalculateTotals();
                loadPromotions();
            });

            roomTypeSelect.addEventListener('change', function() {
                bookingState.discount = 0;
                recalculateTotals();
                loadPromotions();
            });

            /* =====================================================
                SERVICE LOAD
            ===================================================== */

            guestsContainer.addEventListener('change', function(e) {

                if (e.target.classList.contains('service-category')) {

                    const categoryId = e.target.value;
                    const serviceSelect = e.target
                        .closest('.service-item')
                        .querySelector('.service-select');

                    serviceSelect.innerHTML = '<option>Đang tải...</option>';

                    fetch(`/api/services?service_category_id=${categoryId}`)
                        .then(res => res.json())
                        .then(res => {

                            let options = '<option value="">Chọn dịch vụ</option>';

                            (res.data || []).forEach(service => {
                                servicesCache[service.id] = service;
                                options += `
                            <option value="${service.id}">
                                ${service.title}
                                (${service.duration} phút) - 
                                ${formatMoney(service.sale_price || service.price || 0)}
                            </option>
                        `;
                            });

                            serviceSelect.innerHTML = options;
                        });
                }

                if (e.target.classList.contains('service-select')) {
                    bookingState.discount = 0;
                    recalculateTotals();
                    loadPromotions();
                }
            });

            /* =====================================================
                GUEST RENDER & INDEX
            ===================================================== */

            function updateIndexes() {
                const guests = guestsContainer.querySelectorAll('.guest-item');

                guests.forEach((guest, gIndex) => {

                    guest.querySelector('.guest-index').innerText = gIndex + 1;

                    const nameInput = guest.querySelector('.guest-name');
                    nameInput.name = `guests[${gIndex}][name]`;

                    guest.querySelectorAll('.service-item').forEach((service, sIndex) => {

                        service.querySelector('.service-category')
                            .name = `guests[${gIndex}][services][${sIndex}][service_category_id]`;

                        service.querySelector('.service-select')
                            .name = `guests[${gIndex}][services][${sIndex}][service_id]`;
                    });
                });

                guestCountInput.value = guests.length || 1;
            }

            function addGuest() {
                const clone = guestTemplate.content.cloneNode(true);
                guestsContainer.appendChild(clone);
                updateIndexes();
            }

            function removeLastGuest() {
                const guests = guestsContainer.querySelectorAll('.guest-item');
                if (guests.length > 1) {
                    guests[guests.length - 1].remove();
                    updateIndexes();
                }
            }

            function syncGuestToCount(count) {
                const current = guestsContainer.querySelectorAll('.guest-item').length;

                if (count > current) {
                    for (let i = current; i < count; i++) {
                        addGuest();
                    }
                }

                if (count < current) {
                    for (let i = current; i > count; i--) {
                        removeLastGuest();
                    }
                }
            }

            guestCountInput.addEventListener('input', function() {
                let count = parseInt(this.value) || 1;
                if (count < 1) count = 1;
                syncGuestToCount(count);
            });

            addGuestBtn.addEventListener('click', addGuest);

            guestsContainer.addEventListener('click', function(e) {

                if (e.target.closest('.remove-guest')) {
                    const guests = guestsContainer.querySelectorAll('.guest-item');
                    if (guests.length > 1) {
                        e.target.closest('.guest-item').remove();
                        updateIndexes();
                        recalculateTotals();
                        loadPromotions();
                    }
                }

                if (e.target.closest('.add-service')) {
                    const guestItem = e.target.closest('.guest-item');
                    const clone = serviceTemplate.content.cloneNode(true);
                    guestItem.querySelector('.services-container').appendChild(clone);
                    updateIndexes();
                }

                if (e.target.closest('.remove-service')) {
                    e.target.closest('.service-item').remove();
                    updateIndexes();
                    recalculateTotals();
                    loadPromotions();
                }
            });

            syncGuestToCount(parseInt(guestCountInput.value) || 1);
            /* =====================================================
                PROMOTION
            ===================================================== */

            promotionSelect.addEventListener('change', function() {

                if (!this.value) {

                    bookingState.discount = 0;
                    bookingState.discountScope = null;

                } else {

                    const option = this.options[this.selectedIndex];

                    bookingState.discount = parseInt(option.dataset.discount || 0);
                    bookingState.discountScope = option.dataset.scope;

                    console.log('bookingState: ', bookingState.discount);
                }

                recalculateTotals();
            });

            function getBookingData() {

                let services = [];

                guestsContainer.querySelectorAll('.service-select').forEach(select => {
                    const id = select.value;
                    if (!id || !servicesCache[id]) return;

                    const service = servicesCache[id];
                    services.push({
                        id: parseInt(id),
                        price: parseInt(service.sale_price || service.price || 0)
                    });
                });

                return {
                    booker_phone: bookerPhone.value || null,
                    branch_id: branchSelect.value || null,
                    booking_date: dateInput.value || null,
                    booking_time: timeInput.value || null,
                    room_type_id: roomTypeSelect.value || null,
                    services: services,
                    subtotal: bookingState.subtotal
                };
            }

            function loadPromotions() {

                fetch('/api/promotions/available', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(getBookingData())
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('data: ', data);
                        promotionSelect.innerHTML =
                            '<option value="">-- Không áp dụng --</option>';

                        data.forEach(promo => {
                            promotionSelect.innerHTML += `
                    <option value="${promo.id}"
                        data-discount="${promo.discount_amount}"
                        data-scope="${promo.apply_scope}">
                        ${promo.title} - 
                        Giảm ${promo.discount_amount.toLocaleString()}đ
                    </option>
                `;
                        });
                    });
            }

            /* =====================================================
                INIT
            ===================================================== */

            recalculateTotals();

        });
    </script>
@endsection
