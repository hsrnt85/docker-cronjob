@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('routineInspectionRecord.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="quarters_category_id" value="{{ $category->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="quarters_category" name="quarters_category" value="{{ $category->name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Alamat Kuarters <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select select2" id="address" name="address"
                            required
                            data-parsley-required-message="{{ setMessage('address_1.required') }}"
                            data-parsley-group="group1">
                                <option value=""> --  Pilih Alamat -- </option>
                                @foreach($addressAll as $address)
                                    <option value="{{ $address->address_1 }}"> {{ $address->address_1 }} </option>
                                @endforeach
                            </select>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pemantau <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select select2" id="pemantau" name="pemantau" required data-parsley-required-message="{{ setMessage('pemantau.required') }}">
                                <option value=""> --  Pilih Pegawai Pemantau -- </option>
                                @foreach($pemantauAll as $pemantau)
                                    <option value="{{ $pemantau->id }}"> {{ $pemantau->user->name }} </option>
                                @endforeach
                            </select>
                            @error('pemantau')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tarikh Pemantauan</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input class="form-control datepicker" data-provide="datepicker" placeholder="dd/mm/yyyy" type="text" id="tarikh_pemantauan" name="tarikh_pemantauan"
                                required
                                data-parsley-required-message="{{ setMessage('tarikh_pemantauan.required') }}"
                                data-parsley-checkaddress="1"
                                data-parsley-group="group1"
                                data-parsley-trigger="change"
                                data-route="{{ route('routineInspectionRecord.ajaxcheckalamat') }}"
                                > <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tugasan</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="catatan" required data-parsley-required-message="{{ setMessage('catatan.required') }}"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambahkuarters">{{ __('button.simpan') }}</button>
                            <a href="{{ route('routineInspectionRecord.listInspection', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script>
    $(function () {

        let checkAddressValidator = function(value, requirement) {
            let addr = $("#address").val();
            let date = $("#tarikh_pemantauan").val();
            let url  = $("#tarikh_pemantauan").data('route');
            let _token  = $('meta[name="csrf-token"]').attr('content');
            var isValid = false;
            console.log('checkAddressValidator');

            $.ajax({
                url: url,
                type:"POST",
                data:{
                    address:addr,
                    date:date,
                    _token: _token
                },
                async:false,
            }).done(function(data, textStatus, jqXHR){

                isValid = (data.count == 0);
            });

            window.Parsley.addMessage('en', 'checkaddress', addMessageFunction());

            return isValid;
        };

        let addMessageFunction = function() {
            let date = $("#tarikh_pemantauan").val();

            if (date === '') {
                return 'Sila pilih tarikh';
            } else {
                return 'Alamat ini sudah dipilih untuk tarikh ' + date;
            }
        };

        window.Parsley.addValidator('checkaddress', checkAddressValidator, 32);
        window.Parsley.addMessage('en', 'checkaddress', addMessageFunction());
    });
    </script>
@endsection
