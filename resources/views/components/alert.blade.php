@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show px-3 mb-2" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div>
            <h5 class="text-success">Berjaya</h5>
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show px-3 mb-2" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div>
            <h5 class="text-danger">Tidak Berjaya</h5>
            <p>{{ Session::get('error') }}</p>
        </div>
    </div>
@endif

@if(Session::has('error-permission'))
    <div class="alert alert-danger alert-dismissible fade show px-3 mb-2" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div>
            <h5 class="text-danger">Tidak Berjaya</h5>
            <p>@lang('restriction.'.Session::get('error-permission'))</p>
        </div>
    </div>
@endif


@if(Session::has('error'))
        <input type="hidden" name="error" value="{{ Session::get('error') }}">
@endif
@if(Session::has('success'))
    <input type="hidden" name="success" value="{{ Session::get('success') }}">
@endif
@if(Session::has('error-permission'))
        <input type="hidden" name="error-permission" value="@lang('restriction.'.Session::get('error-permission'))">
@endif
