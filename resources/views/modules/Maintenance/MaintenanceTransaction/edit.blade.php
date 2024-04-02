@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body m-2">

            {{-- PAGE TITLE --}}
            <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1)}}</h4></div>

                <h4 class="card-title text-primary mt-2 mb-3">Maklumat Aduan Kerosakan</h4>

                <form class="custom-validation" id="form" method="post" action="{{ route('maintenanceTransaction.update') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" name="id" value="{{ $complaintId }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->ref_no ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Pengadu</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->user->name ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->user->phone_no_hp ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tarikh Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->complaint_date-> format("d/m/Y") ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->quarters->category->name ??''}}</p>
                    </div>


                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Alamat Kuarters</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->quarters->unit_no.',' ??''}} {{ $complaint->quarters->address_1.',' ??''}} {{ $complaint->quarters->address_2.',' ??''}} {{ $complaint->quarters->address_3 ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Senarai Kerosakan Inventori</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <div id="datatable_wrapper">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table  class="table table-bordered dt-responsive wrap w-100" role="grid" >
                                            <thead class="bg-primary bg-gradient text-white">
                                                <tr role="row">
                                                    <th class="text-center" width="5%">Bil.</th>
                                                    <th class="text-center" width="30%">Inventori</th>
                                                    <th class="text-center" width="30%">Butiran Kerosakan</th>
                                                    <th class="text-center" width="15%">Gambar Aduan</th>
                                                    <th class="text-center" width="20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($complaintInventory -> isEmpty())
                                                    <tr class="odd">
                                                        <td class="text-center" colspan="5">Tiada Rekod</td>
                                                    </tr>
                                                @else
                                                    @foreach($complaintInventory as $c_inventory)
                                                        <tr class="odd">
                                                            <td class="text-center" tabindex="0">{{$loop->iteration}}.</td>
                                                            <td class="text-center">{{$c_inventory->inventory->name}}</td>
                                                            <td class="text-center">{{$c_inventory->description}}</td>
                                                            <td class="text-center">
                                                                <a onclick="showInventoryDamageList({{ $c_inventory->id }});" data-route="{{ route('maintenanceTransaction.ajaxGetComplaintInventoryAttachmentList') }}" id="btn-show-inventory-damage" class="btn btn-outline-primary tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center" >{{$complaint->status->complaint_status}} </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Senarai Kerosakan Lain-lain</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <div id="datatable_wrapper">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table  class="table table-bordered dt-responsive wrap w-100" role="grid" >
                                            <thead class="bg-primary bg-gradient text-white">
                                                <tr role="row">
                                                    <th class="text-center" width="5%">Bil.</th>
                                                    <th class="text-center" width="60%">Butiran Kerosakan</th>
                                                    <th class="text-center" width="15%">Gambar Aduan</th>
                                                    <th class="text-center" width="20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($complaintOthers -> isEmpty())
                                                    <tr class="odd">
                                                        <td class="text-center" colspan="5">Tiada Rekod</td>
                                                    </tr>
                                                @else
                                                    @foreach($complaintOthers as $c_others)
                                                        <tr class="odd">
                                                            <td class="text-center" tabindex="0">{{$loop->iteration}}.</td>
                                                            <td class="text-center">{{$c_others->description}}</td>
                                                            <td class="text-center">
                                                                <a onclick="showComplaintOthersList({{ $c_others->id }});" data-route="{{ route('maintenanceTransaction.ajaxGetComplaintOthersAttachmentList') }}"  id="btn-show-complaint-others"  class="btn btn-outline-primary tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center" >{{$complaint->status->complaint_status}} </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Transaksi Penyelenggaraan</h4>

                    <div class="mb-3 row">
                        <label for="start_date" class="col-md-2 col-form-label">Tarikh Selenggara</label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4">
                            <div class="input-group" id="datepicker2">
                                <input class="form-control @error('start_date') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="start_date" id="start_date"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('start_date.required') }}" data-parsley-errors-container="#errorContainer">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="errorContainer"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="monitoring_officer_id" class="col-md-2 col-form-label">Pegawai Pemantau</label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4">
                            <select class="form-select @error('monitoring_officer_id') is-invalid @enderror" name="monitoring_officer_id" required data-parsley-required-message="{{ setMessage('monitoring_officer_id.required') }}"  >
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($officerPemantauAll as $officer)
                                    <option value="{{ $officer->id }}" {{ old('monitoring_officer_id') == $officer->id ? "selected" : "" }}>
                                        {{ $officer->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('monitoring_officer_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label mt-1">Status Penyelenggaraan <span class="text-danger">*</span></label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4 col-form-label">
                            @foreach($maintenanceStatus as $i => $status)
                            <div class="form-check me-2">
                                <input class="form-check-input me-2 @error('maintenance_status') is-invalid @enderror" type="radio" name="maintenance_status" @if(old('maintenance_status')== $status->id ) checked @endif
                                        value="{{ $status->id }}" {{ old('maintenance_status.' . $i) == $status->id ? "checked" : "" }}
                                        required data-parsley-required-message="{{ setMessage('maintenance_status.required') }}" data-parsley-errors-container="#status-errors"  >
                                <label class="form-check-label" for="maintenance_status_{{ $i}}"> {{ capitalizeText($status->status) }} </label>
                            </div>
                            @endforeach
                            <div id="status-errors" ></div>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label class="col-md-2 col-form-label">Muatnaik Gambar Penyelenggaraan </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4 col-form-label">
                            <input class="form-control maintenance_file" type="file" name="maintenance_file[]"  value="{{old('maintenance_file','')}}"  multiple
                           data-parsley-fileextension='png|jpeg|jpg|PNG|JPG|JPEG'
                           data-parsley-fileextension-message="{{ setMessage('maintenance_file.mimes') }}."
                           data-parsley-max-file-size="2000"
                           data-parsley-max-file-size-message="{{ setMessage('maintenance_file.filemaxmegabytes') }}. ">
                            @error('maintenance_file')
                                <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                            <p class="card-title-desc mt-2">* JPG, JPEG & PNG sahaja | Saiz fail mestilah bawah 2MB</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Catatan <span class="text-danger">*</span></label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4 col-form-label">
                            <textarea class="form-control  @error('remarks') is-invalid @enderror" rows="4" type="text" name="remarks" value="{{ old('remarks', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}" data-parsley-errors-container="#remarks-errors" ></textarea>
                            <div class="col-md-8" id="remarks-errors"></div>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3 row">
                        <h4 class="card-title text-primary mt-2 mb-3">Sejarah Transaksi Penyelenggaraan</h4>
                        <label class="col-md-2 col-form-label">Sejarah Transaksi Penyelenggaraan </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <table class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr>
                                        <th class="text-center">Bil.</th>
                                        <th class="text-center">Tarikh</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Pegawai Pemantau</th>
                                        <th class="text-center">Catatan</th>
                                        <th class="text-center">Gambar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( ! $maintenanceTransactionHistory->isEmpty() )
                                        @foreach($maintenanceTransactionHistory as $history)
                                        <tr><input type="hidden"  value="{{$history->id}}">
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{ convertDateSys($history->maintenance_date) }} </td>
                                            <td class="text-center align-middle">{{ $history->status->status ?? '' }} </td>
                                            <td class="text-center align-middle">{{ $history->officer->user->name ?? '' }} </td>
                                            <td class="text-center align-middle">{{ $history->remarks  }} </td>
                                            <td class="text-center align-middle">
                                                <a onclick="showImgMaintenance({{$history->id}});" data-route="{{ route('maintenanceTransaction.ajaxGetMaintenanceTransactionAttachment') }}" id="btn-show-maintenance-image" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center align-middle" colspan="5">{{'Tiada Rekod'}}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 mt-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini-parsley">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('maintenanceTransaction.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

                    {{-------------------------------------------------------MODAL GAMBAR TRANSAKSI PENYELENGGARAAN ----------------------------------------------------------------}}

                    <div class="modal fade" id="view-image-maintenance-transaction" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gambar Penyelenggaraan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body" id="maintenance-body" style=" margin: 0;">
                                    <!--  modal-maintenance-transaction-attachment -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-----------------------------------------------------------MODAL GAMBAR ADUAN ---------------------------------------------------------------------------------}}

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
                                    {{-- modal-complaint-inventory-attachment --}}
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
                                    {{-- modal-complaint-others-attachment --}}
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
<script src="{{ URL::asset('assets/js/pages/MaintenanceTransaction/maintenanceTransaction.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection
