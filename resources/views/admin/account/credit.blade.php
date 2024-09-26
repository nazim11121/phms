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
                    <h4 class="header-title">{{ __('Total Credited') }}</h4><br>
                </div>
                <div id="printSection">
                    <!-- <div class="text-center mt-4">
                        <h4 style="text-align:center;">Dhoya Restaurant</h4>
                        <h5 style="text-align:center;">RC Street, Court Para</h5>
                        <h5 style="text-align:center;">Kushtia(+8801873690534)</h5>
                        <h5 style="text-align:center;" id="filterDate"></h5>
                    </div> -->
                    <table id="report-table" class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th style="text-align:center">New Order</th>
                                <th style="text-align:center">Table Order</th>
                                <th style="text-align:center">Parcel Order</th>
                                <th style="text-align:center">Delivery Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDate as $value)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                    <td style="text-align:center">
                                        @php $sum=0; @endphp
                                        @foreach($orderData as $data)
                                            
                                            @if($value->date==$data->date2 && $data->order_type=="New Order")
                                                @php $sum = $sum + $data->grand_total; @endphp
                                            @endif
                                        @endforeach
                                        {{$sum}} 
                                    </td>
                                    <td style="text-align:center">
                                        @php $sum=0; @endphp
                                        @foreach($orderData as $data)
                                            
                                            @if($value->date==$data->date2 && $data->order_type=="Table Order")
                                                @php $sum = $sum + $data->grand_total; @endphp
                                            @endif
                                        @endforeach
                                        {{$sum}} 
                                    </td>
                                    <td style="text-align:center">
                                        @php $sum=0; @endphp
                                        @foreach($orderData as $data)
                                            
                                            @if($value->date==$data->date2 && $data->order_type=="Parcel Order")
                                                @php $sum = $sum + $data->grand_total; @endphp
                                            @endif
                                        @endforeach
                                        {{$sum}} 
                                    </td>
                                    <td style="text-align:center">
                                        @php $sum=0; @endphp
                                        @foreach($orderData as $data)
                                            
                                            @if($value->date==$data->date2 && $data->order_type=="Delivery Order")
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
                                    <td style="text-align:center"><span>৳ </span>{{$tableTotal}}</td>
                                    <td style="text-align:center"><span>৳ </span>{{$parcelTotal}}</td>
                                    <td style="text-align:center"><span>৳ </span>{{$deliveryTotal}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <div><h5>Total Credited: <span id="total">৳ </span><bold style="background: whitesmoke">{{$creditedTotal}}</bold></h5></div>
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