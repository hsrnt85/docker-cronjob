@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                <h4 class="card-title text-primary">Maklumat Aduan</h4>
                <form id="form" method="post" action="{{ route('complaintAppointment.cancel_appointment') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="officer_cancel_remarks" id="officer_cancel_remarks" >
                    <input type="hidden" name="id" value="{{ $complaint->id }}">
                    {{-- <input type="hidden" name="complaint_appointment" value="{{ $complaint_appointment_latest->id }}"> --}}
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Aduan</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->ref_no??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Pengadu</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->name ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No Telefon</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->phone_no_hp ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tarikh Aduan</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->complaint_date-> format("d/m/Y") ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                        @if($complaint->quarters->category->name ??'' == !null)
                            <p class="col-form-label">{{ $complaint->quarters->category->name ??''}}</p>
                        @elseif($complaint->quarters->category->name ??'' == null)
                            <p class="col-form-label">TIADA BUTIRAN</p>
                        @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Alamat Kuarters</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                        @if($complaint->quarters->unit_no ??'' == !null && $complaint->quarters->address_1 ??'' == !null && $complaint->quarters->address_2 ??'' == !null)
                            <p class="col-form-label">{{ $complaint->quarters->unit_no ??''}} {{ $complaint->quarters->address_1 ??''}} {{ $complaint->quarters->address_2 ??''}}</p>
                        @elseif($complaint->quarters->unit_no ??'' == null && $complaint->quarters->address_1 ??'' == null && $complaint->quarters->address_2 ??'' == null)
                            <p class="col-form-label">TIADA BUTIRAN</p>
                        @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Senarai Inventori</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr class="text-center">
                                        <th width="8%">Bil</th>
                                        <th width="20%">Inventori</th>
                                        <th width="60%">Butiran Kerosakan</th>
                                        <th width="15%">Bukti Aduan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($complaint_inventory->count() != 0)
                                    @foreach ($complaint_inventory as  $complaint_inventory)

                                    <tr class="odd">
                                        <th scope="row" class="text-center">
                                            <input type="hidden" name="complaint_inventory_id"  value="{{$complaint_inventory->id}}" >
                                            {{$loop->iteration}}.
                                        </th>
                                        <td class="text-center">{{ $complaint_inventory->name ?? '' }}</td>
                                        <td class="text-center">{{ $complaint_inventory->description ?? '' }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-outline-primary tooltip-icon" id="btn-show-inventory-damage" onclick="showInventoryDamageList({{ $complaint_inventory->complaint_inventory_id }});" data-route="{{ route('complaintAppointment.ajaxGetComplaintInventoryAttachmentList') }}">
                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="name" class="col-md-2">Butiran Aduan (lain-lain)</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-8">
                            <table class="table table-bordered " id="table-other-complaint" data-list="{{$complaint_others->count()}}">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr>
                                        <th class="text-center" width="8%">Bil</th>
                                        <th class="text-center" width="80%">Butiran Aduan</th>
                                        <th class="text-center" width="15%">Bukti Aduan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($complaint_others->count() != 0)
                                        @foreach($complaint_others as $complaintOthers)
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    <input type="hidden" name="complaint_others_id"  value="{{$complaintOthers->id}}" >
                                                    {{$loop->iteration}}.
                                                </th>
                                                <td class="text-center">{{$complaintOthers->description}}</td>
                                                <td class="text-center">
                                                    <a onclick="showComplaintOthersList({{ $complaintOthers->id }});" data-route="{{ route('complaintAppointment.ajaxGetComplaintOthersAttachmentList') }}"  id="btn-show-complaint-others" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3 row">
                    <h4 class="card-title text-primary">Maklumat Temujanji Aduan</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tarikh Temujanji</label>
                            <div class="col-md-1">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint_appointment_latest->appointment_date->format("d/m/Y") ?? ''}}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Masa Temujanji</label>
                            <div class="col-md-1">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint_appointment_latest->appointment_time->format("h:i A") ?? ''}}</p>
                            </div>
                        </div>
                        <div>
                            @if ($complaint_appointment_latest->data_status == 2)
                                <div class="mb-3  row">
                                    @php
                                        $sebab_batal = "";
                                        if($complaint->data_status == 2) { //Aduan tidak aktif akibat lewat sahkan temujanji
                                            $sebab_batal = "Dibatalkan secara automatik disebabkan lewat membuat pengesahan temujanji aduan.";
                                        }else{
                                            if($complaint_appointment_latest->cancel_remarks) {
                                                $sebab_batal = $complaint_appointment_latest->cancel_remarks;
                                            }else if($complaint_appointment_latest->tenants_remarks->remarks){
                                                $sebab_batal = $complaint_appointment_latest->tenants_remarks->remarks;
                                            }
                                        }
                                    @endphp
                                    <label class="col-md-2 ">Sebab Temujanji Dibatalkan</label>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-9">
                                        {{$sebab_batal}}
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-2 ">Dibatalkan Oleh</label>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-9">
                                    {{$complaint_appointment_latest->delete_name->name ?? '-'}}
                                    </div>
                                </div>
                            @endif
                            <br>
                        </div>

                        @if( ! $complaint_appointment->isEmpty() )
                        <h4 class="card-title text-primary">Sejarah Temujanji Aduan</h4>
                        <div class="col-sm-8">
                            <table class="table table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr>
                                        <th class="text-center">Bil.</th>
                                        <th class="text-center">Tarikh Temujanji</th>
                                        <th class="text-center">Masa</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaint_appointment as $appointment)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $appointment->appointment_date->format("d/m/Y") ?? '' }} </td>
                                        <td class="text-center align-middle">{{ $appointment->appointment_time->format("h:i A") ?? '' }} </td>
                                        <td class="text-center align-middle">
                                            @if ($appointment->data_status == 1){{ $appointment->status_appointment->appointment_status }} @else {{'BATAL'}} @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    <div>

                    <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            @if ($complaint_appointment_latest->appointment_status_id == 1 && $complaint_appointment_latest->data_status == 1 && $complaint_appointment_latest->appointment_date != now()->format('Y-m-d') && $complaint_appointment_latest->appointment_date > now()->format('Y-m-d') )

                            <button type="submit" id="swal-cancel-appointment" class="btn btn-primary float-end ">{{ __('button.batal') }}</button>   @endif
                            {{-- <a href="{{ route('complaintAppointment.index') }}" class="btn btn-secondary float-end me-2">Kembali</a> --}}
                            <a href="{{ route('complaintAppointment.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            {{-- <a href="{{ route('routineInspectionRecord.listInspection', ['category' => $inspection->quarters_category->id]) . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a> --}}
                        </div>
                    </div>

              <!-- Modal Kerosakan Inventori -->
                <div class="modal fade"  id="view-complaint-inventory-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Inventori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <span aria-hidden="true"></span>
                                </a>
                            </div>
                            <div class="modal-body"  id="modal-body" style=" margin: 0;">
                                <p id = "clickingText">Sila klik gambar di bawah:</p>
                                <div class="container" >
                                    <img id="expandedImg" class="mb-4" > </div>
                                <div  style="justify-content: center; display:flex">
                                    @if($complaintInventoryAttachment)
                                        @foreach($complaintInventoryAttachment as $i=>$inventory_attachment)
                                            <img src="{{ getCdn().'/'.$inventory_attachment->path_document }}" width= "50px" height="50px" onclick="showInventoryImage(this,{{ $inventory_attachment->id }});"   style="border: 1px solid black"  >
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Aduan Lain2 -->
                <div class="modal fade" id="view-complaint-others-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Lain-lain</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body" id="complaint-others-modal-body" style=" margin: 0;">
                                <p id = "clickingText2">Sila klik gambar di bawah:</p>
                                <div class="container" >
                                    <img id="expandedImg2" class="mb-4" > </div>
                                <div style="justify-content: center; display:flex">
                                    @if($complaint_attachment)
                                        @foreach($complaint_attachment as $i=>$attachment)
                                            <img src="{{ getCdn().'/'.$attachment->path_document }}" width= "50px" height="50px" onclick="showOtherComplaintImage(this, {{$attachment->id}});" style="border: 1px solid black"  >
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ComplaintAppointment/complaintAppointment.js')}}"></script>
@endsection
