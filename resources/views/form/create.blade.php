@extends('layouts.app')

@section('title')
    Add Form
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Add Form
                        </h3>
                    </div>
                    <div class="col-6 text-end">
                        <a href="javascript:history.back()" class="btn btn-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <form action="{{ route('form.store') }}" method="POST" id="form_form"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="name">Name (Only - is allowed as special character)</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" placeholder="Name.." value="{{ old('name') }}">
                                @error('name')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save & Continue</button>
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('layouts.includes.validation_scripts')
    <script>
        $(function () {
            Codebase.helpersOnLoad(['jq-validation']);

            jQuery("#form_form").validate({
                rules: {
                    "name": {
                        required: true,
                        remote: '{{ route('form.check-name-unique') }}'
                    }
                },
                messages: {
                    name: {
                        remote: "Name has been already taken."
                    }
                }
            });
        });
    </script>
@endsection
