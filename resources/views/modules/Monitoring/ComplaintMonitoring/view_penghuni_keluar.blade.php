@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                    </div>

                    <form class="custom-validation" method="post" action="{{ route('complaintMonitoring.update_penghuni_keluar') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <h4 class="card-title text-primary mb-2">Maklumat Kuarters</h4>
                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Kuarters</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->quarters_category->name }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">No. Unit</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->quarters->unit_no }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Alamat</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->quarters->address_1 }}, {{ $tenant->quarters->address_2 }}, {{ $tenant->quarters->address_3 }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Tarikh Tawaran</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->quarters_offer_date->format('d/m/Y') }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Tarikh Terima</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->quarters_acceptance_date->format('d/m/Y') }}</p>
                        </div>

                        <hr>

                        <h4 class="card-title text-primary mb-2">Maklumat Penghuni</h4>

                        <div class="row mt-2">
                            <label for="name" class="col-md-2 col-form-label ">Nama Penghuni</label>
                            <input type="hidden" name="tenant_id" value="{{$tenant->id}}">
                            <p class="col-md-9 col-form-label">{{ $tenant->name }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">No Kad Pengenalan</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->new_ic }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Status Perkahwinan</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->marital_status }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">No Telefon (Rumah)</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->phone_no_home }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">No Telefon (Bimbit)</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->phone_no_hp }}</p>
                        </div>

                        <!-- <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Emel Peribadi</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->email }}</p>
                        </div> -->

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Nama Agensi Bertugas</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->organization_name }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Jawatan</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->position }}</p>
                        </div>

                        <!-- <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Alamat Jabatan/Agensi Pembayar Gaji</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->position }}</p>
                        </div> -->

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Gred Jawatan</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->position_grade }}</p>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Taraf Jawatan</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->position_type }}</p>
                        </div>

                        <!-- <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">No Gaji</label>
                            <p class="col-md-9 col-form-label">{{ $tenant->position_type }}</p>
                        </div> -->

                        {{-- <hr> --}}

                        {{-- <h4 class="card-title text-primary">Dokumen</h4>

                    <div class="table-responsive col-sm-9 offset-sm-2">
                        <table class="table table-sm table-bordered" id="table-anak-list">
                            <thead class="text-white bg-primary">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Dokumen</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicationAttachmentAll as $applicationAttachment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center"><a download="{{ $applicationAttachment->document->document_name }}" target="_blank" href="{{ $cdn . $applicationAttachment->path_document }}" title="{{ $applicationAttachment->document->document_name }}">{{ $applicationAttachment->document->document_name }}</a></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}

                        @if ($tenant->leave_status_id == 1)

                            {{-- <hr><h4 class="card-title text-primary mb-2">Maklumat Permohonan Keluar Kuarters</h4>

                        <div class="row mt-2">
                            <label for="name" class="col-md-2 col-form-label ">Tarikh mengosongkan kuarters</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{convertDateSys($tenant->leave_date ?? '')}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Alamat Selepas Keluar Dari Kuarters Kerajaan</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{upperText($tenant->other_address_1 ?? '')}} {{upperText($tenant->other_address_2 ?? '')}} {{upperText($tenant->other_address_3 ?? '')}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Daerah</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->district->district_name}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Poskod</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->other_postcode}}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-2 col-form-label ">Jenis Kediaman Semasa</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->residence_type->residence_type}}</p>
                            </div>
                        </div> --}}

                            {{-- <hr><h4 class="card-title text-primary">Maklumat Sebab Keluar Kuarters</h4>

                        <div class="table-responsive col-sm-9 offset-sm-2">
                            <table class="table table-sm table-bordered">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Sebab Keluar Kuarters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($leaveOptionIdAll->count() == 0)
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @else
                                        @foreach ($leaveOptionIdAll as $leaveOptionId)
                                            <tr>
                                                <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                <td>
                                                    @if ($leaveOptionId->id == 5) {{$tenant->other_leave_reason}} <br> @else {{$leaveOptionId->description}}  @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($tenantsLeaveAttachment)
                        <div class="row">
                            <div class="col-md-2"></div>
                            <label for="name" class="col-md-3 col-form-label ">Salinan Dokumen</label>
                            <div class="col-md-3">
                                <a download="Dokumen Keluar Kuarters" target="_blank" href="{{ getCdn().'/' . $tenantsLeaveAttachment->document_path  }}"
                                    class="btn btn-outline-primary tooltip-icon" title="Dokumen Keluar Kuarters"><i class="{{ __('icon.view_file') }}"></i>
                                </a>
                                <br><br>
                            </div>
                        </div>
                        @endif --}}

                        <hr>
                        <div class="row">
                            <div class="offset-sm-0 col-md-9">
                                <div class="bg-danger bg-soft"  id="parsley-errors"></div>
                            </div>
                        </div>

                        <h4 class="card-title text-primary mb-2">Pemantauan Pengesahan Inventori</h4>
                            <div class="table-responsive col-sm-12 offset-sm-0">
                                <table class="table table-sm table-bordered" cellpadding="4">
                                    <thead class="text-white bg-primary">
                                        <tr class="text-center">
                                            <th width="5%">Bil</th>
                                            <th width="18%">Inventori</th>
                                            <th >Kuantiti Masuk</th>
                                            <th >Status Masuk</th>
                                            <th >Catatan Masuk</th>
                                            <th >Kuantiti Keluar</th>
                                            <th >Status Keluar</th>
                                            <th >Catatan Keluar</th>
                                            <th width="12%">Status Inventori</th>
                                            <th width="12%">Kuantiti Inventori</th>
                                            <th width="12%">Keadaan Inventori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($tenantsQuartersInventoryAll->count() == 0)
                                            <tr class="text-center">
                                                <td colspan="8">Tiada rekod</td>
                                            </tr>
                                        @else
                                            @foreach ($tenantsQuartersInventoryAll as $i => $tqi)
                                                @php $inventory_name = capitalizeText(' '.$tqi->inventory->name)  @endphp
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <input type="hidden" name="tqi_id[{{ $i }}]" id="tqi_id{{ $tqi->id }}"  value="{{$tqi->id}}" >
                                                    <td class="text-center">{{ $tqi->inventory->name}}</td>
                                                    <td class="text-center">{{ $tqi->quantity_in ?? '-' }}</td>
                                                    <td class="text-center">{{ $tqi->inventory_status->inventory_status }}</td>
                                                    <td class="text-center">{{ $tqi->remarks_in ?? '-' }}</td>
                                                    <td class="text-center">{{ $tqi->quantity ?? '-' }}</td>
                                                    <td class="text-center">{{ $tqi->inventory_status_out->inventory_status }}</td>
                                                    <td class="text-center">{{ $tqi->remarks_out ?? '-' }}</td>
                                                    <td class="text-center">
                                                        @foreach($inventoryStatus as $k => $status)
                                                            <div class="form-check" >
                                                                <input class=" form-check-input me-2 ms-1 inventory_status inventory_status_{{$tqi->id}} @error('inventory_status') is-invalid @enderror" type="radio" data-tqi-id="{{$tqi->id}}" data-index-status="{{$k}}"
                                                                    id="inventory_status_{{$k}}"name="inventory_status[{{$i}}]" value="{{ $status->id }}" {{ old('inventory_status.'.$i) == $status->id ? "checked" : "" }}
                                                                    required data-parsley-required-message="{{ setMessage('inventory_status.required').$inventory_name }}" data-parsley-errors-container="#parsley-errors"  >
                                                                <label class="form-check-label me-4" for="inventory_status">
                                                                    {{ $status->inventory_status }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    {{-- {{ $tqi->monitoring_quantity ?? 'Belum Dipantau' }}</td> --}}
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control quantity quantity_{{$tqi->id}} @error('quantity') is-invalid @enderror"
                                                        type="number" name="quantity[{{ $i }}]" id="quantity_{{$i}}" value="{{old('quantity.'.$i)}}"  required  data-parsley-required-message="{{setMessage('quantity.required').$inventory_name}}" data-parsley-type="digits" data-parsley-type-message="{{setMessage('quantity.required').$inventory_name}}" data-parsley-min="1" data-parsley-min-message="{{setMessage('quantity.min')}}" data-parsley-max="10" data-parsley-max-message="{{setMessage('quantity.max') }}" data-parsley-errors-container="#parsley-errors" >
                                                        {{-- {{ $tqi->monitoring_status->inventory_status ?? 'Belum Dipantau' }} --}}
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach($inventoryCondition as $c => $condition)
                                                        <div class="form-check" >
                                                            <input class=" form-check-input me-2 ms-1 condition condition_{{$tqi->id}} @error('condition') is-invalid @enderror" type="radio" data-inventory-id="{{$tqi->id}}" data-inventory-condition="{{$c}}"
                                                                id="condition{{$c}}"name="condition[{{$i}}]"  value="{{ $condition->id }}" {{ old('condition_.'.$i) == $condition->id ? "checked" : "" }}
                                                                required data-parsley-required-message="{{ setMessage('condition.required').$inventory_name }}" data-parsley-errors-container="#parsley-errors"  >
                                                            <label class="form-check-label me-4" for="condition">
                                                                {{ $condition->inventory_condition }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                        {{-- {{ $tqi->condition->inventory_condition ?? 'Belum Dipantau' }} --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div><br>

                            <div class="mb-2 row">
                                <label for="name" class="col-md-2 col-form-label ">Status Pemantauan <span class="text-danger">*</span></label>
                                <div class="col-md-8 mb-3">
                                    @foreach($monitoringLeaveStatus as $i => $status)
                                        <div class="form-check me-2">
                                            <input class="form-check-input me-2 monitor_leave_status @error('monitor_leave_status') is-invalid @enderror" type="radio" name="monitor_leave_status" @if(old('monitor_leave_status')== $status->id ) checked @endif
                                                    value="{{ $status->id }}" {{ old('monitor_leave_status.' . $i) == $status->id ? "checked" : "" }}
                                                    required data-parsley-required-message="{{ setMessage('monitor_leave_status.required') }}"
                                                    data-parsley-errors-container="#checkbox-errors">
                                            <label class="form-check-label" for="monitor_leave_status_{{ $i}}"> {{ capitalizeText($status->monitoring_status) }} </label>
                                        </div>
                                    @endforeach
                                    <div class="col-md-8" id="checkbox-errors"></div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-md-2 col-form-label">Butiran Pemantauan <span class="text-danger">*</span></label>
                                <div class="col-md-8 col-form-label">
                                    <textarea class="form-control  @error('remarks') is-invalid @enderror" rows="4" type="text" id="remarks" name="remarks" value="{{ old('remarks', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}" data-parsley-errors-container="#parsley-remarks-errors" ></textarea>  <div class="col-md-8" id="parsley-remarks-errors"></div>
                                </div>
                            </div>

                            <div class="row">
                                <label for="complaint_proven" class="col-md-2 col-form-label">Bukti Pemantauan <span class="text-danger">*</span></label>
                                <div class="col-md-8 col-form-label">
                                    <input class="form-control @error('leave_attachment') is-invalid @enderror" type="file" name="leave_attachment[]"
                                    value="{{old('leave_attachment')}}"  multiple required
                                    data-parsley-required-message="{{ setMessage('leave_attachment.required') }}"
                                    data-parsley-fileextension='png|jpeg|jpg|PNG|JPG|JPEG'
                                    data-parsley-fileextension-message="{{ setMessage('leave_attachment.mimes') }}."
                                    data-parsley-max-file-size="2000"
                                    data-parsley-max-file-size-message="{{ setMessage('leave_attachment.filemaxmegabytes') }}. ">
                                    @error('leave_attachment')
                                        <span class="invalid-feedback">{{$message}}</span>
                                    @enderror
                                    <p class="card-title-desc mt-1">* JPG, JPEG & PNG sahaja | Saiz fail mestilah bawah 2MB </p>
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <label for="name" class="col-md-2 col-form-label">Pegawai Pemantau</label>
                                <div class="col-md-8 col-form-label">
                                    <input class="form-control" value="{{auth()->user()->name}}" readonly>
                                </div>
                            </div>

                            {{-- <hr><h4 class="card-title text-primary">Dokumen Sokongan</h4>
                        <div class="table-responsive col-sm-9 offset-sm-2">
                            <table class="table table-sm table-bordered" >
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Nama Dokumen</th>
                                        <th class="text-center">Muat Turun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveOptionDocumentAll as $leaveOptionDocument)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="align-left">{{ $leaveOptionDocument->description }}</td>
                                            <td class="text-center">
                                                <a download="{{ $leaveOptionDocument->description }}" target="_blank" href="{{ getCdn().'/' .$leaveOptionDocument->document_path }}"
                                                class="btn btn-outline-primary tooltip-icon" title="{{ $leaveOptionDocument->description }}"><i class="{{ __('icon.view_file') }}"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                        @endif

                        {{-- @if ($tenant->blacklist_date)
                    <hr>
                    <h4 class="card-title text-primary mb-2">Maklumat Penghuni</h4>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Tarikh Hilang Kelayakan</label>
                        <p class="col-md-9 col-form-label">{{ convertDateSys($tenant->blacklist_date) }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Sebab Hilang Kelayakan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->reason?->blacklist_reason }}</p>
                    </div>

                    @endif --}}

                        @if ($tenant->monitor_leave)
                            <hr>
                            <h4 class="card-title text-primary mb-2">Maklumat Pemantauan</h4>

                            <div class="row">
                                <label for="name" class="col-md-2 col-form-label ">Tarikh Pemantauan</label>
                                <p class="col-md-9 col-form-label">{{ convertDateSys($tenant->monitor_leave->monitoring_date) }}</p>
                            </div>

                            <div class="row">
                                <label for="name" class="col-md-2 col-form-label ">Status Pemantauan</label>
                                <p class="col-md-9 col-form-label">{{ $tenant->monitor_leave->monitoring_leave_status->monitoring_status ?? '' }}</p>
                            </div>

                            <div class="row">
                                <label for="name" class="col-md-2 col-form-label ">Butiran Pemantauan</label>
                                <p class="col-md-9 col-form-label">{{ upperText($tenant->monitor_leave->description) }}</p>
                            </div>

                            <div class="row">
                                <label class="col-md-2 col-form-label">Bukti Pemantauan </label>
                                <div class="col-md-9">
                                    <a href="#picture" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                        <span class="tooltip-text">{{ __('button.papar') }}</span>
                                        <i class="{{ __('icon.view_image') }} "></i><br>
                                        Papar
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <label for="name" class="col-md-2 col-form-label ">Pegawai Pemantau</label>
                                <p class="col-md-9 col-form-label">{{ $tenant->monitor_leave?->monitoring_officer?->user?->name }}</p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-11 offset-sm-1">
                                @if($tenant->leave_status_id == 1)<button type="submit" class="btn btn-primary float-end swal-menambah">{{ __('button.simpan') }}</button>@endif
                                <a href="{{ route('complaintMonitoring.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                <!-- <a href="{{ route('tenant.view', ['category' => $tenant->quarters_category_id, 'tenant' => $tenant->id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->



    {{-- Modal  --}}
<div class="modal fade" id="picture" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body" style=" margin: 0;">
                @if ($attachmentAll == null || $attachmentAll->isEmpty())
                    <p id="clickingText">Tiada gambar dijumpai.</p>
                @else
                    <p id="clickingText">Sila klik gambar di bawah:</p>
                @endif

                <div class="container" >
                    <img id="expandedImg" class="mb-4" >
                    <div id="imgtext"></div>
                </div>
                <div  style="text-align: center;">
                    @if ( $attachmentAll == null || $attachmentAll->isEmpty())
                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                    @else
                        @foreach($attachmentAll as $attachment)
                            <img src="{{ getCdn().'/'.$attachment->path_document }}" width= "50px" height="50px"  onclick="showImages(this, {{ $attachment->id }});" style="border: 1px solid black"  >
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

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/ComplaintMonitoring/complaintMonitoring.js')}}"></script>
    <script>
        function showImages(imgs, attachment_id)
        {
            $('#attachment_id').val(attachment_id);
            var expandImg = document.getElementById("expandedImg");
            var clickingText = document.getElementById("clickingText");

            expandImg.src = imgs.src;
            expandImg.parentElement.style.display = "block";
            expandImg.style.width = '100%';
            expandImg.style.height = '300px';
            expandImg.style.border = "1px solid black";

            clickingText.style.display = "none";
        }
    </script>
@endsection
