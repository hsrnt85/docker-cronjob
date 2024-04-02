@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('routineInspectionApproval.approval') }}" enctype="multipart/form-data">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">No. Rujukan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->routineInspection->ref_no }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->routineInspection->quarters_category->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat Kuarters</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->routineInspection->address }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tarikh Pemantauan Berkala</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->routineInspection->inspection_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tugasan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->routineInspection->remarks }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4"><h4 class="card-title">Transaksi Pemantauan Berkala</h4></div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Ulasan </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $inspectionTransaction->remarks }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Gambar Pemantauan </label>
                        <div class="col-md-9">
                            <a href="#picture" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon"> 
                                 <span class="tooltip-text">{{ __('button.papar') }}</span>  
                                 <i class="{{ __('icon.view_image') }} "></i><br>
                                 Papar
                            </a>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Status </label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->inspectionStatus->status }}</p>
                        </div>
                    </div>

                    @if($inspectionTransaction->meeting_remarks)
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Keputusan </label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$inspectionTransaction->meeting_remarks }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Ulasan Pengesah </label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->approval_remarks }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Status </label>
                        <div class="col-md-9">
                            <span class="badge {{($inspectionTransaction->approvalStatus->id == 1) ? 'bg-success' : 'bg-danger'}} p-2 font-size-14">
                                {{ $inspectionTransaction->approvalStatus->status }}
                            </span> 
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pengesah </label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspectionTransaction->officer->user->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('routineInspectionApproval.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
                @if ($attachmentAll->isEmpty() || $attachmentAll == null )
                    <p id="clickingText">Tiada gambar dijumpai.</p>
                @else
                    <p id="clickingText">Sila klik gambar di bawah:</p>
                @endif

                <div class="container" >
                    <img id="expandedImg" class="mb-4" >
                    <div id="imgtext"></div>
                </div>
                <div  style="text-align: center;">
                    @if ($attachmentAll->isEmpty() || $attachmentAll == null)
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
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script>
        function showImages(imgs, attachment_id) 
        {
            $('#attachment_id').val(attachment_id);
            var expandImg = document.getElementById("expandedImg");
            var clickingText = document.getElementById("clickingText");
            expandImg.src = imgs.src;
            expandImg.parentElement.style.display = "block";
            clickingText.style.display = "none";
            expandImg.style.width = '100%';
            expandImg.style.height = '300px';
            expandImg.style.border = "1px solid black";
        }
    </script>
@endsection
