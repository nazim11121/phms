@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.staff') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.staff_list') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('Purchase List') }}</h4>
                <a href="{{ route('admin.purchase.create') }}" class="btn btn-success float-right">Create</a>
                <div class="row" id="example">
                    @foreach($perchaseList as $key=>$value)
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header text-white" style="background:#621bf2;">
                                <div class="form-inline">
                                    <h6 class="card-title">#{{$key}}</h6>
                                    <a class="btn btn-sm btn-success ml-2" href="{{route('admin.purchase.edit',$key)}}" style="padding: 0px 4px;margin-bottom: auto;margin-left: 3px;"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                @php $key2='';@endphp
                                <p class="card-text">
                                    @foreach($value as $key=>$item)
                                        @php $key2 = $item->purchase_no;@endphp
                                        <span>{{$item->quantity}}x {{$item->medicine->name}} - </span>   
                                        <span>{{$item->medicine->brand->name}}</span>  
                                        <br>
                                    @endforeach
                                </p>
                                <div class="form-inline">
                                    <button class="printButton1 btn btn-sm btn-primary" data-id="{{$key2}}"><i class="fa fa-sms"></i></button>
                                    <button class="printButton btn btn-sm btn-secondary ml-2" data-id="{{$key2}}"><i class="fa fa-user"></i> <i class="fa fa-print"></i></button>
                                    <form action="{{route('admin.purchase.destroy', $key2)}}"  id="delete-form-{{$key2}}" method="post">
                                        
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-danger ml-2" data-from-name="{{$key2}}" data-from-id="{{$key2}}" type="submit" title="Delete"><i class="mdi mdi-trash-can-outline"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Print Start -->
    <div class="printDiv1" id="contentToPrint1" style="display:none;width: 3in;margin-top:1px;margin-bottom:1px">
        <h2 style="width: 3in;" id="storeName"></h2>
        <h4 style="margin-left:16px;margin-top: -15px;width: 3in;" id="storeAddress"></h4>
        <h4 style="width: 3in;margin-top: -15px;margin-left:28px;" id="storeMobile"></h4>
        <h4 style="margin-left:16px;width: 3in;margin-top: -15px">{{date('d/m/Y h:m')}}</h4>
        <p style="margin-left: 10px;margin-bottom:-5px;margin-top:-10px;width: 3in;">Purchase No: <span id="purchaseNo"></span></p>
        <hr style="border: none;border-top: 1px dotted black;width: 2in;float:left">
        <div style="width: 3in;">
            <table class="table table-bordered" style="width: 3in;">
                <thead>
                    <tr>
                        <td>Qty</td>
                        <td>Item</td>
                    </tr>
                </thead>
                <tbody id="printTbody1">

                </tbody>
            </table>
        </div>
        <hr style="border: none;border-top: 1px dotted black;width: 2in;float:left">
        <div style="width: 100%; text-align:center;width: 2.1in;">
            <!-- <p>Thank You Will Come Again</p> -->
            <p style="font-size:12px;margin-top:-13px">Powered by N&N Co.</p>
        </div>
    </div>
<!-- Print End -->
@endsection

@push('style')
    <style>
        @media print {
            @page {
                size: 58mm auto; /* Adjust width to match printer's paper width */
                margin: 0;
            }
            body {
                font-size: 12px; /* Set the font size */
                margin: 0; /* No margins */
                padding: 0;
                width: 58mm;
                height: auto;
            }
        } 
        .printDiv1 {
            width: 58mm; /* Mini printer paper width, change to 58mm if needed */
            margin: 20px auto; /* Center the receipt preview */
            padding: 10px;
            border: 1px solid #ccc; /* Optional border for screen preview */
            font-size: 12px;
            background-color: #f9f9f9; /* Light background */
        }   
    </style>
@endpush

@push('script')
<!-- print start -->
<script>
        // jQuery code to handle printing and data fetching
        $(document).ready(function () {
            $(".printButton").on("click", function() {
                var dynamicId = $(this).data('id');
                printContent(dynamicId);
            });
        });

        // Function to fetch data using AJAX and print
        function printContent(dynamicId) {
            
            var url = '{{ route("admin.purchase.print", ":dynamicId") }}';
            url = url.replace(':dynamicId', dynamicId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) { 
                console.log(response);
                    $('#printTbody1').empty();
                    
                    $.each(response.purchase, function (index, item) {
                        
                        var row = '<tr><td>' + item.quantity + 'x' +'</td><td>' + item.medicine.name +' - '+ item.medicine.brand.name +'</td></tr>';
                        
                        $('#printTbody1').append(row);
                    });
                    
                    $('#storeName').html(response.storeName);
                    $('#storeAddress').html(response.storeAddress);
                    $('#storeMobile').html(response.storeMobile);
                    $('#purchaseNo').html(response.purchase_no);
                    // Open a new window and trigger print
                    var printWindow = window.open();
                    // printWindow.document.open();
                    printWindow.document.write(contentToPrint1.innerHTML);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>
<!-- print end -->
@endpush