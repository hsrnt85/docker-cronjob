@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(2) }}</h4>
                    </div>

                    <form class="custom-validation" id="form" method="post" action="{{ route('blacklistPenalty.update') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $bp->id }}">
                        <input type="hidden" name="tid" value="{{ $tenant->id }}">
                        <input type="hidden" name="quarters_category_id" value="{{ $tenant->quarters_category_id }}">

                        <div class="mb-3 row">
                            <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="{{ $tenant->new_ic }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="{{ $tenant->name }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="{{ $tenant->phone_no_hp }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                            <div class="col-md-10">
                                <input class="form-control" name="quarters_cat" id="quarters_cat" readonly value="{{ $tenant->quarters_category->name }}">
                                <div class="spinner-wrapper"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Hilang Kelayakan</label>
                            <div class="col-md-10">
                                <input class="form-control" readonly value="{{ convertDateSys($tenant->blacklist_date) }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Sebab Hilang Kelayakan</label>
                            <div class="col-md-10">
                                @foreach ($blacklistReasonAll as $reason)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason" id="reason{{ $loop->iteration }}" value="{{ $reason->id }}" required
                                            data-parsley-required-message="{{ setMessage('reason.required') }}" @if ($tenant->blacklist_reason_id == $reason->id) checked @endif>
                                        <label class="form-check-label" for="reason{{ $loop->iteration }}">
                                            {{ $reason->blacklist_reason }}
                                        </label>
                                    </div>
                                @endforeach

                                @error('reason')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="mv" class="col-md-2 col-form-label">Harga Pasaran Semasa Rumah</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="mv" value="{{ $bp->tenant->market_rental_amount }}" readonly>
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Transaksi</label>
                            <div class="col-md-10">
                                <input class="form-control" readonly value="{{ convertDateSys($bp->penalty_date) }}">
                            </div>
                        </div> --}}

                        {{-- <div class="mb-3 row">
                            <label for="ic_numb" class="col-md-2 col-form-label">Kadar Denda (%)</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="rate" id="rate" value="{{ $bp->rate->rate }}" readonly>
                            </div>
                        </div> --}}

                        {{-- <div class="mb-3 row">
                            <label for="payment_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                            <div class="col-md-10">
                                <input class="form-control @error('penalty_amount') is-invalid @enderror" type="text" name="penalty_amount" oninput="checkNumber(this)"
                                    onfocus="focusInput(this);" value="{{ old('penalty_amount', $bp->penalty_amount) }}" readonly>
                                @error('penalty_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">Maklumat Amaun Denda Hilang Kelayakan</h4>
                        </div>
    
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="4%">Bil</th>
                                            <th class="text-center">No. Rujukan Denda</th>
                                            <th class="text-center">Tempoh (Bulan)</th>
                                            <th class="text-center">Harga Pasaran Semasa Rumah (RM)</th>
                                            <th class="text-center">Kadar Denda (%)</th>
                                            <th class="text-center">Amaun Denda (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tenant->penalties as $penalty)
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $penalty->penalty_ref_no }}</td>
                                                <td class="text-center">{{ getDateDiffByMonth($tenant->blacklist_date, $penalty->penalty_date) }}</td>
                                                <td class="text-center">{{ $penalty->market_rental_fee }}</td>
                                                <td class="text-center">{{ $penalty->rate->rate }}</td>
                                                <td class="text-center">{{ $penalty->penalty_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                                <a href="{{ route('blacklistPenalty.penaltyList', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/BlacklistPenalty/blacklist-penalty.js') }}"></script>
@endsection
