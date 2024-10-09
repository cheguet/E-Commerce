@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <h6>{{Session::get('error')}}</h6>
    </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible">
        <h6>{{Session::get('success')}}</h6>
    </div>
@endif
