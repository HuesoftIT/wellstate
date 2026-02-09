<div class="box-body">

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-times"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <input type="hidden" name="back_url" value="{{ $backUrl ?? old('back_url') }}">

    <table class="table table-condensed">

        {{-- BRANCH --}}
        <tr class="row {{ $errors->has('branch_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('branch_id', __('Chi nhánh'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select(
                    'branch_id',
                    $branches->pluck('name', 'id'),
                    old('branch_id', $employeeWorkingShift->branch_id ?? null),
                    [
                        'class' => 'form-control input-sm',
                        'required',
                        'id' => 'branch_id',
                        'placeholder' => '-- Chọn chi nhánh --',
                    ],
                ) !!}
                {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- WORK DATE --}}
        {{-- <tr class="row {{ $errors->has('work_date') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('work_date', __('Ngày làm việc'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::date('work_date', old('work_date', $employeeWorkingShift->work_date ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}

                {!! $errors->first('work_date', '<p class="help-block">:message</p>') !!}
            </td>
        </tr> --}}

        {{-- APPLY TYPE --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label label-required">Áp dụng</label>
            </td>
            <td class="col-md-8 col-lg-9">

                <label class="radio-inline">
                    <input type="radio" name="apply_type" value="single" checked>
                    1 ngày
                </label>

                <label class="radio-inline">
                    <input type="radio" name="apply_type" value="7_days">
                    7 ngày
                </label>

                <label class="radio-inline">
                    <input type="radio" name="apply_type" value="month">
                    1 tháng
                </label>

                <label class="radio-inline">
                    <input type="radio" name="apply_type" value="range">
                    Khoảng thời gian
                </label>

                <div class="row" style="margin-top:10px;">
                    <div class="col-md-6">
                        <label>Từ ngày</label>
                        <input type="date" name="from_date" class="form-control input-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label>Đến ngày</label>
                        <input type="date" name="to_date" class="form-control input-sm" required>
                    </div>
                </div>

            </td>
        </tr>



        {{-- WORKING SHIFT --}}
        <tr class="row {{ $errors->has('working_shift_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('working_shift_id', __('Ca làm việc'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div id="working-shifts" style="display:flex; gap:15px; flex-wrap:wrap;">
                    <span class="text-muted">Vui lòng chọn chi nhánh</span>
                </div>
                {!! $errors->first('working_shift_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- EMPLOYEES --}}
        <tr class="row {{ $errors->has('employee_ids') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                <label class="control-label label-required">
                    Nhân viên
                </label>
            </td>
            <td class="col-md-8 col-lg-9">

                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="check-all">
                        <strong>Chọn tất cả nhân viên trong chi nhánh</strong>
                    </label>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th>Mã NV</th>
                            <th>Tên nhân viên</th>
                        </tr>
                    </thead>
                    <tbody id="employee-table">
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Vui lòng chọn chi nhánh
                            </td>
                        </tr>
                    </tbody>
                </table>

                {!! $errors->first('employee_ids', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

    </table>
</div>






<div class="box-footer">
    {!! Form::button(
        '<i class="fa fa-check-circle"></i> ' . ($text = isset($submitButtonText) ? $submitButtonText : __('message.save')),
        [
            'class' => 'btn btn-info mr-2',
            'type' => 'submit',
        ],
    ) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/employee-working-shifts') }}" class="btn btn-default"><i
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
            $('#avatar').change(function() {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#avatar').files[0];
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
                    document.querySelector('#avatar').value = '';
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
        const FORM_DATA = {
            branch_id: "{{ old('branch_id', $employeeWorkingShift->workingShift->branch_id ?? '') }}",
            working_shift_id: "{{ old('working_shift_id', $employeeWorkingShift->working_shift_id ?? '') }}",
            employee_ids: @json(old('employee_ids', isset($employeeWorkingShift) ? [$employeeWorkingShift->employee_id] : []))
        };
        const STATE = {
            checkAll: false,
        };
    </script>

    <script>
        function loadBranchData(branchId, selectedEmployeeIds = [], selectedWorkingShiftId = null) {
            if (!branchId) return;

            const employeeTable = document.getElementById('employee-table');
            const workingShiftContainer = document.getElementById('working-shifts');
            const checkAll = document.getElementById('check-all');

            checkAll.disabled = true;
            employeeTable.innerHTML = `<tr><td colspan="3" class="text-muted text-center">Đang tải...</td></tr>`;
            workingShiftContainer.innerHTML = `<span class="text-muted">Đang tải...</span>`;

            fetch(`/ajax/branch/${branchId}/employees`)
                .then(res => res.json())
                .then(data => {
                    employeeTable.innerHTML = '';

                    if (!data.length) {
                        employeeTable.innerHTML =
                            `<tr><td colspan="3" class="text-center text-muted">Không có nhân viên</td></tr>`;
                        return;
                    }

                    data.forEach(emp => {
                        const checked =
                            STATE.checkAll ||
                            selectedEmployeeIds.includes(emp.id);

                        employeeTable.innerHTML += `
                    <tr>
                        <td>
                            <input type="checkbox"
                                   name="employee_ids[]"
                                   value="${emp.id}"
                                   ${checked ? 'checked' : ''}>
                        </td>
                        <td>${emp.code ?? ''}</td>
                        <td>${emp.name}</td>
                    </tr>
                `;
                    });

                    checkAll.disabled = false;
                    syncCheckAll();
                });

            fetch(`/ajax/branch/${branchId}/working-shifts`)
                .then(res => res.json())
                .then(data => {
                    workingShiftContainer.innerHTML = '';

                    if (!data.length) {
                        workingShiftContainer.innerHTML =
                            `<span class="text-muted">Chưa có ca làm việc</span>`;
                        return;
                    }

                    data.forEach(shift => {
                        const checked = shift.id == selectedWorkingShiftId;
                        workingShiftContainer.innerHTML += `
                    <label class="radio-inline">
                        <input type="radio"
                               name="working_shift_id"
                               value="${shift.id}"
                               ${checked ? 'checked' : ''} required>
                        <strong>${shift.name}</strong>
                        (${shift.start_time} - ${shift.end_time})
                    </label>
                `;
                    });
                });
        }
    </script>
    <script>
        function formatDateLocal(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function setDateRange(from, to) {
            document.querySelector('[name="from_date"]').value = formatDateLocal(from);
            document.querySelector('[name="to_date"]').value = formatDateLocal(to);
        }
    </script>
    <script>
        document.addEventListener('change', function(e) {
            if (e.target.name !== 'apply_type') return;

            const base = new Date();

            let from, to;

            switch (e.target.value) {
                case 'single':
                    from = new Date(base);
                    to = new Date(base);
                    break;

                case '7_days':
                    from = new Date(base);
                    to = new Date(base);
                    to.setDate(to.getDate() + 6);
                    break;

                case 'month':
                    from = new Date(base.getFullYear(), base.getMonth(), 1);
                    to = new Date(base.getFullYear(), base.getMonth() + 1, 0);
                    break;

                case 'range':
                    return;
            }

            setDateRange(from, to);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            setDateRange(today, today);
        });
    </script>

    <script>
        function syncCheckAll() {
            const all = document.querySelectorAll('input[name="employee_ids[]"]');
            const checked = document.querySelectorAll('input[name="employee_ids[]"]:checked');
            const checkAll = document.getElementById('check-all');

            if (!all.length) {
                checkAll.checked = false;
                STATE.checkAll = false;
                return;
            }

            const isAll = all.length === checked.length;
            checkAll.checked = isAll;
            STATE.checkAll = isAll;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const branchSelect = document.getElementById('branch_id');
            const checkAll = document.getElementById('check-all');

            branchSelect.addEventListener('change', function() {
                STATE.checkAll = false;
                checkAll.checked = false;
                loadBranchData(this.value);
            });

            checkAll.addEventListener('change', function() {
                STATE.checkAll = this.checked;
                document
                    .querySelectorAll('input[name="employee_ids[]"]')
                    .forEach(cb => cb.checked = this.checked);
            });
            document.addEventListener('change', function(e) {
                if (e.target.name === 'employee_ids[]') {
                    syncCheckAll();
                }

            });

            if (FORM_DATA.branch_id) {
                branchSelect.value = FORM_DATA.branch_id;
                loadBranchData(
                    FORM_DATA.branch_id,
                    FORM_DATA.employee_ids,
                    FORM_DATA.working_shift_id
                );
            }
        });
    </script>
@endsection
