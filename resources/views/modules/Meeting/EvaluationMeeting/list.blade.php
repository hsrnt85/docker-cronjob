@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th width="12%">Bil. Mesyuarat</th>
                                        <th width="10%">Tarikh</th>
                                        <th width="8%">Masa</th>
                                        <th width="35%">Tujuan</th>
                                        <th width="18%">Tempat</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($meetingAll as $bil => $meeting)
                                        <tr>
                                            <th class="text-center">{{ ++$bil }}</th>
                                            <td>{{ $meeting -> bil_no }}</a></td>
                                            <td>{{ convertDateSys($meeting -> date) }}</a></td>
                                            <td>{{ convertTime($meeting -> time) }}</a></td>
                                            <td>{{ $meeting -> purpose }} </a></td>
                                            <td>{{ $meeting -> venue }} </a></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("U") && !$meeting ->is_done)
                                                        <a href="{{ route('evaluationMeeting.edit', ['id' => $meeting->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('evaluationMeeting.edit', ['id' => $meeting->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.open_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
