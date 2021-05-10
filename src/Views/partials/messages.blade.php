@if(session('message'))
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i>
        {{ session('message') }}
    </div>
@endif
