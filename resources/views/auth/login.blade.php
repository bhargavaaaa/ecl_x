@extends('layouts.simple')

@section('title')
    Login
@endsection

@section('content')
    <div class="bg-body-dark">
        <div class="row mx-0 justify-content-center">
            <div class="hero-static col-lg-6 col-xl-4">
                <div class="content content-full overflow-hidden">
                    <!-- Header -->
                    <div class="py-4 text-center">
                        <a class="fw-bold" href="{{ url('/') }}"><span class="smini-hidden">
                                <img src="{{ asset("media/photos/j_s_logo.png") }}" width="250"/>
                            </span></a>
                        <h1 class="h3 fw-bold mt-4 mb-2">Welcome to Your Dashboard</h1>
                        <h2 class="h5 fw-medium text-muted mb-0">It’s a great day today!</h2>
                    </div>
                    <form class="js-validation-signin" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="block block-themed block-rounded block-fx-shadow">
                            <div class="block-header bg-gd-dusk">
                                <h3 class="block-title">Please Sign In</h3>
                            </div>
                            <div class="block-content">
                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter your email or phone"
                                        value="{{ old('email') }}">
                                    <label class="form-label" for="email">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter your password">
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-sm-flex align-items-center push">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="remember"
                                                name="remember">
                                            <label class="form-check-label" for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end push">
                                        <button type="submit" class="btn btn-lg btn-alt-primary fw-medium">
                                            Sign In
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <div
                                    class="block-content block-content-full bg-body-light text-center d-flex justify-content-between">
                                    <a class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block"
                                        href="{{ route('password.request') }}">
                                        Forgot Password
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                    <!-- END Sign In Form -->
                </div>
            </div>
        </div>
    </div>
@endsection
