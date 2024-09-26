@extends('admin.layouts.master')
@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('suplier') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.suplier') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __t('add_suplier') }}</h4>

                <form class="form-validate" action="{{ route('admin.suplier.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Suplier Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Brand Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="brand_name" id="brand_name">
                                        <option value="">Select One...</option>
                                        @foreach($brands as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="mobile" id="mobile" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" name="email" id="email">
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
