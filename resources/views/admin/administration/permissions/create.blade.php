@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Permission</a></li>
                <li class="breadcrumb-item active">Add Permission</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Add Permission</h4>
                <form class="forms-sample" id="ic_permission_add" action="{{route('admin.permissions.store')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-form-label">Group Name</label><span class="text-danger"><strong>*</strong></span>
                            <div>
                                <input type="text" id="group_name" class="form-control" name="group_name"
                                    placeholder="Enter permission group name" required>
                            </div>
                            @error('group_name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-form-label">Permission Name</label><span class="text-danger"><strong>*</strong></span>
                            <div>
                                <input type=" text" id="name" value="{{ old('name') }}" class="form-control" name="name"
                                    placeholder="Enter permission name" required>
                            </div>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <div>
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.roles.index') }}">
                                <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>


@endsection


@push('script')

@endpush

@push('style')

@endpush