@extends('admin.layouts.master')
@push('link')
<link rel="stylesheet" href="{{ asset('assets/jquery-toast-plugin/jquery.toast.min.css') }}" />
@endpush
@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="header-title">{{ __('Total Debited') }}</h4><br>
                </div>
                <div>
                    <form class="form-validate" action="{{ route('admin.expense.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="container">
                            <div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Staff/CEO Name <span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Comments <span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="comments" id="comments" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Amount<span class="requiredStar"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="amount" id="amount" required>
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
                <div class="table-responsive" id="printSection">
                    <!-- <div class="text-center mt-4">
                        <h4 style="text-align:center;">Dhoya Restaurant</h4>
                        <h5 style="text-align:center;">RC Street, Court Para</h5>
                        <h5 style="text-align:center;">Kushtia(+8801873690534)</h5>
                        <h5 style="text-align:center;" id="filterDate"></h5>
                    </div> -->
                    <table id="report-table" class="table table-dark">
                        <thead>
                            <tr>
                                <th>Date</th> 
                                <th style="text-align:center">Debit Type</th>
                                <th style="text-align:center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenseDate as $value)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                    <td style="text-align:center">{{$value->comments}}</td>
                                    <td style="text-align:center">{{$value->amount}}</td>   
                                </tr>
                            @endforeach
                                <tr>
                                    <td><h6>Total</h6></td>
                                    <td style="text-align:center"><span> </span></td>
                                    <td style="text-align:center"><span>৳ </span>{{$otherExpenseTotal}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <div><h5>Total Dedited: <span id="total">৳ </span><bold style="background: whitesmoke">{{$deditedTotal}}</bold></h5></div>
                    <div class="endPrint"  style="font-size:12px;text-align:center">
                        <!-- <p>Powered by DIGITAL INNOVATION</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
        .header-title{
            color: #213166;
        }
        .btn-primary{
            border-radius: 6px;
        }
        .btn-default:hover{
            color: black;
        }
        .action{
            padding-left: 22px!important;
        }
        .text-center{
            text-align: center;
            display: inherit!important;
        }
        @page {
            width: 3in;
            /* size: 80mm 297mm;  */
            margin: 20px;
            font-size: 10pt;
            font-family: Tahoma, Verdana, Segoe, sans-serif;
        }
        .printDiv{
            width: 3in;
            /* size: 80mm 297mm; 
            margin: 20px; */
            font-size: 10pt;
            font-family: Tahoma, Verdana, Segoe, sans-serif;
        }
        .datepicker{
            border: 1px solid cadetblue;
        }
        #wrapper .select2-container--default .select2-selection--single .select2-selection__rendered{
            width: 100px;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
@endpush

@push('script')

@endpush