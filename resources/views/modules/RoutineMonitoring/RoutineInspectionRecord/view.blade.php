@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Kategori Kuarters (Lokasi)</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $inspection->quarters_category->name }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Alamat Kuarters</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $inspection->address }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Pegawai Pemantau</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $inspection->monitoring_officer->user->name }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Tarikh Pemantauan</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ convertDateSys($inspection->inspection_date) }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Tugasan</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $inspection->remarks }}</p>
                    </div>
                </div>

                @if ($transaction)
                    <hr>

                    <div class="mb-4"><h4 class="card-title">Transaksi Pemantauan Berkala</h4></div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Ulasan </label>
                        <div class="col-md-9">
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $transaction->remarks }}</p>
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
                            <p class="col-form-label">{{ $transaction->inspectionStatus->status }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Ulasan Pengesah </label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $transaction->approval_remarks ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Status </label>
                        <div class="col-md-9">
                            <span class="badge {{($transaction->approvalStatus?->id == 1) ? 'bg-success' : 'bg-danger'}} p-2 font-size-14">
                                {{ $transaction->approvalStatus?->status ?? '-'}}
                            </span>
                        </div>
                    </div>

                    {{-- <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pengesah </label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $transaction->officer->user->name }}</p>
                        </div>
                    </div> --}}

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pengesah </label>
                        <div class="col-md-9">
                            @if($transaction->officer && $transaction->officer->user)
                                <p class="col-form-label">{{ $transaction->officer->user->name }}</p>
                            @else
                                <p class="col-form-label">-</p>
                            @endif
                        </div>
                    </div>
                    
                @endif

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        {{-- <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a> --}}
                        <a href="{{ route('routineInspectionRecord.listInspection', ['category' => $inspection->quarters_category->id]) . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>

                <form method="POST" action="{{ route('quarters.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" >
                    <input type="hidden" name="quarters_cat_id" >
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@if ($attachmentAll)
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
@endif
@endsection

@section('script')
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
