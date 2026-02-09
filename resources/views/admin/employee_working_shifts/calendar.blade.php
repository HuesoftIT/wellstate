@extends('adminlte::layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
@endsection

@section('htmlheader_title', 'Lịch phân ca')
@section('contentheader_title', 'Lịch phân ca')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> Dashboard</a></li>
        <li><a href="{{ route('employee-working-shifts.index') }}">Phân ca</a></li>
        <li class="active">Lịch phân ca</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            {{-- <div class="mb-4">
                <label class="font-semibold mb-1 block">Chọn chi nhánh</label>
                <select id="branchFilter" class="form-control w-64">
                    <option value="">-- Tất cả chi nhánh --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <div id="calendar"></div>
        </div>
    </div>
@endsection


@section('scripts-footer')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales-all.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                locale: 'vi',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay'
                },

                events: '{{ route('employee-working-shifts.calendar.events') }}',

                eventClick(info) {
                    const e = info.event.extendedProps;
                    const employeeHtml = e.employees.map(name => `
                                <span style="
                                    display:inline-block;
                                    background:#f1f5f9;
                                    padding:6px 10px;
                                    border-radius:6px;
                                    margin:4px;
                                    font-size:13px;
                                ">
                                    👤 ${name}
                                </span>
                            `).join('');

                    Swal.fire({
                        title: info.event.title,
                        html: `
            <div style="text-align:left">
                <div style="margin-bottom:10px">
                    <b>🏢 Chi nhánh:</b><br>
                    <span style="color:#2563eb">${e.branch}</span>
                </div>

                <div>
                    <b>👥 Nhân viên:</b><br>
                    <div style="margin-top:6px">
                        ${employeeHtml}
                    </div>
                </div>
            </div>
        `,
                        width: 600,
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#6366f1'
                    });
                }

            });

            calendar.render();
        });
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const branchSelect = document.getElementById('branchFilter');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                locale: 'vi',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay'
                },

                events: function(info, successCallback, failureCallback) {
                    fetch(
                            `{{ route('employee-working-shifts.calendar.events') }}?start=${info.startStr}&end=${info.endStr}&branch_id=${branchSelect.value}`)
                        .then(res => res.json())
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                },

                eventClick(info) {
                    const e = info.event.extendedProps;

                    const employeeHtml = e.employees.map(name => `
                <span style="
                    display:inline-block;
                    background:#f1f5f9;
                    padding:6px 10px;
                    border-radius:6px;
                    margin:4px;
                    font-size:13px;
                ">
                    👤 ${name}
                </span>
            `).join('');

                    Swal.fire({
                        title: info.event.title,
                        html: `
                    <div style="text-align:left">
                        <div style="margin-bottom:10px">
                            <b>🏢 Chi nhánh:</b><br>
                            <span style="color:#2563eb">${e.branch}</span>
                        </div>

                        <div>
                            <b>👥 Nhân viên:</b><br>
                            <div style="margin-top:6px">
                                ${employeeHtml}
                            </div>
                        </div>
                    </div>
                `,
                        width: 600,
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#6366f1'
                    });
                }
            });

            calendar.render();

            branchSelect.addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script> --}}

@endsection
