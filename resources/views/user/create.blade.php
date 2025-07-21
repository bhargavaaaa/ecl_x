@extends('layouts.app')

@section('title')
    Add User
@endsection

@section('css')
    @include('layouts.includes.select2_styles')
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Add User
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
                    <form action="{{ route('user.store') }}" method="POST" id="user_form"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" placeholder="Name.." value="{{ old('name') }}">
                                @error('name')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" placeholder="Email.." value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" placeholder="Phone.." value="{{ old('phone') }}">
                                @error('phone')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Password.."
                                       value="{{ old('password') }}">
                                @error('password')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-4">
                                <label class="form-label" for="country_id">Country</label>
                                <select class="js-select2 form-select @error('country_id') is-invalid @enderror"
                                        id="country_id" name="country_id" data-placeholder="Select Country..">
                                    <option></option>
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $key }}"
                                                @if (old('country_id') == $key) selected @endif>{{ $country }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="state_id">State</label>
                                <select class="js-select2 form-select @error('state_id') is-invalid @enderror"
                                        id="state_id" name="state_id" data-placeholder="Select State..">
                                    <option></option>
                                    @if (old('country_id'))
                                        @foreach (getStates(old('country_id')) as $key => $state)
                                            <option value="{{ $key }}"
                                                    @if (old('state_id') == $key) selected @endif>{{ $state }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('state_id')
                                <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="city_id">City</label>
                                <select class="js-select2 form-select @error('city_id') is-invalid @enderror"
                                        id="city_id" name="city_id" data-placeholder="Select City..">
                                    <option></option>
                                    @if (old('state_id'))
                                        @foreach (getCities(old('state_id')) as $key => $city)
                                            <option value="{{ $key }}"
                                                    @if (old('city_id') == $key) selected @endif>{{ $city }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('city_id')
                                <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address"
                                          placeholder="Address..">{!! old('address') !!}</textarea>
                                @error('address')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="role_id">Role</label>
                                <select class="js-select2 form-select @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" data-placeholder="Select Role..">
                                    <option></option>
                                    @foreach ($roles as $key => $role)
                                        <option value="{{ $key }}"
                                                @if (old('role_id') == $key) selected @endif>{{ $role }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                           @if (old('status', true)) checked @endif>
                                    <label class="form-check-label" for="status">Status</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('layouts.includes.select2_scripts')
    @include('layouts.includes.validation_scripts')
    <script>
        $(function () {
            Codebase.helpersOnLoad(['jq-validation', 'jq-select2']);

            jQuery("#user_form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "email": {
                        required: true,
                        email: true,
                        remote: '{{ route('user.check-email-unique') }}'
                    },
                    "phone": {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        remote: '{{ route('user.check-phone-unique') }}'
                    },
                    "password": {
                        required: true,
                        min: 6
                    },
                    "role_id": {
                        required: true
                    }
                },
                messages: {
                    email: {
                        remote: "Email has been already taken."
                    },
                    phone: {
                        remote: "Phone has been already taken."
                    }
                }
            });

            $(document).on("change", "#country_id", function () {
                let $idVal = $(this).val();
                if ($idVal) {
                    $("#state_id").html('<option></option>').change();
                    $("#city_id").html('<option></option>').change();

                    jQuery.ajax({
                        url: "{{ route('globals.states') }}",
                        method: 'get',
                        data: {
                            id: $idVal,
                        },
                        success: function (result) {
                            addSelectData($("#state_id"), result.data);
                        }
                    });
                }
            });

            $(document).on("change", "#state_id", function () {
                let $idVal = $(this).val();
                if ($idVal) {
                    $("#city_id").html('<option></option>').change();

                    jQuery.ajax({
                        url: "{{ route('globals.cities') }}",
                        method: 'get',
                        data: {
                            id: $idVal,
                        },
                        success: function (result) {
                            addSelectData($("#city_id"), result.data);
                        }
                    });
                }
            });
        });

        function addSelectData(selector, objects) {
            let data = $.map(objects, function (obj, key) {
                return {
                    id: key,
                    text: obj
                };
            });
            console.log(data);

            selector.empty().trigger("change");
            selector.select2({
                data: data
            });
            selector.select2('val', 0);
        }
    </script>
@endsection
