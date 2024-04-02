@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('ispeksIntegrationIncoming.process') }}" >
                    {{ csrf_field() }}

                    @if(checkPolicy("A") )
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <button type="submit" name="btnSubmit"  value="process" class="btn btn-success float-end waves-effect waves-light">{{ __('button.proses_integrasi') }}</button>
                            </div>
                        </div>
                    @endif
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" >
                            <a class="nav-link active" data-bs-toggle="tab" href="#penyata-pemungut" role="tab" >Penyata Pemungut</a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link" data-bs-toggle="tab" href="#jurnal" role="tab" >Jurnal</a>
                        </li>
                    </ul>    <br>

                    <div class="tab-content">
                        <div class="tab-pane active" id="penyata-pemungut" role="tabpanel">
                            <div id="datatable_wrapper">

                                <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable indextable" role="grid" >
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="5%">Bil</th>
                                            <th class="text-center" width="25%">No. Penyata Pemungut</th>
                                            <th class="text-center" width="25%">Tarikh Penyata Pemungut</th>
                                            <th class="text-center" width="20%">Tempoh Pungutan</th>
                                            <th class="text-center" width="10%">Jumlah Kutipan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($collectorStatementList as $bil => $data)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $data->collector_statement_no }}</td>
                                                <td class="text-center">{{ convertDateSys($data->collector_statement_date) }}</td>
                                                <td class="text-center">{{ convertDateSys($data->collector_statement_date_from) }} - {{ convertDateSys($data->collector_statement_date_to) }}</td>
                                                <td class="text-center">{{ numberFormatComma($data->collection_amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="jurnal" role="tabpanel">
                            <div id="datatable_wrapper">

                                <table id="datatable2" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="5%">Bil</th>
                                            <th class="text-center" width="25%">No. Jurnal Pelarasan</th>
                                            <th class="text-center" width="25%">Tarikh Jurnal</th>
                                            <th class="text-center" width="20%">Amaun Debit</th>
                                            <th class="text-center" width="20%">Amaun Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($journalList as $data)
                                            <tr>
                                                <td class="text-center" tabindex="0"  width="5%">{{ $loop->iteration }}</td>
                                                <td class="text-center" width="25%">{{ $data->journal_no }}</td>
                                                <td class="text-center" width="25%">{{ convertDateSys($data->journal_date) }}</td>
                                                <td class="text-center" width="20%">{{ numberFormatComma($data->amaun_debit) }}</td>
                                                <td class="text-center" width="20%">{{ numberFormatComma($data->amaun_kredit) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

