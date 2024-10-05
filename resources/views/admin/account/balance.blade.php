@extends('admin.layouts.master')
@push('link')

@endpush
@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="header-title">{{ __('Balanced') }}</h4><br>
                </div>
                <div class="row mt-4 ml-4 mb-4 no-print">
                    <form class="form-inline" id="filter-form">
                        <!-- @csrf -->
                        <label for="start_date">From Date: </label>
                        <input type="text" class="form-control datepicker ml-3" autocomplete="off" id="from_date" name="start_date" required>          
                        <label for="start_date" class="ml-4">To Date: </label>
                        <input type="text" class="form-control datepicker ml-3" autocomplete="off" id="to_date" name="end_date" required>          
                        
                        <button type="submit" class="btn btn-info ml-3">Show</button>
                    </form>
                    <!-- <button id="printBtn" class="btn btn-success ml-4">Print Report</button> -->
                </div>
                <div>
                    <h4 style="text-align:center">Balanced: {{$creditedTotal - $deditedTotal}}/-</h4>
                </div>
                <div class="form-inline">
                    <div class="col-sm-6 table-responsive" id="printSection">
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
                                    <th style="text-align:center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderDate as $value)
                                    <tr>
                                        <td>{{ date('d-m-Y',strtotime($value->date)) }}</td>
                                        <td style="text-align:center">
                                            @php $sum=0; @endphp
                                            @foreach($orderData as $data)
                                                
                                                @if($value->date==$data->date2)
                                                    @php $sum = $sum + $data->grand_total; @endphp
                                                @endif
                                            @endforeach
                                            {{$sum}} 
                                        </td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td><h6>Total</h6></td>
                                        <td style="text-align:center"><span>৳ </span>{{$newTotal}}</td>
                                    </tr>
                            </tbody>
                        </table>
                        <div><h5>Total Credited: <span id="total">৳ </span><bold style="background: whitesmoke">{{$creditedTotal}}</bold></h5></div>
                        <div class="endPrint"  style="font-size:12px;text-align:center">
                            <!-- <p>Powered by DIGITAL INNOVATION</p> -->
                        </div>
                    </div>

                    <div class="col-sm-5 table-responsive" id="printSection" style="margin-left: auto;margin-top: -20px;">
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
                                    <th style="text-align:center">Kitchen</th>
                                    <th style="text-align:center">Staff Salary</th>
                                    <th style="text-align:center">Others</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenseDate as $value)
                                    <tr>
                                        <td>{{ date('d-m-Y',strtotime($value->date)) }}</td>
                                        <td style="text-align:center">
                                            @php $sum=0; @endphp
                                            @foreach($expenseData as $data)
                                                
                                                @if($value->date==$data->date2 && $data->comments=="Kitchen")
                                                    @php $sum = $sum + $data->amount; @endphp
                                                @endif
                                            @endforeach
                                            {{$sum}} 
                                        </td>
                                        <td style="text-align:center">
                                            @php $sum=0; @endphp
                                            @foreach($expenseData as $data)
                                                
                                                @if($value->date==$data->date2 && $data->comments=="Staff Salary")
                                                    @php $sum = $sum + $data->amount; @endphp
                                                @endif
                                            @endforeach
                                            {{$sum}} 
                                        </td>
                                        <td style="text-align:center">
                                            @php $sum=0; @endphp
                                            @foreach($expenseData as $data)
                                                
                                                @if($value->date==$data->date2 && $data->comments!="Staff Salary" && $data->comments!="Kitchen")
                                                    @php $sum = $sum + $data->amount; @endphp
                                                @endif
                                            @endforeach
                                            {{$sum}} 
                                        </td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td><h6>Total</h6></td>
                                        <td style="text-align:center"><span>৳ </span>{{$kitchenExpenseTotal}}</td>
                                        <td style="text-align:center"><span>৳ </span>{{$staffExpenseTotal}}</td>
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