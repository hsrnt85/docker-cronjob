@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input class="form-control" type="hidden" id="id" name="id" value="{{ $userActivityLog->id}}">

                <div class="mb-3 row">
                    <label for="payment_method" class="col-md-2 col-form-label">Nama Pengguna</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->name}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_no" class="col-md-2 col-form-label">Tarikh Log/Masa</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->action_on}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_name" class="col-md-2 col-form-label">Modul</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->module_name}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="payment_method_id" class="col-md-2 col-form-label">Sub Modul</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->submodule_name}}</p>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="payment_category" class="col-md-2 col-form-label">Aktiviti</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->activity}}</p>
                    </div>
                </div>

                {{-- <div class="mb-3 row">
                    <label for="payment_category" class="col-md-2 col-form-label">Sebab</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$userActivityLog->reason}}</p>
                    </div>
                </div><hr> --}}

                {{-- <div class="col-sm-12">
                    <div data-simplebar style="max-height: 1000px;">
                        <table class="table table-bordered wrap w-100" >
                            <thead class="bg-primary bg-gradient text-white">
                                <tr role="row">
                                    <th class="text-center" width="5%"> Bil. </th>
                                    <th class="text-center" width="10%"> Input</th>
                                    <th class="text-center" width="10%"> Data Sebelum </th>
                                    <th class="text-center" width="15%"> Data Selepas </th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($userActivityLog->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="8">Tiada Rekod</td>
                                    </tr>
                                @else
                                @foreach($userActivityLog as $bil => $activity)
                                    <tr>
                                        {{-- <th class="text-center" scope="row">{{ ++$bil }}</th>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{ $activity->data_before }}</td>
                                        <td class="text-center">{{ $activity->data_after }}</td> 
                                    </tr>
                                @endforeach
                            
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div> --}}

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <a href="{{ route('auditTrail.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

