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
                <h4 class="header-title">{{ __('Add Staff Salary') }}</h4>

                <form class="form-validate" action="{{ route('admin.staffSalary.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Select Staff <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="staff_id" id="staff_id">
                                        <option value="">Select one..</option>
                                        @foreach($staffList as $list)
                                            <option value="{{$list->id}}">{{$list->name}}-{{$list->mobile}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Salary <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="salary" id="salary" required value="0.00" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Select Month <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="month" id="month" required>
                                        <option value="">Select one..</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Payable <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="payable" id="payable" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Select Payment Method <span class="requiredStar"> *</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="payment_method" id="payment_method" required>
                                        <option value="">Select one..</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Bank">Bank</option>
                                    </select>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('#staff_id').on('change', function () {
                var staffId = $(this).val();

                if (staffId) {
                    $.ajax({
                        url: '/admin/staff/list/staff-salary/getSalary/' + staffId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            // console.log(data);
                            $('#salary').val(data);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('Error:', textStatus, errorThrown);
                        }
                    });
                } else {
                    $('#salary').val('0.00');
                }
            });
        });
    </script>
@endpush
