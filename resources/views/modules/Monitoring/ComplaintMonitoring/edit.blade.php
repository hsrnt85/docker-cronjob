@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body m-2">

            {{-- PAGE TITLE --}}
            @if($complaint->complaint_type == 1)
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1).' '.'Baru (Aduan Kerosakan)' }}</h4></div>
            @else
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1).' '.'Baru (Aduan Awam)'  }}</h4></div>
            @endif

            <h4 class="card-title text-primary mt-2 mb-3">Maklumat Aduan</h4>
            @if ($errors->any())
            <div class="alert alert-danger ">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <form class="custom-validation" method="post" action="{{ route('complaintMonitoring.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $complaint->id }}">
                <input type="hidden" name="appointment_id" value="{{ $complaint_appointment_latest->id ?? '' }}">
                <input type="hidden" name="complaint_type" value="{{ $complaint_monitoring->complaint_type ?? '' }}">

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No. Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9" >
                            <p class="col-form-label">{{ $complaint->ref_no ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Nama Pengadu</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No Telefon</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->user->phone_no_hp ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Tarikh Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->complaint_date-> format("d/m/Y") ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->quarters->category->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Alamat Kuarters</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->quarters->unit_no.',' ??''}} {{ $complaint->quarters->address_1.',' ??''}} {{ $complaint->quarters->address_2.',' ??''}} {{ $complaint->quarters->address_3 ??''}}</p>
                    </div>
                </div>

                @if($complaint->complaint_type == 1)
                 {{-------------------------------- ADUAN KEROSAKAN----------------------------------------}}
                 <div id="checkbox-group">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Untuk Tindakan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-8">
                            <label class="col-md-4 col-form-label"><b><u>Senarai Inventori</u></b></label>
                            <p class="col-form-label">*Tandakan pada ruangan <strong>Pilih (Untuk Penyelenggaraan)</strong> bagi aduan yang perlu dilaksanakan penyelenggaraan.</p>

                            <table class="table table-bordered">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr class="text-center">
                                        <th width="22%" style="margin: auto;">Pilih (Untuk Penyelenggaraan)</th>
                                        <th >Inventori</th>
                                        <th >Butiran Kerosakan</th>
                                        <th width="15%">Gambar Aduan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($complaintInventoryAll -> count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="4">Tiada Rekod</td>
                                    </tr>
                                    @endif
                                    @foreach ($complaintInventoryAll as $i=> $complaint_inventory)
                                    <tr class="odd">
                                        <th scope="row" style="margin: auto;">
                                            <input type="hidden" name="complaint_inventory_id[{{ $i }}]"  value="{{$complaint_inventory->id}}" >
                                            <div class="form-check">
                                                <input style="margin: auto; float:none" class="form-check-input complaint_inventory_check" name="inventoryCheck[{{ $i }}]"  type="checkbox"  value="{{$complaint_inventory->inventory_id}}"  required  data-parsley-mincheck="1" data-parsley-required-message="{{ setMessage('checkbox.required') }}" data-parsley-errors-container="#parsley-checkbox-errors" data-parsley-multiple="checkboxes"  data-parsley-multiple-message="{{ setMessage('checkbox.required') }}"  >
                                            </div>
                                        </th>
                                        <td class="text-center" >{{$complaint_inventory->inventory->name}}</td>
                                        <td class="text-center" >{{$complaint_inventory->description}}</td>
                                        <td class="text-center" ><a onclick="showInventoryDamageList({{ $complaint_inventory->id }},'view');" data-route="{{ route('complaintMonitoring.ajaxGetComplaintInventoryAttachmentList') }}"  id="btn-show-inventory-damage" class="btn btn-outline-primary tooltip-icon">
                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                        </a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label"></label>
                        <div class="col-md-1"></div>
                        <div class="col-md-8">
                            <label class="col-md-4 col-form-label"><b><u>Senarai Aduan Lain-lain</u></b></label>
                            <p class="col-form-label">*Tandakan pada ruangan <strong>Pilih (Untuk Penyelenggaraan)</strong> bagi aduan yang perlu dilaksanakan penyelenggaraan.</p>
                            <table class="table table-bordered" id="table-other-complaint" data-list="{{$complaintOthersAll->count()}}">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr>
                                        <th class="text-center" style="margin: auto;" width="22%">Pilih (Untuk Penyelenggaraan)</th>
                                        <th class="text-center" >Butiran Aduan</th>
                                        <th class="text-center" width="15%">Gambar Aduan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($complaintOthersAll->count() != 0)
                                        @foreach($complaintOthersAll as $i=> $complaintOthers)
                                            <tr>
                                                <td scope="row" style="margin: auto;" >
                                                    <input type="hidden" name="complaint_others_id[{{$i}}]" value="{{$complaintOthers->id}}" >
                                                    <div class="form-check pt-0">
                                                        <input data-parsley-trigger="keyup" style="margin: auto; float:none" class="form-check-input complaint_others_check" type="checkbox" name="complaintCheck[{{ $i }}]"  value="1"  required data-parsley-mincheck="1" data-parsley-required-message="{{ setMessage('checkbox.required') }}" data-parsley-errors-container="#parsley-checkbox-errors" data-parsley-multiple="checkboxes" data-parsley-multiple-message="{{ setMessage('checkbox.required') }}">
                                                    </div>
                                                </td>
                                                <td class="text-center">{{$complaintOthers->description}}</td>
                                                <td class="text-center">
                                                    <a onclick="showComplaintOthersList({{ $complaintOthers->id }},'view');" data-route="{{ route('complaintMonitoring.ajaxGetComplaintOthersAttachmentList') }}"  id="btn-show-complaint-others" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_image') }}"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="3">Tiada rekod</td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <div id="parsley-checkbox-errors"></div>
                        </div>
                    </div>
                 </div>

                    {{-----------------------------------------------------------------------------------------------------------------------------}}
                    <hr>
                    <h4 class="card-title text-primary mt-2 mb-3">Maklumat Temujanji Aduan</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label ">Tarikh Temujanji</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint_monitoring->appointment_date->format("d/m/Y") ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Masa Temujanji</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint_monitoring->appointment_time->format("h:i A") ??''}}</p>
                        </div>
                    </div>

                @else
                    {{-------------------------------- ADUAN AWAM----------------------------------------}}
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Butiran Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->complaint_description ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gambar Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#complaintPicture" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>
                @endif

               {{-----------------------------------------------------------------------------------------------------------------------------}}
                <hr>
                <h4 class="card-title text-primary mt-2 mb-3">Transaksi Pemantauan Aduan</h4>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Butiran Pemantauan Aduan <span class="text-danger">*</span></label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-8 col-form-label">
                        <textarea class="form-control  @error('remarks') is-invalid @enderror" rows="4" type="text" id="remarks" name="remarks" value="{{ old('remarks', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}" data-parsley-errors-container="#parsley-remarks-errors" ></textarea>  <div class="col-md-8" id="parsley-remarks-errors"></div>
                    </div>
                </div>

                <div class="mb-2 row">
                <label class="col-md-2 col-form-label">Muatnaik Gambar Pemantauan </label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-8 col-form-label">
                        <input class="form-control  @error('monitoring_file') is-invalid @enderror" type="file" id="monitoring_file" name="monitoring_file[]"  value="{{old('monitoring_file','')}}"  multiple
                        data-parsley-fileextension='jpg|jpeg|png|JPG|JPEG|PNG'
                        data-parsley-fileextension-message="Sila Gunakan Format JPG, JPEG & PNG sahaja"
                        data-parsley-max-file-size="2000"
                        data-parsley-max-file-size-message="Saiz Fail Mestilah Bawah 2MB">
                        <p class="card-title-desc mt-2">* JPG, JPEG & PNG sahaja | Saiz fail mestilah bawah 2MB </p>
                    </div>
                </div>

                <div class="mb-2 row">
                    @if($complaint->complaint_type == 1)
                        <label for="name" class="col-md-2 col-form-label ">Status Aduan <span class="text-danger">*</span><br><p class="mt-2">(Status Ditolak sekiranya tiada tindakan penyelenggaraan akan diambil bagi semua aduan tersebut)</p>
                            @else
                        <label for="name" class="col-md-2 col-form-label ">Status Pemantauan <span class="text-danger">*</span>@endif
                      </label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-8 mb-3">

                        @if($complaint->complaint_type == 1)
                          {{-------------------------------- ADUAN KEROSAKAN----------------------------------------}}
                            @foreach($complaintStatus as $i => $status)

                                @php
                                    $desc = "";
                                    if($status->id == 2){$desc = ' dan tidak perlu diselenggara';} else {$desc= ' dan perlu diselenggara';}
                                @endphp

                                <div class="form-check me-2">
                                    <input class="form-check-input me-2 complaint_status @error('complaint_status') is-invalid @enderror" type="radio" name="complaint_status" @if(old('complaint_status')== $status->id ) checked @endif
                                            value="{{ $status->id }}" {{ old('complaint_status.' . $i) == $status->id ? "checked" : "" }}
                                            required data-parsley-required-message="{{ setMessage('complaint_status.required') }}"
                                            data-parsley-errors-container="#checkbox-errors">
                                    <label class="form-check-label" for="complaint_status_{{ $i}}"> {{ capitalizeText($status->complaint_status).$desc }} </label>

                                </div>
                            @endforeach
                            <div class="col-md-8" id="checkbox-errors"></div>
                        @else
                            {{-------------------------------- ADUAN AWAM-----------------------------------------}}
                            @foreach($monitoringStatus as $i => $status)
                                <div class="form-check me-2">
                                    <input class="form-check-input me-2 monitoring_status @error('monitoring_status') is-invalid @enderror" type="radio" name="monitoring_status" @if(old('monitoring_status')== $status->id ) checked @endif
                                            value="{{ $status->id }}" {{ old('monitoring_status.' . $i) == $status->id ? "checked" : "" }}
                                            required data-parsley-required-message="{{ setMessage('monitoring_status.required') }}"
                                            data-parsley-errors-container="#monitoring-errors">
                                    <label class="form-check-label" for="monitoring_status{{ $i}}"> {{ capitalizeText($status->monitoring_status) }} </label>

                                </div>
                            @endforeach
                        @endif
                        <div id="monitoring-errors"></div>
                    </div>
                </div>

                <div id="rejected_div" style="display:none">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label"  for="rejected_reason">Ulasan Penolakan <span class="text-danger">*</span></label>
                        <div class="col-md-1 ">:</div>
                        <div class="col-md-8 ">
                            <textarea class="form-control  @error('rejected_reason') is-invalid @enderror" rows="4" type="text" name="rejected_reason" id="rejected_reason" value="{{ old('rejected_reason', '') }}" required data-parsley-required-message="{{ setMessage('rejected_reason.required') }}" data-parsley-errors-container="#rejected-errors" ></textarea>
                            <div  id="rejected-errors"></div>
                            @error('rejected_reason')
                                <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Modal Kerosakan Inventori --}}
                <div class="modal fade"  id="view-complaint-inventory-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Inventori</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true"></span> </button>
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
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span> </button>
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

                {{-- Modal Bukti Aduan Awam --}}
                <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Awam</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">
                            <p id = "clickingText_A">Sila klik gambar di bawah:</p>
                                <div class="container" >
                                    <img id="expandedImg_A" class="mb-4" >
                                    <div id="imgtext_A"></div>
                                </div>
                                <div  style="text-align: center;">
                                    @if ($complaint_attachment)
                                        @foreach($complaint_attachment as $attachment)
                                            <img src="{{getCdn().'/'.$attachment->path_document}}" width= "50px" height="50px"  onclick="showImagesAwam(this);" style="border: 1px solid black"  >
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

                <div class="mb-3 row">
                    <div class="col-sm-11 offset-sm-1">
                        <button type="submit" class="btn btn-primary float-end swal-menambah">{{ __('button.simpan') }}</button>
                        <a href="{{ route('complaintMonitoring.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/ComplaintMonitoring/complaintMonitoring.js')}}"></script>
@endsection
