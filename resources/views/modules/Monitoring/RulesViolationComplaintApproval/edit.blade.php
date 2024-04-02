@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            {{-- <form method="post" class="custom-validation" id="form" action="{{ route('rulesViolationComplaintApproval.update') }}"> --}}

                {{-- PAGE TITLE --}}
                <h4 class="card-title">{{ getPageTitle(1) }}  (Pengesahan Aduan)</h4><hr>
                <h4 class="card-title text-primary mt-2 mb-3">Maklumat Aduan</h4>

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

                {{-- <hr>
                <h4 class="card-title text-primary mt-2 mb-3">Maklumat Pengesahan Aduan</h4> --}}

                {{-- <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Keterangan Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-7 col-form-label">
                        <textarea class="form-control @error('remarks') is-invalid @enderror" type="text" rows="3" name="remarks" required data-parsley-required-message="{{ setMessage('remarks.required') }}">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div> --}}


                <div class="mb-3 row">
                    <div class="col-sm-11 offset-sm-1">
                        {{-- <button type="submit" class="btn btn-primary float-end swal-hantar">{{ __('button.hantar') }}</button> --}}
                        <a href="{{ route('rulesViolationComplaintApproval.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>

                <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <h5 class="modal-title modal-header">Bukti Aduan Awam</h5>
                            <div class="modal-body">
                                @if ($complaint_attachment->isEmpty() || $complaint_attachment == null )
                                    <p id="clickingText">Tiada bukti aduan dijumpai.</p>
                                @else
                                    <p id="clickingText">Sila klik gambar di bawah:</p>
                                @endif
                                <div class="container" >
                                    {{-- <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span> --}}
                                    <img id="expandedImg" class="mb-4" >
                                    <div id="imgtext"></div>
                                </div>
                                <div  style="text-align: center;">
                                @if ($complaint_attachment -> count() == 0)
                                    <p class="text-center" colspan="3">Tiada Rekod</p>
                                @endif
                                @foreach($complaint_attachment as $attachment)
                                    <img src="{{getCdn().'/' .$attachment->path_document}}" width= "50px" height="50px"  onclick="showImages(this);" style="border: 1px solid black"  >
                                @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- </form> --}}
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/RulesViolationComplaintApproval/rulesViolationComplaintApproval.js')}}"></script>
@endsection
