@extends('admin.layouts.master')
@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('brand') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.brand') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __t('add_type') }}</h4>

                <form class="form-validate" action="{{ route('admin.type.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Type Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-7 submit">
                                    <button type="submit" class="btn btn-secondary btn-submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
    <style>
        .submit{
            text-align: center;
        }
        .btn-submit{
            width: 220px;
        }
        .requiredStar{
            color: red;
        }
        .btn-default:hover{
            color: #333 !important;
            background-color: white!important;
            border: none!important;
            border-color: white!important;
        }
    </style>
@endpush

@push('script')
@endpush
