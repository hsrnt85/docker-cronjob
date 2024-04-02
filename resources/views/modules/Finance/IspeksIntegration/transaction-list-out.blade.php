@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('ispeksIntegrationOutgoing.process') }}" >
                    {{ csrf_field() }}

                    @if(checkPolicy("A") )
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <button type="submit" name="btnSubmit"  value="process" class="btn btn-success float-end waves-effect waves-light">{{ __('button.proses_integrasi') }}</button>
                            </div>
                        </div>
                    @endif

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable indextable" role="grid" >
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="5%">Bil</th>
                                            <th class="text-center" width="25%">Nama Fail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resitPerbendaharaanList as $data)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $data['nama_fail'] }}</td>
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
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

