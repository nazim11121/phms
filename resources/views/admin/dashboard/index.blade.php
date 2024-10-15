@extends('admin.layouts.master')
@section('content')

    <!-- ======== breadcump start ========  -->

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <!-- @php $role=Auth::User()->roles;@endphp
                @foreach($role as $val)
                    @php $roleName = $val->name @endphp
                @endforeach  -->
            </div>
        </div>
    </div>

    <!-- ======== breadcump end ========  -->
    @if($roleName=='Super Admin' || $roleName=='Admin' || $roleName=='Admin2')
    <div class="ic-section-gap" >
        <div class="row">
            @can('Total Customer')
            <div class="col-lg-3 mb-2">
                <a href="#" style="color: black">
                    <div class="card card-1">
                        <div class="card-body">
                            <div class="card-img-top d-flex align-items-center bg-light">
                                <div>
                                    <img class="img-fluid" src="{{asset('storage/dashboard/product.jpg')}}" alt="Card image cap" style="border-radius: 15px;width: 68px;">
                                </div>
                                <h5 class="card-title" style="text-align:center">Todays Sell <span>{{ $data['total_order']}}</span></h5>
                                <p class="card-text"></p>
                                <h4 style="margin-left:5px"></h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
            @can('Total Orders')
            <div class="col-lg-3 mb-2">
                <a href="#" style="color: black">
                    <div class="card card-1">
                        <div class="card-body">
                            <div class="card-img-top d-flex align-items-center bg-light">
                                <div>
                                    <img class="img-fluid" src="{{asset('storage/dashboard/order.jpg')}}" alt="Card image cap" style="border-radius: 15px;width: 100px;">
                                </div>
                                <h5 class="card-title" style="text-align:center">Total Income <span>{{$data['total_amount']}}</span></h5>
                                <p class="card-text"></p>
                                <h4 style="margin-left:5px"></h4>
                            </div>
                        </div>
                    </div>
                </a>    
            </div>
            @endcan
            @can('Total Orders')
            <div class="col-lg-3 mb-2">
                <div class="card card-1">
                    <div class="card-body">
                        <div class="card-img-top d-flex align-items-center bg-light">
                            <div>
                                <img class="img-fluid" src="{{asset('storage/dashboard/expence.jpg')}}" alt="Card image cap" style="border-radius: 15px;width: 94px;">
                            </div>
                            <h5 class="card-title" style="text-align:center">Total Expensse <span>{{$data['total_expense']}}</span></h5>
                            <p class="card-text"></p>
                            <h4 style="margin-left:5px"></h4>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @can('Total Orders')
            <div class="col-lg-3 mb-2">
                <div class="card card-1">
                    <div class="card-body">
                        <div class="card-img-top d-flex align-items-center bg-light">
                            <div>
                                <img class="img-fluid" src="{{asset('storage/dashboard/profit.jpg')}}" alt="Card image cap" style="border-radius: 15px;width: 100px;">
                            </div>
                            <h5 class="card-title" style="text-align:center">Total Balanced <span>{{$data['total_profit']}}</span></h5>
                            <p class="card-text"></p>
                            <h4 style="margin-left:5px"></h4>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @endif
    <!-- @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif -->
    
        <!-- <div class="ic-section-gap" >
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title">List of Todays Order</h5>
                            <a href="{{route('admin.order.index')}}" class="btn btn-sm btn-primary float-right mb-2">View More</a>
                            <div class="table-responsive mt-1">
                            <div class="table-responsive mt-1">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead class="table-head">
                            <tr>
                                <td>Sl</td>
                                <td>Order No</td>
                                <td>Table Name</td>
                                <td>Order Summary</td>
                                <td>Total Amount</td>
                                <td class="action" style="text-align:center">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderList as $key=>$value)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value->order_no }}</td>
                                    <td>{{ $value->table_name }}</td>
                                    <td>
                                        @foreach($value->orderSku as $key=>$item)
                                        {{$item->quantity}}x{{$item->item_name}} <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        
                                            Tk. {{ $value->total }}<br>
                                            <a class="btn btn-sm btn-primary" href="{{route('admin.order.payment.add', $value->id)}}" onclick="openPaymentModal(this)" data-toggle="modal" data-target="#myPaymentModal" data-value="{{$value->id}}" data-id="{{$value->id}}" style="font-size: 10px;">Add Payment</a>
                                      
                                    </td>
                                    <td class="action-column">
                                        <button class="printButton1 btn btn-sm btn-primary" data-id="{{$value->id}}"><i class="fa fa-print"></i> Kitchen Print</button>

                                        <button class="printButton btn btn-sm btn-success" data-id="{{$value->id}}"><i class="fa fa-print"></i> Customer Print</button>
                                        @if($value->payment_status == 'Unpaid')
                                            <a class="mr-3 btn btn-sm btn-primary" href="{{route('admin.order.edit', $value->id)}}"><i class="fas fa-edit"></i>Add More</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>     -->
   
        <div class="row" id="example">
            @foreach($orderList as $value)
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header text-white" style="background:#621bf2;">
                        <div class="form-inline">
                            <h6 class="card-title">#@if($value->order_type=="Table Order"){{$value->table_name}}@else{{$value->order_type}}@endif</h6>
                            @if($value->payment_status == 'Unpaid' && $value->order_type == 'Table Order')
                                <a class="btn btn-sm btn-success ml-2" href="{{route('admin.order.edit', $value->id)}}" style="padding: 0px 4px;margin-bottom: auto;margin-left: 3px;"><i class="fa fa-plus"></i></a>
                            @endif
                            <h6 class="card-title" style="margin-left: auto;">৳ {{$value->grand_total}}</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <p class="card-text">
                            @foreach($value->orderSku as $key=>$item)
                                
                                @if($item->kprint == 2)
                                    <span style="color:red">{{$item->quantity}}x{{$item->item_name}} </span>    
                                @else 
                                    {{$item->quantity}}x{{$item->item_name}} 
                                    <button class="cancel btn btn-sm btn-danger" data-id="{{$value->id}}" data-name="{{$item->id}}" data-price="{{$item->item_price}}" style="padding: 0px 6px;"><i class="fa fa-minus"></i></button>   
                                @endif    
                                <br>
                            @endforeach
                        </p>
                        <div class="form-inline">
                            <button class="printButton1 btn btn-sm btn-primary" data-id="{{$value->id}}"><i class="fa fa-print"></i></button>
                            <a class="btn btn-sm btn-info ml-2" href="{{route('admin.order.payment.add', $value->id)}}" onclick="openPaymentModal(this)" data-toggle="modal" data-target="#myPaymentModal" data-value="{{$value->id}}" data-id="{{$value->id}}">Payment</a>
                            <button class="printButton btn btn-sm btn-secondary ml-2" data-id="{{$value->id}}"><i class="fa fa-user"></i> <i class="fa fa-print"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

<!-- Customer Print Start -->
    <div class="printDiv" id="contentToPrint" style="display:none;width: 2.1in;">
        <h2 style="text-align:center;width: 2.1in;">ধোঁয়া Restaurant</h2>
        <h4 style="text-align:center;width: 2.1in;margin-top:-15px">RC Street, Court Para</h4>
        <h4 style="text-align:center;width: 2.1in;margin-top: -15px">Kushtia(+8801873690534)</h4>
        <h4 style="text-align:center;width: 2.1in;margin-top: -15px">{{date('d/m/Y h:m')}}</h4>
        <div style="width: 2.1in;">
            <div style="width: 50%; float: left;width: 2.1in;margin-top:-15px">
                <p style="margin: 0">Order #<span id="orderId3"></span></p>
            </div>
            <div style="clear: both"></div>
        </div>
        <div style="width: 2.1in;">
            <table class="table table-bordered" style="width: 2.1in;">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Qty</td>
                        <td>Item</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody id="printTbody">

                </tbody>
            </table>
        </div>
        <hr style="border: none;border-top: 1px dotted black;width: 2.1in;float:left">
        <div style="padding: 3%;;width: 2.1in;">
            <div style="width: 45%; float: right;">
                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-40%">Subtotal:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -45px;" id="subTotal"></p>
                </div>
                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-55%">(-)Discount:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -40px;" id="discount2"> </p>
                </div>
                <!-- <hr style="border: none;border-top: 1px dotted black;width: 3in;float:left"> -->

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-85%">Delivery Charge:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -40px;" id="delivery_charge"> </p>
                </div>

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-60%">Net Payable:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -45px;" id="grandTotal2"></p>
                </div>

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>
                <hr style="border: none;border-top: 1px dotted black;">
                <div style="display: inline-block;">
                    <p style="margin: 0; font-weight: 600;margin-left:-75%" id="payment_method"> </p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: -15px;margin-left: -55px;margin-top:1px" id="grandTotal3"> </p>
                </div>

                <div style="margin-top: 2px"></div>
                <div style="clear: both"></div>
            </div>
            <div style="clear: both"></div>
            <!-- <div style="display: inline-block;margin-top: 1px">
                <p style="font-weight: 600; text-align:center">Thank You Will Come Again. </p>
            </div> -->
        
        </div>
        <hr style="border: none;border-top: 1px dotted black;width: 2.1in;float:left">
        <div style="width: 100%; text-align:center;width: 2.1in;">
            <!-- <p>For any queries, please call 01706358357 (10:00 AM - 10:00 PM)</p> -->
            <p>Thank You Will Come Again</p>
            <p style="font-size:12px;margin-top:-15px">Powered by DIGITAL INNOVATION</p>
        </div>
    </div>
<!-- Customer Print End -->
<!-- Kitchen Print Start -->
    <div class="printDiv1" id="contentToPrint1" style="display:none;width: 3in;margin-top:1px;margin-bottom:1px">
        <!-- <h2 style="width: 3in;">Dhoya Restaurant</h2>
        <h4 style="margin-left:10px;width: 3in;">RC Street, Court Para</h4>
        <h4 style="width: 3in;margin-top: -15px">Kushtia(+8801873690534)</h4>
        <h4 style="margin-left:16px;width: 3in;margin-top: -15px">{{date('d/m/Y h:m')}}</h4> -->
        <p style="margin-left: 10px;margin-bottom:-5px;width: 3in;">Order #<span id="orderId33"></span></p>
        <hr style="border: none;border-top: 1px dotted black;width: 2in;float:left">
        <div style="width: 3in;">
            <div style="width: 50%; float: left;width: 3in;">
                <p style="margin: 0">Table #<span id="tableName"></span></p>
            </div>
            <div style="clear: both"></div>
        </div>
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
        <div style="width: 3in;">
            <p style="width: 3in;font-size: 12px;float:left;margin-top:-5px">Powered by DIGITAL INNOVATION</p>
        </div>
    </div>
<!-- Kitchen Print End -->
<!-- payment add start -->
    <div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-validate" action="{{ route('admin.order.payment.store')}}" method="POST"
                            enctype="multipart/form-data" id="submitForm">
                @csrf 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin: 0% 10%">
                            <div style="width: 50%;font-size: 14px;display: inline-block">
                                <p style="margin: 0">Order ID:</p>
                                <p style="margin: 0">Order Amount:</p>
                                <p style="margin: 0">Date:</p>
                            </div>
                            <div style="width: 45%;font-size: 14px;float: right">
                                <p style="margin: 0"><span id="order_id3"></span></p>
                                <p style="margin: 0">Tk. <span id="totalAmount"></span></p>
                                <p style="margin: 0"><span id="date2"></span></p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <hr style="width: 500px;margin-top: 0px;margin-bottom: 0px;">
                        <div class="col-sm-10 tableOrder" style="margin: 3% 10%;display:none">
                            <div style="width: 50%;font-size: 14px;display: inline-block">
                                <p style="margin: 0">Customer Name<br><input type="text" name="customer_name" id="customer_name" class="form-control w-auto"></p>
                            </div>
                            <div style="width: 45%;font-size: 14px;float: right">
                                <p style="margin: 0">Customer Mobile<br>
                                <input type="text" name="customer_mobile" id="customer_mobile" class="form-control w-auto">
                            </p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <hr style="width: 500px;margin-top: 0px;margin-bottom: 0px;">
                        <div class="col-sm-10" style="margin: 3% 10%">
                            <div style="width: 50%;font-size: 14px;display:none" class="discount_amount">
                                <p style="margin: 0">Discount Amount<br><input type="number" name="discount_amount" id="discount_amount" class="form-control w-auto"></p>
                            </div>
                            <!-- <div style="width: 45%;font-size: 14px;float: right;display:none" class="delivery_charge"> 
                                <p style="margin: 0">Delivery Charge<br><input type="number" name="delivery_charge" id="delivery_charge" class="form-control w-auto"></p>
                            </div> -->
                            <div style="width: 50%;font-size: 14px;display: inline-block">
                                <p style="margin: 0">Paid Amount<br><input type="number" name="paid_amount" id="paid_amount" class="form-control w-auto" readonly></p>
                            </div>
                            <div style="width: 45%;font-size: 14px;float: right">
                                <p style="margin: 0">Payment Method<br>
                                <select class="select2 w-auto" name="payment_method">
                                    <option value="Cash">Cash</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Bank">Card</option>
                                </select>
                            </p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <input type="hidden" class="form-control" name="order_id" id="order_id44" value="" /> 
                        <p id="modalText22" hidden></p>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit & Print</button>
                </div>
            </form>
            </div>
        </div>
    </div>
<!-- payment add  end -->    
@endsection

@push('style')
<style>
    .page-titles{
        text-align: center;
        color: #795000;
    }
    .card-1{
        background-color: #fff;
        border-radius: 10px;
    }
    .status_1{
            color: #0FA958;
    }
    .status_2{
            color: #666666;
    }
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{asset('assets/print.js')}}"></script>
<script src="{{ asset('assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ asset('assets/jquery-toast-plugin/toastDemo.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#examples').DataTable();
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    @if (session('success'))
    showSuccessToast('{{ session("success") }}');
    @elseif(session('warning'))
    showWarningToast('{{ session("warning") }}');
    @elseif(session('danger'))
    showDangerToast('{{ session("danger") }}');
    @endif
  });
</script>
<script>
    $(document).ready(function() {
        $('.disabled').prop('disabled', true);
        $('.disabled').css('pointer-events', 'none');
    });
    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>
<!-- customer print -->
    <script>
        // jQuery code to handle printing and data fetching
        $(document).ready(function () {
            $("#example").on("click", ".printButton", function() {
                var dynamicId = $(this).data('id');
                printContent(dynamicId);
            });
        });

        // Function to fetch data using AJAX and print
        function printContent(dynamicId) {
            
            var url = '{{ route("admin.order.print", ":dynamicId") }}';
            url = url.replace(':dynamicId', dynamicId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) { 
                console.log(response);
                    $('#printTbody').empty();
                    $.each(response.data.order_sku, function (index, item) {
                        if(item.kprint!=2){
                            var row = '<tr><td>' + ++index + '</td><td>' + item.quantity + 'x' + item.item_price + '</td><td>' + item.item_name +'</td><td>' + item.subtotal + '</td></tr>';
                        }
                        $('#printTbody').append(row);
                    });
                    $('#orderId2').html(response.data.order_no);
                    $('#orderId3').html(response.data.order_no);
                    $('#subTotal').html(response.data.total);
                    $('#discount2').html(response.data.discount_amount);
                    $('#discountParcentage').html(response.data.discount);
                    $('#grandTotal2').html(response.data.grand_total);
                    $('#grandTotal3').html(response.data.grand_total);
                    $('#delivery_charge').html(response.data.delivery_charge);
                    $('#payment_method').html(response.data.payment_method);
                    // Open a new window and trigger print
                    var printWindow = window.open();
                    // printWindow.document.open();
                    printWindow.document.write(contentToPrint.innerHTML);
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
<!-- kitchen print start -->
    <script>
        // jQuery code to handle printing and data fetching
        $(document).ready(function () {
            $("#example").on("click", ".printButton1", function() {
                var dynamicId = $(this).data('id');
                printContent1(dynamicId);
            });
        });

        // Function to fetch data using AJAX and print
        function printContent1(dynamicId) {
            
            var url = '{{ route("admin.order.kitchen.print", ":dynamicId") }}';
            url = url.replace(':dynamicId', dynamicId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) { 
                console.log(response);
                    $('#printTbody1').empty();
                    $.each(response.data.order_sku2, function (index, item) {
                        if(item.kprint ==2){
                            var row = '<tr><td>' + item.quantity + 'x' +'</td><td><del>' + item.item_name +'</del></td></tr>';
                        }else{
                            var row = '<tr><td>' + item.quantity + 'x' +'</td><td>' + item.item_name +'</td></tr>';
                        }
                        // var row = '<tr><td>' + item.quantity + 'x' +'</td><td>' + item.item_name +'</td></tr>';
                        $('#printTbody1').append(row);
                    });
                    if(response.data.order_type=="Table Order"){
                        $('#tableName').html(response.data.table_name);
                    }else{
                        $('#tableName').html(response.data.order_type);
                    }
                    $('#orderId33').html(response.data.order_no);
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
<!-- kitchen print end -->
<!-- Payment add start -->
    <script>

        function openPaymentModal(button) {
        
        var valueToDisplay22 = button.getAttribute('data-value');
        var modalText22 = document.getElementById("modalText");

        $('#order_id44').val(valueToDisplay22);

        $.ajax({
            url: 'order/payment/add/' + valueToDisplay22,
            method: 'GET',
            // dataType: 'json',
            success: function (data) {
                
                $('#order_id3').html(data.order_no);
                var formattedDate = moment(data.created_at).format('DD-MM-YYYY');
                $('#date2').html(formattedDate);
                $('#totalAmount').html(data.total);
                $('#paid_amount').val(data.total);
                $('#paidAmount2').html(data.paid_amount);
                var balance = data.grand_total - data.paid_amount;
                $('#balance').html(balance);
                var tableBody = $('#dataTable tbody');
                tableBody.empty();

                if(data.order_type == 'Table Order'){
                    $('.tableOrder').css('display','block');
                    $('.discount_amount').css('display','block');
                }else if(data.order_type == 'Parcel Order'){
                    $('.tableOrder').css('display','none');
                    $('.discount_amount').css('display','block');
                }else{
                    $('.tableOrder').css('display','none');
                }

                var discount = 0;
                var deliveryCharge = 0;
                $('#discount_amount').on('input', function() { 
                     discount = $(this).val();
                     var total = data.total;
                    if(discount>0 && deliveryCharge<1){
                        var grandTotal = total-discount;
                        $('#paid_amount').val(grandTotal);
                    }else if(discount>0 && deliveryCharge>0){
                        var grandTotal1 = parseFloat(total)+parseFloat(deliveryCharge);
                        var grandTotal = parseFloat(grandTotal1)-parseFloat(discount);
                        $('#paid_amount').val(grandTotal);
                    }else{
                        $('#paid_amount').val(data.total);
                    }
                });
                $('#delivery_charge').on('input', function() { 
                     deliveryCharge = $(this).val();
                    if(deliveryCharge){
                        var total = data.total;
                        var grandTotal1 = parseFloat(total)+parseFloat(deliveryCharge);
                        var grandTotal = parseFloat(grandTotal1)-parseFloat(discount);
                        $('#paid_amount').val(grandTotal);
                    }else{
                        $('#paid_amount').val(data.total);
                    }
                });
                // $('#paid_amount').on('input', function() {
                //     var paidAmount = parseFloat($(this).val());
                //     var balanceAmount = parseFloat(data.grand_total);

                //     if (paidAmount > balanceAmount) {
                //         alert('Paid amount cannot be more than the balance amount');
                //         $(this).val(''); 
                //     }
                // });

            },
            error: function () {
                alert('Failed to fetch record data.');
            }
        });

        modalText22.innerHTML =  valueToDisplay22;

        $('#order_id44').val(valueToDisplay22); 
        
        var modal = document.getElementById("myModal");
        // modal.style.display = "block";
        
        }

        function closeModal() {
        
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
        }

    </script>
<!-- Payment add end -->    
<!-- Payment & Print start -->
    <script>
        $(document).ready(function() {
            $('#submitForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting normally

                var form = $(this);
                var formData = form.serialize();

                // Send form data using AJAX
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // After successful submission, trigger the print action
                        printFormData(response);
                    },
                    error: function(xhr) {
                        alert('Error occurred while submitting the form');
                    }
                });
            });

            function printFormData(data) {
                $('#printTbody').empty();
                $.each(data.order_sku, function (index, item) {
                    var row = '<tr><td>' + ++index + '</td><td>' + item.quantity + 'x' + item.item_price + '</td><td>' + item.item_name +'</td><td>' + item.subtotal + '</td></tr>';
                    $('#printTbody').append(row);
                });
                $('#orderId2').html(data.order_no);
                $('#orderId3').html(data.order_no);
                $('#subTotal').html(data.total);
                $('#discount2').html(data.discount_amount);
                $('#discountParcentage').html(data.discount);
                $('#grandTotal2').html(data.grand_total);
                $('#grandTotal3').html(data.grand_total);
                $('#delivery_charge').html(data.delivery_charge);
                var printWindow = window.open();
                printWindow.document.write(contentToPrint.innerHTML);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
                location.reload(true);
                // printWindow.onafterprint = function() {
                //     printWindow.close();
                //     window.location.href = "{{ route('admin.dashboard') }}"; 
                // };
            }
        });
    </script>
<!-- Payment & Print end -->
<!-- Item Cancel Start -->
 <script>
    $(document).ready(function () {
            $(".cancel").on("click", function() {
                var orderId = $(this).data('id');
                var itemId = $(this).data('name');
                var itemPrice = $(this).data('price');
                submit(orderId,itemId,itemPrice);
            });
        });

        // Function to fetch data using AJAX and print
        function submit(orderId,itemId,itemPrice) {

            var url = '{{ route("admin.item.cancel") }}';

            $.ajax({
                url: url,
                type: 'Post',
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    orderId: orderId,
                    itemId: itemId,
                    itemPrice: itemPrice
                },
                success: function (response) { 
                    if(response){
                        window.location.href = "{{ route('admin.dashboard') }}";
                    }
                    // console.log(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
 </script>
<!-- Item Cancel End -->
@endpush