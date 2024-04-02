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
                        <label class="col-md-2 col-form-label">Butiran Aduan</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                        @if($complaint->complaint_description == !null)
                            <p class="col-form-label">{{ $complaint->complaint_description ??''}}</p>
                        @elseif($complaint->complaint_description == null)
                            <p class="col-form-label">TIADA BUTIRAN</p>
                        @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Bukti Aduan (Gambar)</label>
                        <div class="col-md-1">:</div>
                        <div class="col-md-9">
                            <a href="#complaintPicture" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Aduan <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3 row">
                        <h4 class="card-title text-primary">Maklumat Pengesahan Aduan</h4>

                        <div class="mb-3 mt-2 row">
                            <label class="col-md-2 col-form-label">Tarikh Pengesahan</label>
                            <div class="col-md-1">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ convertDateSys( $complaint->officer_respond_on) ?? ''}}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Masa Pengesahan</label>
                            <div class="col-md-1">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ convertTime($complaint->officer_respond_on) ?? ''}}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Status Aduan Awam</label>
                            <div class="col-md-1">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint->status->complaint_status ?? ''}}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            @if($complaint->complaint_status_id == 2)
                                <label class="col-md-2 col-form-label" for="rejection_reason">Keterangan Penolakan Aduan</label>
                            @elseif($complaint->complaint_status_id == 3)
                                    <label class="col-md-2 col-form-label" for="rejection_reason">Keterangan daripada Pegawai</label>
                            @endif
                            <div class="col-md-1">:</div>
                            <div class="col-md-7">
                                <p class="col-form-label">{{ $complaint->remarks ?? ''}}</p>
                            </div>
                        </div>

                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <a href="{{ route('rulesViolationComplaintApproval.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

                    <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <h5 class="modal-title modal-header">Bukti Aduan </h5>
                                <div class="modal-body">
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
                                        @if ($complaint_attachment -> count() == 0)
                                                <img  src="{{ getNoImages() }}"  alt="no_images" width="auto" height="100px" class="mt-3">
                                        @else
                                            @foreach($complaint_attachment as $attachment)
                                                <img src="{{getCdn().'/'.$attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImages(this);" style="border: 1px solid black"  >
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
<script src="{{ URL::asset('assets/js/pages/RulesViolationComplaintApproval/rulesViolationComplaintApproval.js')}}"></script>
@endsection
