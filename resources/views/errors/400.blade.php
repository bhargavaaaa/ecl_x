@extends('layouts.simple')

@section('title')
    400
@endsection

@section('content')
    <div class="hero bg-body-extra-light">
        <div class="hero-inner">
            <div class="content content-full">
                <div class="py-4 text-center">
                    <div class="display-1 fw-bold text-warning">400</div>
                    <h1 class="fw-bold mt-5 mb-2">Oops.. You just found an error page..</h1>
                    <h2 class="fs-4 fw-medium text-muted mb-5">We are sorry but your request contains bad syntax and cannot
                        be fulfilled..</h2>
                    <a class="btn btn-lg btn-alt-secondary" href="{{ url('/') }}">
                        <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
