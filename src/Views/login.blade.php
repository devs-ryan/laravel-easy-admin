@extends('easy-admin::layouts.auth')


@section('title', env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin'))

@section('content')
    <div class="px-md-5 py-md-3">
        <div class="jumbotron pt-3">
            <h3>
                <i class="fas fa-door-open"></i>
                {{ env('EASY_ADMIN_APP_NAME', 'Welcome') }}
            </h3>
            <p class="lead"><i>Please log in using the form below:</i></p>
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </h3>
                            <hr>
                            <form action="/easy-admin/login" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" placeholder="Enter email">
                                    <small class="form-text text-danger">
                                        {{ $email_error ?? '' }}
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" placeholder="Password">
                                    <small class="form-text text-danger">
                                        {{ $password_error ?? '' }}
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="far fa-check-square"></i> Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection