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
         
                <h4 class="float-left header-title">{{ __t('order_list') }}</h4>
                <div class="float-right">
                    <!-- <a href="{{route('admin.order.create')}}" class="btn btn-primary">Create Manual Invoice</a> -->
                </div>
                <br><br>
                <div class="table-responsive mt-1">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead class="table-head">
                            <tr>
                                <td>Sl</td>
                                <td>Order No</td>
                                <td>Order Type</td>
                                <td>Order Summary</td>
                                <td>Total Amount</td>
                                <td>Discount</td>
                                <!-- <td>Date</td> -->
                                <!-- <td>Status</td> -->
                                <td class="action">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderList as $key=>$value)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value->order_no }}</td>
                                    <td>{{ $value->order_type }}</br>{{ $value->table_name }}</td>
                                    <td>
                                        @foreach($value->orderSku as $key=>$item)
                                        {{$item->quantity}} x {{$item->item_name}}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($value->payment_status == 'Paid')
                                            Tk. {{ $value->grand_total }}<br>
                                            <a class="btn btn-sm btn-primary disabled" href="{{route('admin.order.payment.add', $value->id)}}" onclick="openPaymentModal(this)" data-toggle="modal" data-target="#myPaymentModal" data-value="{{$value->id}}" style="font-size: 10px;">Paid</a>
                                        @else
                                            Tk. {{ $value->total }}<br>
                                            <a class="btn btn-sm btn-primary" href="{{route('admin.order.payment.add', $value->id)}}" onclick="openPaymentModal(this)" data-toggle="modal" data-target="#myPaymentModal" data-value="{{$value->id}}" style="font-size: 10px;">Payment</a>
                                        @endif
                                    </td>
                                    <!-- <td>Tk. {{ $value->total - $value->payable_amount }}</td> -->
                                    <td>Tk. {{ $value->discount_amount }}</td>
                                    <!-- <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td> -->
                                    <!-- <td>@if($value->status=='0')
                                            <span style="color:#0804D2">{{ 'Incomplete' }}</span>
                                        @else
                                            <span style="color:#B00000">{{ 'Complete' }}</span>
                                        @endif
                                    </td> -->
                                    <td class="action-column">
                                        <!-- <a class="btn btn-default" href="{{route('admin.order.show', $value->id)}}" onclick="openViewModal(this)" data-toggle="modal" data-target="#myViewModal" data-value="{{$value->id}}"><i class="fas fa-eye"></i></a> -->
                                  
                                        <button class="printButton1 btn btn-sm btn-primary" data-id="{{$value->id}}"><i class="fa fa-print"></i> Kitchen</button>

                                        <button class="printButton btn btn-sm btn-info" data-id="{{$value->id}}"><i class="fa fa-print"></i> <i class="fa fa-user"></i></button>
                                        
                                        @if($value->payment_status == 'Unpaid' && $value->order_type == 'Table Order')
                                            <a class="btn btn-sm btn-primary" href="{{route('admin.order.edit', $value->id)}}"><i class="fas fa-plus"></i></a>
                                        @endif
                                        @php $role=Auth::User()->roles;@endphp
                                        @foreach($role as $val)
                                            @php $roleName = $val->name @endphp
                                        @endforeach 
                                        @if($roleName=='Super Admin' || $roleName=='Admin' || $roleName=='Admins')
                                            {!! Form::open(['method' => 'DELETE','route' => ['admin.order.destroy', $value->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-sm btn-danger" data-from-text = "You won\'t be able to revert this! <br/> " data-from-name="" data-from-id=""  title="Delete"><i class="mdi mdi-trash-can-outline"></i></button> 
                                            {!! Form::close() !!}
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
<!-- Customer Print Start -->
    <div class="printDiv" id="contentToPrint" style="display:none;width: 2.1in;">
        <h2 style="text-align:center;width: 2.1in;">ধোঁয়া Restaurant</h2>
        <h4 style="text-align:center;width: 2.1in;margin-top:-15px">RC Street, Court Para</h4>
        <h4 style="text-align:center;width: 2.1in;margin-top: -15px">Kushtia(+8801873690534)</h4>
        <h4 style="text-align:center;width: 2.1in;margin-top: -15px">{{date('d/m/Y h:m')}}</h4>
        <div style="width: 2.1in;">
            <div style="width: 50%; float: left;width: 2.1in;;margin-top:-15px">
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
<!-- Payment Add Modal -->
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
                <div class="col-sm-12" style="margin: 3% 10%">
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
                    <div style="width: 50%;font-size: 14px;display: none" class="discount_amount">
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
                <input type="hidden" class="form-control" name="order_id" id="order_id4" value="" /> 
                <p id="modalText2" hidden></p>
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
<!-- View History Modal -->
<div class="modal fade" id="myHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-sm-12" style="margin: 3% 0">
                    <div style="width: 50%;font-size: 14px;display: inline-block">
                        <p style="margin-bottom: 3px; font-size: 16px;font-weight: 500;">Customer To</p>
                        <p style="margin: 0"><span id="name4"></span></p>
                        <p style="margin: 0"><span id="phone4"></span></p>
                        <!-- <p style="margin: 0"><span id="email4"></span></p> -->
                        <p style="margin: 0">Order ID: <span id="order_id5"></span></p>
                        <p style="margin: 0">Date: <span id="date4"></span></p>
                        <p style="margin: 0">Total Order Amount: <span id="totalAmount4"></span></p>
                        <p style="margin: 0">Status: <span id="status4"></span></p>
                        <p style="margin: 0">Payment Status: <span id="paymentStatus4"></span></p>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div class="col-sm-12"><h5 class="text-center">Payment List</h5></div>
                <table style="width: 100%;margin: 20px;" id="dataTable">
                    <thead>
                        <tr>
                            <th style="text-align: left">#</th>
                            <th style="text-align: left">Date</th>
                            <th style="text-align: left">Payment Method</th>
                            <th style="text-align: left">Paid Amount</th>
                            <th style="text-align: left">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table> 
                <input type="hidden" class="form-control" name="order_id" id="order_id" value="" /> 
                <p id="modalText" hidden></p>
            </div>  
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
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
            size: 80mm 297mm; 
            margin: 0px;
            font-size: 10pt;
            font-family: Tahoma, Verdana, Segoe, sans-serif;
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
        embed {
            margin: 0;
            width: 58mm;
            height: auto;
        }
    </style>
@endpush

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.rawgit.com/DoersGuild/jQuery.print/master/jQuery.print.js"></script>
<script src="{{asset('assets/print.js')}}"></script>
<script src="{{ asset('assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ asset('assets/jquery-toast-plugin/toastDemo.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#example').DataTable();
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
</script>
<!-- Customer print -->
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
                        // var row = '<tr><td>' + ++index + '</td><td>' + item.quantity + 'x' + item.item_price + '</td><td>' + item.item_name +'</td><td>' + item.subtotal + '</td></tr>';
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
<!-- Kitchen print start-->
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
                
                    $('#printTbody1').empty();
                    $.each(response.data.order_sku2, function (index, item) {
                        if(item.kprint ==2){
                            var row = '<tr><td>' + item.quantity + 'x' +'</td><td><del>' + item.item_name +'</del></td></tr>';
                        }else{
                            var row = '<tr><td>' + item.quantity + 'x' +'</td><td>' + item.item_name +'</td></tr>';
                        }
                        $('#printTbody1').append(row);
                    });
                    $('#orderId33').html(response.data.order_no);
                    if(response.data.order_type=="Table Order"){
                        $('#tableName').html(response.data.table_name);
                    }else{
                        $('#tableName').html(response.data.order_type);
                    }
                    
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
<!-- Kitchen print end-->    
<!-- Payment add start -->
    <script>

        function openPaymentModal(button) {
        
        var valueToDisplay2 = button.getAttribute('data-value');
        var modalText2 = document.getElementById("modalText");

        $.ajax({
            url: 'order/payment/add/' + valueToDisplay2,
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

        modalText2.innerHTML =  valueToDisplay2;

        $('#order_id4').val(valueToDisplay2); 
        
        var modal = document.getElementById("myModal");
        // modal.style.display = "block";
        
        }

        function closeModal() {
        
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
        }

    </script>
<!-- Payment add end -->    
<!-- Payment and Print start -->
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
    <!-- Payment and Print end -->
@endpush