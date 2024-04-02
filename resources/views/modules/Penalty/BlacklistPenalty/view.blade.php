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

                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenant->new_ic }}</p>
                        </div>

                    </div>
                    <div class="mb-3 row">
                        <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenant->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenant->phone_no_hp }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $category->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Hilang Kelayakan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ convertDateSys($tenant->blacklist_date) }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ convertDateSys($tenant->initialPenalty->execution_date) }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="description" class="col-md-2 col-form-label">Sebab Hilang Kelayakan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ upperText($tenant->reason?->blacklist_reason) ??   upperText($tenant->blacklist_reason_others)}}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="mv" class="col-md-2 col-form-label">Keputusan Mesyuarat</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $bp->meeting_remarks ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="mv" class="col-md-2 col-form-label">Harga Pasaran Semasa Rumah (RM)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenant->market_rental_amount }}</p>
                        </div>
                    </div>

                    {{-- <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Transaksi</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ convertDateSys($bp->penalty_date) }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">Kadar Denda (%)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $bp->rate->rate }}</p>
                        </div>

                    </div>

                    <div class="mb-3 row">
                        <label for="payment_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $bp->penalty_amount }}</p>
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
                                            {{-- <td class="text-center">{{ getDateDiffByMonth($initialPenaltyDate, $penalty->penalty_date) }}</td> --}}
                                            <td class="text-center">{{ convertDateToMonthYear($penalty->penalty_date) }}</td>
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
                            <a href="{{ route('blacklistPenalty.penaltyList', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function() {
            $(document).on('change', 'select[name="tid"]', function() {
                const selectedOption = $(this).find('option:selected'); // Get the selected option element
                $('#tenant_name').val(selectedOption.data('name'));
                $('#phone_numb').val(selectedOption.data('tel_no'));
                $('#mv').val(selectedOption.data('market-rental'));
            });

            $(document).on('change', 'select#rate', function() {
                const rate = $(this).find('option:selected').text();
                const market_rent = $('#mv').val();
                const penalty = (rate / 100) * market_rent;
                $('input[name="penalty_amount"]').val(penalty);
            });
        })
    </script>
@endsection
