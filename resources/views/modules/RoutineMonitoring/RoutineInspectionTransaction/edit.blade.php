@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('routineInspectionTransaction.update') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">No. Rujukan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspection->ref_no }}</p>
                        </div>
                    </div>

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
                        <label for="name" class="col-md-3 col-form-label">Tarikh Pemantauan Berkala</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $inspection->inspection_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tugasan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ upperText($inspection->remarks) }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4"><h4 class="card-title">Transaksi Pemantauan Berkala</h4></div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Ulasan <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            @if ($inspection->inspection_transaction)
                                {{$inspection->inspection_transaction->remarks }}
                            @else
                                <textarea name="remarks" id="remarks" cols="30" rows="5" class="form-control" required data-parsley-required-message="{{ setMessage('catatan.required') }}"></textarea>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Gambar Pemantauan <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            @if ($inspection->inspection_transaction)
                                <a href="#picture" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon mb-2">
                                    <span class="tooltip-text">{{ __('button.papar') }}</span>
                                    <i class="{{ __('icon.view_image') }} "></i><br>
                                    Papar
                                </a>
                            @endif

                            <input type="file" class="form-control" name="gambar[]" multiple
                                required
                                data-parsley-required-message="{{ setMessage('gambar.required') }}"
                                data-parsley-fileextension='png|jpeg|jpg|PNG|JPEG|JPG'
                                data-parsley-fileextension-message="{{ setMessage('gambar.fileextension') }}"
                                data-parsley-max-file-size="2000"
                                data-parsley-max-file-size-message="{{ setMessage('gambar.size') }}">

                            <p class="card-title-desc mt-1">* JPG, JPEG & PNG sahaja | Saiz fail mestilah bawah 2MB</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            @if($inspection->inspection_transaction)
                                <span class="badge {{($inspection->inspection_transaction) ? 'bg-danger' : 'bg-warning'}} p-2 font-size-14">
                                    {{ $inspection->inspection_transaction->inspectionStatus->status }}
                                </span>
                            @else
                                @foreach($statusAll as $i => $status)
                                    <div class="form-check me-2">
                                        <input class="form-check-input me-2" type="radio" id="status_{{ $i}}" name="status"
                                                value="{{ $status->id }}"
                                                required data-parsley-required-message="{{ setMessage('status.required') }}"
                                                data-parsley-errors-container="#parsley-errors-status">
                                        <label class="form-check-label" for="status_{{ $i}}"> {{ capitalizeText($status->status) }} </label>
                                    </div>
                                @endforeach
                                <div id="parsley-errors-status"></div>
                            @endif
                        </div>
                    </div>

                    @if($inspection->inspection_transaction && $inspection->inspection_transaction->inspection_status_id == 2)
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Keputusan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="meeting_remarks" id="meeting_remarks" cols="30" rows="5" class="form-control" required data-parsley-required-message="{{ setMessage('catatan.required') }}"></textarea>
                                <span style="color:#f46a6a">*Sila masukkan tindakan atau hasil keputusan bagi pemantauan ini.</span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pengesahan <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-control" id="pengesah" name="approval_officer"
                                required
                                data-parsley-required-message="{{ setMessage('pengesah.required') }}"
                                @if (!$inspection->inspection_transaction) disabled @endif
                                >
                                    <option value=""> --  Pilih Pegawai Pengesahan -- </option>
                                    @foreach($pengesahAll as $pengesah)
                                        <option value="{{ $pengesah->id }}" {{ ($inspection->approval_officer_id == $pengesah->id) ? 'selected' : '' }}> {{ $pengesah->user->name }} </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambahkuarters">{{ __('button.simpan') }}</button>
                            <a href="{{ route('routineInspectionTransaction.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
        // parsley
        $(function () {
            window.Parsley
                .addValidator('fileextension', function (value, requirement) {
                    var fileExtension = value.split('.').pop();
                    var req = requirement.split('|');

                    return req.includes(fileExtension);
                }, 32)
                .addMessage('en', 'fileextension', 'Fail ini tidak dibenarkan');


            window.Parsley.addValidator('maxFileSize', {
                validateString: function(_value, maxSize, parsleyInstance) {
                if (!window.FormData) {
                    alert('You are making all developpers in the world cringe. Upgrade your browser!');
                    return true;
                }
                    var files = parsleyInstance.$element[0].files;
                    return files.length != 1  || files[0].size <= maxSize * 1024;
                },
                requirementType: 'integer',
            });


            setPengesahInput();

            $(document).on('change', 'input[name="status"]', function(){
                setPengesahInput();
            });

            function setPengesahInput()
            {
                const status = $('input[name="status"]:checked').val();

                if(typeof status === 'undefined') return;

                const is_required = (status == 1);
                const is_disabled = (!is_required);

                const pengesah = $('#pengesah');
                pengesah.prop('required', is_required);
                pengesah.prop('disabled', is_disabled);
            }
        });

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
