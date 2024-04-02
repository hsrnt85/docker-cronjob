@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            <form class="custom-validation" id="form" method="post" action="{{ route('complaintAppointment.store') }}">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}  (Aduan Baru)</h4></div>
                <h4 class="card-title text-primary">Maklumat Aduan</h4>

                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $complaint->id }}">

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No Aduan</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->ref_no ??''}}</p>
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
                    <label class="col-md-2 col-form-label">Gred Jawatan</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->position->position_name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Gred Jawatan</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{strtoUpper ($complaint->user->office->organization->name ??'')}}</p>
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
                                    <th width="40%">Inventori</th>
                                    <th width="40%">Butiran Kerosakan</th>
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
                                        <a onclick="showInventoryDamageList({{ $complaint_inventory->complaint_inventory_id }});" data-route="{{ route('complaintAppointment.ajaxGetComplaintInventoryAttachmentList') }}" id="btn-show-inventory-damage"  class="btn btn-outline-primary tooltip-icon">
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
                <h4 class="card-title text-primary">Maklumat Temujanji Aduan</h4>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Tarikh Temujanji</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-4">
                        <div class="input-group" id="datepicker2">
                            <input class="form-control @error('appointment_date') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="appointment_date" id="appointment_date"
                            data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                            data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('appointment_date.required') }}" data-parsley-errors-container="#errorContainer">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            @error('appointment_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="errorContainer"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Masa Temujanji</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-4 mb-3">
                        <input class="form-control @error('appointment_time') is-invalid @enderror" type="time" name="appointment_time" value="{{ old('appointment_time', '') }}" required data-parsley-required-message="{{ setMessage('appointment_time.required') }}">
                        @error('appointment_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-8">
                            <label class="col-md-7 mt-3 text-primary">Tarikh Temujanji Yang Telah Dibuat</label></br>
                            <table class="table table-bordered" id="table_appointment_list">
                                <thead>
                                    <tr class="bg-primary bg-gradient text-white"class="text-center">
                                        <th class="text-center" width="8%">Bil</th>
                                        <th class="text-center" width="17%">No. Aduan</th>
                                        <th class="text-center" width="30%">Nama</th>
                                        <th class="text-center" width="30%">Alamat</th>
                                        <th class="text-center" width="15%">Masa</th>
                                    </tr>
                                </thead>
                                <tbody class="field_wrapper_listing"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-11 offset-sm-1">
                        <button type="submit" class="btn btn-primary float-end swal-hantar">{{ __('button.hantar') }}</button>
                        <a href="{{ route('complaintAppointment.index') . '#' . $tab  }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>

                </form>

                <!-- Modal Kerosakan Inventori -->
                <div class="modal fade"  id="view-complaint-inventory-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Inventori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body"  id="modal-body" style=" margin: 0;">
                                {{-- view-complaint-inventory-attachment --}}
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body" id="complaint-others-modal-body" style=" margin: 0;">
                                {{-- view-complaint-others-attachment --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ComplaintAppointment/complaintAppointment.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection
