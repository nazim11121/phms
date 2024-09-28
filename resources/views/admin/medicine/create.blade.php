@extends('admin.layouts.master')
@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('medicine') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.medicine') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __t('add_medicine') }}</h4>

                <form class="form-validate" action="{{ route('admin.medicine.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Medicine Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Group Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="group_id" id="group_id" required>
                                        <option value="">Select One...</option>
                                        @foreach($groups as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Brand Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="brand_id" id="brand_id" required>
                                        <option value="">Select One...</option>
                                        @foreach($brands as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Type <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="type_id" id="type_id" required>
                                        <option value="">Select One...</option>
                                        @foreach($types as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Suplier</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="suplier_id" id="suplier_id">
                                        <option value="">Select One...</option>
                                        @foreach($supliers as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Quantity <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="quantity" id="quantity" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Buying Price</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="buying_price" id="buying_price">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Selling Price <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="selling_price" id="selling_price" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Expired Date </label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="expired_date" id="expired_date">
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
