@extends('admin.layouts.master')

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
                <h4 class="header-title">{{ __('Edit Staff') }}</h4>

                <form class="form-validate" action="{{ route('admin.staff.update', $staff->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="container">
                        <div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="name" id="name" value="{{$staff->name}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Father's Name <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="father_name" id="father_name" value="{{$staff->father_name}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="mobile" id="mobile" value="{{$staff->mobile}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">NID No.</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="nid_no" value="{{$staff->nid_no}}"  id="nid_no">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Address <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="address" id="address" value="{{$staff->address}}">{{$staff->address}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Role <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="role" id="role">
                                        <option value="">Select one..</option>
                                        <option value="Manager" {{$staff->role == 'Manager'?'selected':''}}>Manager</option>
                                        <option value="Employee" {{$staff->role == 'Employee'?'selected':''}}>Employee</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Salary <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="salary" value="{{$staff->salary}}" id="salary"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Joining Date <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" name="joinning_date" id="joinning_date" value="{{$staff->joinning_date}}"/>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Image:</label>
                                <div class="ml-3">
                                    <label class="custom-file-upload">
                                        <input type="file" name="image" id="file-input">Upload File
                                        <i class="fas fa-cloud-upload-alt" style="color:green"></i> 
                                    </label>
                                </div>    
                            </div> -->
                            <div class="form-group row">
                                <label for="lastName" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-1">
                                    <input class="form-control" type="checkbox" id="status" name="status" value="Active" {{  ($staff->status == 'Active' ? ' checked' : '') }}>
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
    </style>
@endpush

@push('script')
@endpush
