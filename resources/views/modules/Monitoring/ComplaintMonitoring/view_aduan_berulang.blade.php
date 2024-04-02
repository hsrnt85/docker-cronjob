@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <p class="card-title text-primary mt-2 mb-3">Maklumat Aduan Awam</p>
                <form class="custom-validation" method="post" action="{{ route('complaintMonitoring.update_aduan_berulang') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $complaint->id }}">

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
                    {{--------------------------------------------------------PEMANTUAN PERTAMA ------------------------------------------------------------------------------------------}}
                    <hr>
                    <h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Aduan</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Butiran Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4">
                            <p class="col-form-label">{{ upperText($complaint_monitoring->monitoring_remarks) ?? ''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gambar Pemantauan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#monitoringPicture" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint_monitoring->status->monitoring_status ??''}}</p>
                        </div>
                    </div>

                    {{--------------------------------------------------------PEMANTUAN KEDUA / AKHIR ------------------------------------------------------------------------------------------}}
                    @if ($complaint_monitoring->monitoring_counter == 1) {{-- nk buat pemantauan kedua --}}
                        <hr>
                        <h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Berulang (Kedua)</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Butiran Pemantauan<span class="text-danger">*</span></label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-4 col-form-label">
                                <textarea class="form-control" rows="4" type="text" name="remarks_repeat" value="{{ old('remarks_repeat', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}"></textarea>
                            </div>
                        </div>
                @elseif ($complaint_monitoring->monitoring_counter == 2)  {{--nk buat pemantauan akhir --}}
                        {{--------------------------------------------------------MAKLUMAT PEMANTUAN KEDUA ------------------------------------------------------------------------------------------}}
                        <hr>
                        <h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Berulang (Kedua)</h4>

                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label">Butiran Pemantauan</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-4 ">
                                <p class="col-form-label">{{ $complaint_monitoring->monitoring_remarks_repeat ??''}}</p>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label">Gambar Pemantauan</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9 col-form-label">
                                <a href="#monitoringPicture2" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint_monitoring->status->monitoring_status ??''}}</p>
                            </div>
                        </div>
                         {{--------------------------------------------------------PEMANTUAN KEDUA / AKHIR ------------------------------------------------------------------------------------------}}
                        <hr>
                        <h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Berulang (Akhir)</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Butiran Pemantauan <span class="text-danger">*</span></label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-4 col-form-label">
                                <textarea class="form-control" rows="4" type="text" name="remarks_final" value="{{ old('remarks_final', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}"></textarea>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Muatnaik Gambar Pemantauan </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4 col-form-label">
                            <input class="form-control" type="file" id="monitoring_file" name="monitoring_file[]"  value="{{old('monitoring_file','')}}"  multiple
                            data-parsley-fileextension='jpg|jpeg|png|JPG|JPEG|PNG'
                            data-parsley-fileextension-message="Sila Gunakan Format JPG, JPEG & PNG sahaja"
                            data-parsley-max-file-size="2000"
                            data-parsley-max-file-size-message="Saiz Fail Mestilah Bawah 2MB">
                            <p class="card-title-desc mt-2">* JPG, JPEG & PNG sahaja | Saiz fail mestilah bawah 2MB </p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label ">Status Pemantauan Berulang <span class="text-danger">*</span> </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-8 mb-3 col-form-label">
                            @if($complaint_monitoring->monitoring_counter == 1)  {{--pemantauan kedua --}}
                                @foreach($monitoring_status_repeat as $i => $monitoringStatus)
                                    <div class="form-check me-2">
                                        <input class="form-check-input me-2 @error('monitoring_status') is-invalid @enderror" type="radio" name="monitoring_status" @if(old('monitoring_status')== $monitoringStatus->id ) checked @endif
                                                value="{{ $monitoringStatus->id }}" {{ old('monitoring_status.' . $i) == $monitoringStatus->id ? "checked" : "" }}
                                                required data-parsley-required-message="{{ setMessage('monitoring_status.required') }}"
                                                data-parsley-errors-container="#parsley-monitoring-status">
                                        <label class="form-check-label" for="monitoring_status{{ $i}}"> {{ capitalizeText($monitoringStatus->monitoring_status) }} </label>
                                    </div>
                                @endforeach
                                <div class="col-md-8" id="parsley-monitoring-status"></div>
                            @elseif ($complaint_monitoring->monitoring_counter == 2) {{--pemantauan akhir --}}
                                @foreach($monitoring_status_final as $i => $monitoringStatus)
                                    <div class="form-check me-2">
                                        <input class="form-check-input me-2 @error('monitoring_status') is-invalid @enderror" type="radio" name="monitoring_status" @if(old('monitoring_status')== $monitoringStatus->id ) checked @endif
                                                value="{{ $monitoringStatus->id }}" {{ old('monitoring_status.' . $i) == $monitoringStatus->id ? "checked" : "" }}
                                                required data-parsley-required-message="{{ setMessage('monitoring_status.required') }}"
                                                data-parsley-errors-container="#parsley-monitoring-status-2">
                                        <label class="form-check-label" for="monitoring_status{{ $i}}"> {{ capitalizeText($monitoringStatus->monitoring_status) }} </label>
                                    </div>
                                @endforeach
                                <div class="col-md-8" id="parsley-monitoring-status-2"></div>
                            @endif
                        </div>
                    </div>

                    {{-----------------------------------------------------------MODAL BUKTI ADUAN AWAM -----------------------------------------------------------------------------}}
                    <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Bukti Aduan Awam</h5>
                                    <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                                </div>
                                <div class="modal-body">

                                    @if ($complaint_attachment->isEmpty() || $complaint_attachment == null )
                                        <p id="clickingText_A">Tiada bukti aduan dijumpai.</p>
                                    @else
                                        <p id="clickingText_A">Sila klik gambar di bawah:</p>
                                    @endif

                                    <div class="container" >
                                        <img id="expandedImg_A" class="mb-4" >
                                        <div id="imgtext_A"></div>
                                    </div>
                                    <div  style="text-align: center;">

                                        @if ($complaint_attachment->isEmpty() || $complaint_attachment == null)
                                            <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                        @else
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

                    {{----------------------------------------------------------- MODAL BUKTI PEMANTAUAN PERTAMA----------------------------------------------------------------------------}}
                    <div class="modal fade" id="monitoringPicture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gambar Pemantauan</h5>
                                    <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                                </div>
                                <div class="modal-body">
                                    @if ($monitoring_attachment_1->isEmpty() || $monitoring_attachment_1 == null )
                                        <p id="clickingText_A1">Tiada gambar pemantauan dijumpai.</p>
                                    @else
                                        <p id="clickingText_A1">Sila klik gambar di bawah:</p>
                                    @endif
                                    <div class="container" >
                                    <img id="expandedImg_A1" class="mb-4" >
                                        <div id="imgtext_A1"></div>
                                    </div>
                                    <div  style="text-align: center;">
                                        @if ($monitoring_attachment_1->isEmpty() || $monitoring_attachment_1 == null)
                                            <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                        @else
                                            @foreach($monitoring_attachment_1 as $monitoring_attachment)
                                                <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" width= "50px" height="50px"  onclick="showImagesMonitoringAwam1(this);" style="border: 1px solid black"  >
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

                    {{----------------------------------------------------------- MODAL BUKTI PEMANTAUAN KEDUA -----------------------------------------------------------------------------}}
                    <div class="modal fade" id="monitoringPicture2" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gambar Pemantauan</h5>
                                    <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                                </div>
                                <div class="modal-body">
                                    @if ($monitoring_attachment_2->isEmpty() || $monitoring_attachment_2 == null )
                                        <p id="clickingText_A2">Tiada gambar pemantauan dijumpai.</p>
                                    @else
                                        <p id="clickingText_A2">Sila klik gambar di bawah:</p>
                                    @endif
                                    <div class="container" >
                                    <img id="expandedImg_A2" class="mb-4" >
                                        <div id="imgtext_A2"></div>
                                    </div>
                                    <div  style="text-align: center;">
                                        @if ($monitoring_attachment_2->isEmpty() || $monitoring_attachment_2 == null)
                                            <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                        @else
                                            @foreach($monitoring_attachment_2 as $monitoring_attachment)
                                                <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" width= "50px" height="50px"  onclick="showImagesMonitoringAwam2(this);" style="border: 1px solid black"  >
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
                            <button type="submit" class="btn btn-primary float-end swal-mengemaskini">{{ __('button.kemaskini') }}</button>
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
