@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            <form class="custom-validation" id="form" method="post" action="{{ route('rulesViolationComplaintApproval.store') }}">

                {{-- PAGE TITLE --}}
                <h4 class="card-title">{{ getPageTitle(1) }}  (Aduan Baru)</h4><hr>
                <h4 class="card-title text-primary">Maklumat Aduan</h4>

                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $complaint->id }}">

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
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
                    <label class="col-md-2 col-form-label">Gred Jawatan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->position->position_name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Gred Jawatan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{strtoUpper ($complaint->user->office->organization->name ??'')}}</p>
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
                    <div class="col-md-1  col-form-label">:</div>
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


                <hr>
                <h4 class="card-title text-primary">Maklumat Pengesahan Aduan</h4>

                <div class="mb-3 mt-2 row">
                    <label class="col-md-2 col-form-label">Pengesahan Aduan</label>
                    <div class="col-md-1">:</div>
                    <div class="col-md-7  @error('complaint_status') is-invalid @enderror ">
                        @foreach($complaintStatusAll as $i => $complaint_status)
                            <div class="form-check me-5  form-check-inline">
                                <input class="form-check-input me-2 complaint_status @error('complaint_status') is-invalid @enderror" type="radio" name="complaint_status" @if(old('complaint_status')== $complaint_status->id ) checked @endif
                                onclick="ShowHideReason({{ $complaint_status->id }})"
                                        value="{{ $complaint_status->id }}" {{ old('complaint_status.' . $i) == $complaint_status->id ? "checked" : "" }} required
                                         data-parsley-required-message="{{ setMessage('complaint_status.required') }}"
                                        data-parsley-errors-container="#parsley-errors-complaint-status">
                                <label class="form-check-label" for="complaint_status_{{ $i}}"> {{ capitalizeText($complaint_status->complaint_status) }} </label>
                            </div>
                        @endforeach

                        <div id="parsley-errors-complaint-status"></div>

                        @error('complaint_status')
                        <span class="invalid-feedback">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div id="section_rejection_reason" style="display:none">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label" for="rejection_reason">Keterangan Penolakan Aduan<span class="text-danger">*</span></label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-7">
                            <textarea class="form-control @error('rejection_reason') is-invalid @enderror"
                                rows="3" id="rejection_reason" name="rejection_reason"  data-parsley-required-message="{{ setMessage('rejection_reason.required')}}" >{{old('rejection_reason')}}</textarea>
                            @error('rejection_reason')
                                <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-11 offset-sm-1">
                        <button type="submit" class="btn btn-primary float-end swal-hantar">{{ __('button.hantar') }}</button>
                        <a href="{{ route('rulesViolationComplaintApproval.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </form>

            <!-- Modal Gambar Aduan Awam-->
            <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Bukti Aduan Awam </h5>
                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body" style=" margin: 0;">
                            @if ($complaint_attachment->isEmpty() || $complaint_attachment == null )
                                <p id="clickingText">Tiada bukti aduan dijumpai.</p>
                            @else
                                <p id="clickingText">Sila klik gambar di bawah:</p>
                            @endif
                            <div class="container" >
                                <img id="expandedImg" class="mb-4" >
                                <div id="imgtext"></div>
                            </div>
                            <div  style="text-align: center;">
                            @if (!$complaint_attachment->isEmpty())
                                @foreach($complaint_attachment as $attachment)
                                    @if ($attachment->path_document)
                                        <img src="{{ getCdn().'/'.$attachment->path_document }}" class="mb-1" width= "50px" height="50px"  onclick="showImages(this);" style="border: 1px solid black"  >
                                    @else
                                        <img  src="{{ getNoImages() }}"  alt="no_images" width="auto" height="100px" class="mt-3">
                                    @endif
                                @endforeach
                            @else
                                <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                            @endif
                            </div>
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
<script src="{{ URL::asset('assets/js/pages/RulesViolationComplaintApproval/rulesViolationComplaintApproval.js')}}"></script>
@endsection
