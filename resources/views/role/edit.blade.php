@extends('layouts.app')

@section('title')
    Edit Role
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Edit Role
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
                    <form action="{{ route('role.update', $role) }}"
                        method="POST" id="role_form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Name.."
                                    value="{{ old('name', $role->name) }}">
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if ($permissions->count() > 0)
                            <div class="row">
                                @foreach ($permissions->groupby('group_name') as $group => $groups)
                                    <div class="col-12 mb-4">
                                        <div class="block block-themed block-rounded">
                                            <div class="block-header">
                                                <h3 class="block-title">{{ $group }}</h3>
                                            </div>
                                            <div class="block-content">
                                                <div class="row">
                                                    @foreach ($groups->groupby('sub_group_name') as $type => $permission)
                                                        <div class="col-3 mb-4 block-upper-main">
                                                            <div class="block block-themed block-rounded">
                                                                <div class="block-header bg-muted">
                                                                    <label class="block-title" for="{{ Str::slug($group.' '.$type) }}">{{ $type }}</label>
                                                                    <div class="block-options">
                                                                        <input class="form-check-input form-check-header" type="checkbox" id="{{ Str::slug($group.' '.$type) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="block-content">
                                                                    <div class="space-y-2">
                                                                        @foreach ($permission as $key => $value)
                                                                        <div class="form-check">
                                                                                <input class="form-check-input inner-all-checks" type="checkbox" name="permissions[]" @if ($role->permissions->contains($value->id)) checked="checked"
                                                                                @elseif (!empty(old('permissions')) && in_array($value->id, old('permissions'))) checked="checked" @endif id="inlineCheckbox{{ $value->id }}" value="{{ $value->id }}">
                                                                                <label class="form-check-label" for="inlineCheckbox{{ $value->id }}">{{ $value->description }}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
    @include('layouts.includes.validation_scripts')
    <script>
        $(function() {
            Codebase.helpersOnLoad(['jq-validation']);

            jQuery("#role_form").validate({
                rules: {
                    "name": {
                        required: true,
                        remote: '{{ route('role.check-name-unique', $role) }}'
                    }
                },
                messages: {
                    name: {
                        remote: "Name has been already taken."
                    }
                }
            });

            $(document).on('change', '.form-check-header', function() {
                if($(this).prop('checked')) {
                    $(this).closest('.block-upper-main').find('.inner-all-checks').prop('checked', true);
                } else {
                    $(this).closest('.block-upper-main').find('.inner-all-checks').prop('checked', false);
                }
            });
        });
    </script>
@endsection
