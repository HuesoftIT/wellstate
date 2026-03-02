<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table class="table table-condensed">

        {{-- Thông tin khách --}}
        <tr>
            <td width="30%">
                <label class="control-label label-required">Họ tên</label>
            </td>
            <td>
                <input type="text" name="customer_name" class="form-control input-sm" required>
            </td>
        </tr>

        <tr>
            <td>
                <label class="control-label label-required">Số điện thoại</label>
            </td>
            <td>
                <input type="text" name="customer_phone" class="form-control input-sm" required>
            </td>
        </tr>

        <tr>
            <td>
                <label class="control-label label-required">Số khách</label>
            </td>
            <td>
                <input type="number" id="guest_count" name="guest_count" min="1" value="1"
                    class="form-control input-sm">
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
                        <option value="{{ $id }}">{{ $name }}</option>
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
                    class="form-control input-sm" required>
            </td>
        </tr>

        {{-- Giờ --}}
        <tr>
            <td>
                <label class="control-label label-required">Giờ</label>
            </td>
            <td>
                <div id="time-slots" class="grid grid-cols-4 sm:grid-cols-6 gap-3"></div>

                <input type="hidden" name="booking_time" id="booking_time">
            </td>
        </tr>

        {{-- Loại phòng --}}
        <tr>
            <td class="align-middle" width="25%">
                <label class="control-label label-required">Loại phòng</label>
            </td>
            <td>
                <select name="room_type_id" id="room_type_id" class="form-control input-sm" required>
                    <option value="">-- Vui lòng chọn chi nhánh --</option>
                </select>
            </td>
        </tr>

        <tr id="guest-services-row">
            <td class="align-top">
                <label class="control-label">Dịch vụ theo khách</label>
            </td>
            <td>
                <div id="guests-container"></div>
                {{-- <div class="guest-services-container">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Tên khách
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="guest_names[]" class="form-control input-sm"
                                placeholder="Tên khách (tùy chọn)">
                        </div>
                    </div>

                    <div class="guest-service-item panel panel-default mb-3">

                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">
                                Loại dịch vụ
                            </label>
                            <div class="col-sm-4">
                                <select name="guests[0][services][0][service_category_id]"
                                    class="form-control input-sm">
                                    <option value="">-- Chọn loại dịch vụ --</option>
                                    @foreach ($serviceCategories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-sm-1 col-form-label">
                                Dịch vụ
                            </label>
                            <div class="col-sm-5">
                                <select name="guest_services[]" class="form-control input-sm">
                                    <option value="">-- Chọn dịch vụ --</option>
                                </select>
                            </div>

                            <div class="col-sm-1 text-right">
                                <button type="button" class="btn btn-danger btn-sm remove-service">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                    </div>



                </div> --}}
                {{-- Add Button --}}
                <div class="text-left">
                    <button type="button" id="add-guest" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Thêm khách
                    </button>
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
                <select class="form-control service-select" disabled>
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

            const branchSelect = document.getElementById('branch_id');
            const roomTypeSelect = document.getElementById('room_type_id');

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
                        data = data.data || [];
                        data.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent =
                                `${room.name} - ${room.price.toLocaleString()}đ`;
                            roomTypeSelect.appendChild(option);
                        });

                    })
                    .catch(() => {
                        roomTypeSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                    });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('time-slots');
            const input = document.getElementById('booking_time');
            const dateInput = document.getElementById('booking_date');
            const branchSelect = document.getElementById('branch_id');

            const step = 15;

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
                    })
                    .catch(err => {
                        console.error(err);
                    });
            }

            branchSelect.addEventListener('change', fetchAvailableTimes);
            dateInput.addEventListener('change', fetchAvailableTimes);

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const guestsContainer = document.getElementById('guests-container');
            const guestTemplate = document.getElementById('guest-template');
            const serviceTemplate = document.getElementById('service-template');
            const addGuestBtn = document.getElementById('add-guest');
            const guestCountInput = document.getElementById('guest_count');

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

            addGuestBtn.addEventListener('click', function() {
                addGuest();
            });

            guestsContainer.addEventListener('click', function(e) {

                if (e.target.closest('.remove-guest')) {
                    const guests = guestsContainer.querySelectorAll('.guest-item');

                    if (guests.length > 1) {
                        e.target.closest('.guest-item').remove();
                        updateIndexes();
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
                }

            });

            syncGuestToCount(parseInt(guestCountInput.value) || 1);

        });
    </script>
@endsection
