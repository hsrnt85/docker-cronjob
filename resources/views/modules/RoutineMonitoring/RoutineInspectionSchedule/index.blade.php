@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                <div class="row">
                    <div class="col-sm-12 col-md-10 offset-md-1">
                        <div id="calendar" data-route="{{ route('routineInspectionSchedule.ajaxGetSenaraiPemantauan') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
        <div class="modal-header">
        <h5 class="modal-title" id="modal-title">PBJB2302000001</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 border-secondary">
                    <tbody>
                        <tr>
                            <th class="table-primary border-0">No. Rujukan</th>
                            <td id="no-rujukan"></td>
                        </tr>
                        <tr>
                            <th class="table-primary border-0">Lokasi</th>
                            <td id="lokasi"></td>
                        </tr>
                        <tr>
                            <th class="table-primary border-0">Alamat</th>
                            <td id="alamat"></td>
                        </tr>
                        <tr>
                            <th class="table-primary border-0">Tarikh Pemantauan</th>
                            <td id="tarikh-pemantauan"></td>
                        </tr>
                        <tr>
                            <th class="table-primary border-0">Pegawai Pemantau</th>
                            <td id="pegawai-pemantau"></td>
                        </tr>
                        <tr>
                            <th class="table-primary border-0">Catatan</th>
                            <td id="catatan"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="mt-3 btn btn-secondary btn-block waves-effect waves-light col-sm-12" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(function () {

        var calendarEl = document.getElementById('calendar');
        var route = $('#calendar').data('route');
        var token  = $('meta[name="csrf-token"]').attr('content');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: false,
            events: function (info, successCallback, failureCallback) {

                var events = [];

                $.ajax({
                    url: route,
                    type:"POST",
                    data:{
                        _token: token,
                        bulan: new Date(info.startStr),
                        start: info.start
                    },
                }).done(function(response, textStatus, jqXHR){
                    for (var i = 0; i < response.inspectionAll.length; i++) 
                    {
                        var event = {
                            title: response.inspectionAll[i].ref_no,
                            start: moment(new Date(response.inspectionAll[i].inspection_date)).format().substring(0, 10), 
                            quarters_category: response.inspectionAll[i].quarters_category.name,
                            address: response.inspectionAll[i].address,
                            monitoring_date: moment(new Date(response.inspectionAll[i].inspection_date)).format('DD/MM/YYYY'),
                            officer: response.inspectionAll[i].monitoring_officer.user.name,
                            remarks: response.inspectionAll[i].remarks,
                            "className": "cursor-pointer",
                        };

                        events.push(event);
                    }

                    successCallback(events);
                });

            },
            showNonCurrentDates: false,
            eventClick: function(info) {
                $('#modal-title').html(info.event.title);
                $('#no-rujukan').html(info.event.title);
                $('#lokasi').html(info.event.extendedProps.quarters_category);
                $('#alamat').html(info.event.extendedProps.address);
                $('#tarikh-pemantauan').html(info.event.extendedProps.monitoring_date);
                $('#pegawai-pemantau').html(info.event.extendedProps.officer);
                $('#catatan').html(info.event.extendedProps.remarks);
                $('#exampleModal').modal('show');
            },
        });

        calendar.render();
    });

</script>
@endsection