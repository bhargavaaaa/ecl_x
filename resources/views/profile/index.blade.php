@extends('layouts.app')
@section('title')
    Update Profile
@endsection
@section('css')
    @include('layouts.includes.select2_styles')
@endsection
@section('content')
    <div class="bg-image bg-image-bottom" style="background-image: url('{{ asset('media/photos/photo13@2x.jpg') }}');">
        <div class="bg-black-75 py-4">
            <div class="content content-full text-center">
                <!-- Avatar -->
                <div class="mb-3">
                    <a class="img-link" href="javascript:void(0)">
                        <img class="img-avatar img-avatar96 img-avatar-thumb object-fit-cover"
                             src="{{ getUserAvatar() }}" alt="">
                    </a>
                </div>
                <!-- END Avatar -->
                <!-- Personal -->
                <h1 class="h3 text-white fw-bold mb-2">{{ auth()->user()->name }}</h1>
                <h2 class="h5 text-white-75">
                    {{ auth()->user()->roles->value('name') }}
                </h2>
                <!-- END Personal -->
            </div>
        </div>
    </div>
    <div class="content">
        <!-- User Profile -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-user-circle me-1 text-muted"></i> User Profile
                </h3>
            </div>
            <div class="block-content">
                <form action="{{ route('profile.update', ['profile' => auth()->user()->id]) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Your account’s vital information.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="mb-4">
                                <label class="form-label" for="name">Name</label>
                                <input type="text"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name" placeholder="Enter your name.."
                                       value="{{ old('name', auth()->user()->name) }}">
                                @error('name')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email" placeholder="Enter your email.."
                                       value="{{ old('email', auth()->user()->email) }}">
                                @error('email')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text"
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone" placeholder="Enter your phone.."
                                       value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row mb-4">
                                <div class="col-4">
                                    <label class="form-label" for="country_id">Country</label>
                                    <select class="js-select2 form-select @error('country_id') is-invalid @enderror"
                                            id="country_id" name="country_id" data-placeholder="Select Country..">
                                        <option></option>
                                        @foreach (getCountries() as $key => $country)
                                            <option value="{{ $key }}"
                                                    @if (old('country_id', auth()->user()->country_id) == $key) selected @endif>{{ $country }}</option>
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
                                        @if (old('country_id', auth()->user()->country_id))
                                            @foreach (getStates(old('country_id', auth()->user()->country_id)) as $key => $state)
                                                <option value="{{ $key }}"
                                                        @if (old('state_id', auth()->user()->state_id) == $key) selected @endif>{{ $state }}
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
                                        @if (old('state_id', auth()->user()->state_id))
                                            @foreach (getCities(old('state_id', auth()->user()->state_id)) as $key => $city)
                                                <option value="{{ $key }}"
                                                        @if (old('city_id', auth()->user()->city_id) == $key) selected @endif>{{ $city }}
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
                                    <textarea
                                        class="form-control form-control-lg @error('address') is-invalid @enderror"
                                        id="address"
                                        name="address">{!! old('address', auth()->user()->address) !!}</textarea>
                                    @error('address')
                                    <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="push">
                                        <img class="img-avatar object-fit-cover" src="{{ getUserAvatar() }}" alt="">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="avatar">Choose
                                            new avatar</label>
                                        <input class="form-control @error('avatar') is-invalid @enderror" type="file"
                                               id="avatar" name="avatar">
                                        @error('avatar')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END User Profile -->
        <!-- Change Password -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-asterisk me-1 text-muted"></i> Change Password
                </h3>
            </div>
            <div class="block-content">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Changing your sign in password is an easy way to keep your account secure.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="mb-4">
                                <label class="form-label" for="current_password">Current Password</label>
                                <input type="password"
                                       class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password"
                                       value="{{ old('current_password') }}">
                                @error('current_password')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="password">New Password</label>
                                <input type="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       id="password" name="password" value="{{ old('password') }}">
                                @error('password')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">Confirm New
                                    Password</label>
                                <input type="password"
                                       class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation"
                                       value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Change Password -->
    </div>
@endsection
@section('js')
    @include('layouts.includes.select2_scripts')
    <script>
        $(function () {
            Codebase.helpersOnLoad(['jq-select2']);
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
            $(document).on("change", "#country_id2", function () {
                let $idVal = $(this).val();
                if ($idVal) {
                    $("#state_id2").html('<option></option>').change();
                    $("#city_id2").html('<option></option>').change();
                    jQuery.ajax({
                        url: "{{ route('globals.states') }}",
                        method: 'get',
                        data: {
                            id: $idVal,
                        },
                        success: function (result) {
                            addSelectData($("#state_id2"), result.data);
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
            $(document).on("change", "#state_id2", function () {
                let $idVal = $(this).val();
                if ($idVal) {
                    $("#city_id2").html('<option></option>').change();
                    jQuery.ajax({
                        url: "{{ route('globals.cities') }}",
                        method: 'get',
                        data: {
                            id: $idVal,
                        },
                        success: function (result) {
                            addSelectData($("#city_id2"), result.data);
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
            selector.empty().trigger("change");
            selector.select2({
                data: data
            });
            selector.select2('val', 0);
        }
    </script>
@endsection
