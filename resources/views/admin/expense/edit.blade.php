@extends('admin.layouts.master')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('Service') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.services') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('Add Category') }}</h4>

                <form class="form-validate" action="{{ route('admin.expense.update', $expenses->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container">
                            <div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Staff/CEO Name <span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="name" id="name" value="{{$expenses->name}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Comments <span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="comments" id="comments" value="{{$expenses->comments}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Amount<span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="amount" id="amount" value="{{$expenses->amount}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-7 submit">
                                        <button type="submit" class="btn btn-secondary btn-submit">Update</button>
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
        .custom-file-upload {
            display: inline-block;
            position: relative;
            cursor: pointer;
            padding: 10px 20px; 
            background-color: #fff; 
            color: black; 
            border-radius: 5px; 
            text-align: center;
            overflow: hidden;
            border: 1px solid #d7ddf5;
        }

        .custom-file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .custom-file-upload i {
            margin-right: 5px; 
        }

        .requiredStar{
            color: red;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            border: none;
        }

        .btn-default:hover{
            color: #333 !important;
            background-color: white!important;
            border: none!important;
            border-color: white!important;
        }

        .form-group .bootstrap-select.btn-group{
            border: 1px solid #ced4da;
        }
        
        .dropdown-toggle::after{
            display: none!important;
        }

        .list-inline>li{
            display: none!important;
        }
    </style>
@endpush

@push('script')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                // Customize Select2 options here
            });
        });
    </script>
@endpush
