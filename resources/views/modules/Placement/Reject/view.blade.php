@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form>
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ 'ss' }}">

                    {{-- SECTION - APPLICATION INFO --}}
                    @include('modules.Placement.ApplicationInfo.view-application-info')
                    {{-- SECTION - APPLICATION INFO --}}

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <a href="{{ route('reject.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

{{-- @section('script')
<script src="{{ URL::asset('assets/js/pages/Placement/placement.js')}}"></script>
@endsection --}}
