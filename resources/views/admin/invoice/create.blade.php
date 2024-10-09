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
                <h4 class="header-title">{{ __t('add_invoice') }}</h4>
                <div class="container">
                    <div class="row">
                        <!-- Product Cards -->
                        <!-- <h3>Medicine</h3> -->
                        <div class="container mb-2">
                            <input type="text" class="form-control" name="search" id="product-name-input" placeholder="Type product name...">
                        </div>
                        <div class="col">
                            <div class="product-cards row">
                                @php
                                    $currentDate = date('Y-m-d',strtotime(Carbon\Carbon::now()));
                                @endphp
                                @foreach($medicineList as $product)
                                    @if($product->stock->expired_date>$currentDate)
                                    <div class="col-sm-4" style="margin-right: -12px">
                                        <div class="card" style="width: fit-content;">
                                            <!-- <div class="card-body"> -->
                                                <div class="product-card" 
                                                    data-id="{{ $product->id }}" 
                                                    data-name="{{ $product->name }}" 
                                                    data-price="{{ $product->stock->selling_price }}"
                                                    data-group="{{ $product->group->name }}">
                                                    <h6>{{ $product->name }}({{ $product->available_stock }}) </h6>
                                                    <p class="m-0">{{ $product->group->name }}</p>
                                                    <p class="m-0">Price: ৳{{ $product->stock->selling_price }}</p>
                                                    
                                                    @if($product->available_stock>0)
                                                        <button class="btn btn-primary btn-sm add-to-cart-btn text-center mt-1" style="float:left"><i class="fa fa-shopping-cart"></i></button>
                                                        <a class="btn btn-sm btn-success mt-1" href="#" onclick="openStockUpdateModal(this)" data-toggle="modal" data-target="#myStockUpdateModal" data-value="{{$product->id}}" style="padding: 1px 7px;float:right"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <button class="btn btn-danger btn-sm add-to-cart-btn text-center" style="font-size: smaller;float:left" disabled>StockOut</button>
                                                        <a class="btn btn-sm btn-success mt-1" href="#" onclick="openStockUpdateModal(this)" data-toggle="modal" data-target="#myStockUpdateModal" data-value="{{$product->id}}" style="padding: 1px 7px;float:right"><i class="fa fa-plus"></i></a>
                                                    @endif
                                                </div>
                                            <!-- </div> -->
                                        </div>        
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Cart/Invoice Section -->
                        <div class="col-sm-6 invoice-section float-end">
                            <h3>Invoice</h3>
                            <div class="container mb-2">
                                <div class="row">
                                    <label>Name: </label>
                                    <div class="col-sm-4">
                                    <input type="text" class="form-control" name="customer_name" id="customer_name">
                                    </div>
                                    <label>Mobile: </label>
                                    <div class="col-sm-5">
                                    <input type="text" class="form-control" name="customer_mobile" id="customer_mobile">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="cart-items">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items-list">
                                        <!-- Cart items will be dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                            <h5>SubTotal: ৳<span id="total-amount">0</span></h5>
                            <input type="hidden" name="total" id="total">
                            
                            <div class="form-inline">
                                <label>Discount(%)</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="discount" id="discount" min="0">
                                </div>
                            </div>
                            <h5>GrandTotal: ৳<span id="grand-total">0</span></h5>
                            <input type="hidden" name="grand_total" id="grand_total">
                            <div class="form-inline mb-4">
                                
                                <label>Pay Amount</label>
                                <div class="col-sm-4 mr-4">
                                    <input type="number" class="form-control" name="payable_amount" id="payable_amount" min="0">
                                </div>
                                <div class="col-sm-4 ml-2">
                                    <select class="select2 form-control" name="payment_method" id="payment_method">
                                        <option value="Cash">Cash</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-success" id="place-order">Place Order</button>
                            <button class="btn btn-danger" id="reset-btn">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Invoice Print Start-->
    <div class="printDiv" id="contentToPrint" style="display:none;width: 3in;">
        <h2 style="text-align:center;width: 3in;" id="shopName"></h2>
        <h4 style="text-align:center;width: 3in;margin-top:-15px" id="shopAddress"></h4>
        <h4 style="text-align:center;width: 3in;margin-top: -15px" id="shopMobile"></h4>
        <h4 style="text-align:center;width: 3in;margin-top: -15px">{{date('d/m/Y h:m')}}</h4>
        <div clas="container" style="width: 3in;margin-bottom:5px">
            <div class="row">
                <div class="col-sm" style="float:left">
                    <p style="margin: 0">Invoice#<span id="invoiceNo"></span></p>
                </div>
                <div class="col-sm" style="float:right">
                    <p style="margin: 0;"><span id="customerMobile"></span></p>
                </div>
            </div>
            <div style="clear: both"></div>
        </div>
        <div style="width: 3in;">
            <table class="table table-bordered" style="width: 3in;">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Item</td>
                        <td>Qty</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody id="printTbody">

                </tbody>
            </table>
        </div>
        <hr style="border: none;border-top: 1px dotted black;width: 3in;float:left">
        <div style="padding: 3%;;width: 3in;">
            <div style="width: 45%; float: right;">
                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-50%">Subtotal:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -77px;" id="subTotal"></p>
                </div>
                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-60%">(-)Discount:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -77px;" id="discount2"> </p>
                </div>
                <!-- <hr style="border: none;border-top: 1px dotted black;width: 3in;float:left"> -->

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <!-- <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-75%">Delivery Charge:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -77px;" id="delivery_charge"> </p>
                </div> -->

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>

                <div style="display: inline-block">
                    <p style="margin: 0; font-weight: 600;margin-left:-75%">Net Payable:</p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: 0;margin-left: -80px;" id="grandTotal2"></p>
                </div>

                <div style="margin-top: 5px"></div>
                <div style="clear: both"></div>
                <hr style="border: none;border-top: 1px dotted black;width:1in">
                <div style="display: inline-block;">
                    <p style="margin: 0; font-weight: 600;margin-left:-75%" id="paymentMethod"> </p>
                </div>
                <div style="display: inline-block; float: right;">
                    <p style="margin: -15px;margin-left: -80px;margin-top:1px" id="payableAmount"> </p>
                </div>
                <div style="clear: both"></div>
                <!-- Due -->
                <div class="due" style="display: none">
                    <p style="margin: 0; font-weight: 600;margin-left:-75%">Due</p>
                </div>
                <div  class="due" style="display: none; float: right;">
                    <p style="margin: -15px;margin-left: -80px;margin-top:1px" id="due"> </p>
                </div>

                <div style="margin-top: 2px"></div>
                <div style="clear: both"></div>
            </div>
            <div style="clear: both"></div>
            <!-- <div style="display: inline-block;margin-top: 1px">
                <p style="font-weight: 600; text-align:center">Thank You Will Come Again. </p>
            </div> -->
        
        </div>
        <hr style="border: none;border-top: 1px dotted black;width: 3in;float:left">
        <div style="width: 3in;text-align:center">
            <!-- <p>For any queries, please call 01706358357 (10:00 AM - 10:00 PM)</p> -->
            <p style="width: 3in;font-size: 14px;float:left;margin-top:-5px">Thank You Will Come Again</p>
            <p style="width: 3in;font-size: 12px;float:left;margin-top:-10px">Powered by N&N Co.</p>
        </div>
    </div>
<!-- Invoice Print End-->
<!-- Edit Stock Modal Start -->
    <div class="modal fade" id="myStockUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __t('stock_update') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-validate" action="{{ route('admin.medicine.stock.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf   
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="from_access" value="invoice">
                            <div class="form-group col-sm-6 mt-2">
                                <label for="name">Medicine Name <span class="requiredStar"> *</span></label>
                                <input type="text" class="form-control" name="name" id="name" required disabled>
                                @error('name')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6 mt-2">
                                <label for="name">Available Stock <span class="requiredStar"> *</span></label>
                                <input type="text" class="form-control" name="available_stock" id="available_stock" required readonly>
                                @error('available_stock')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row"> 
                        <div class="form-group col-sm-6 mt-2">
                            <label for="name">Add New Stock</label>
                            <input type="number" class="form-control" name="new" id="new">
                            @error('new')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 mt-2">
                            <label for="name">Expired Date<span class="requiredStar"> *</span></label>
                            <input type="date" class="form-control" name="expired_date" id="expired_date" required>
                            @error('expired_date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div><br>
                        </div> 
                        <div class="row"> 
                        <div class="form-group col-sm-6 mt-2">
                            <label for="name">Buying Price</label>
                            <input type="number" class="form-control" name="buying_price" id="buying_price">
                            @error('buying_price')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div><br>
                        <div class="form-group col-sm-6 mt-2">
                            <label for="name">Selling Price<span class="requiredStar"> *</span></label>
                            <input type="number" class="form-control" name="selling_price" id="selling_price" required>
                            @error('selling_price')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div><br>
                        </div> 
                        <div class="row">  
                            <input type="hidden" class="form-control" name="id" id="medicine_id" value="" /> 
                            <p id="modalText3" hidden></p>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>  
            </div>
        </div>
    </div>
<!-- Edit Stock Modal End -->
@endsection

@push('style')
    <style>
        .container {
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            /* margin-bottom: 10px; */
            cursor: pointer;
        }
        .product-card h4 {
            margin-bottom: 10px;
        }
        .invoice-section {
            margin-left: 50px;
        }
        .modal-footer{
            justify-content: center!important;
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

<!-- Product add to cart and calculation start -->
<script>
    $(document).ready(function() {
        var totalAmount = 0;
        var discountPercentage = 0;
        var discountAmount = 0;
        var cart = [];

        // Add product to cart on button click
        $('.add-to-cart-btn').on('click', function() {
            var productId = $(this).closest('.product-card').data('id');
            var productName = $(this).closest('.product-card').data('name');
            var productPrice = $(this).closest('.product-card').data('price');
            
            // Check if the product is already in the cart
            var existingProduct = cart.find(item => item.id === productId);

            if (existingProduct) {
                existingProduct.quantity++;
            } else {
                // Add new product to cart
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
            }

            updateCartDisplay();
        });

        // Function to update the cart display and calculate total
        function updateCartDisplay() {
            var totalAmount = 0;
            var cartHtml = '';

            cart.forEach(function(item, index) {
                var productTotal = item.price * item.quantity;
                totalAmount += productTotal;
                cart[index].subtotal = productTotal;

                cartHtml += `
                    <tr data-index="${index}">
                        <td>${item.name}</td>
                        <td>${item.price}</td>
                        <td>
                            <input type="number" class="form-control quantity-input" data-index="${index}" value="${item.quantity}" min="1">
                        </td>
                        <td>${productTotal}</td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item-btn" data-index="${index}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });

            $('#cart-items tbody').html(cartHtml);
            $('#total-amount').text(totalAmount.toFixed(2));
            $('#total').val(totalAmount.toFixed(2));
            $('#grand-total').text(totalAmount.toFixed(2));
            $('#grand_total').val(totalAmount.toFixed(2));

            // Update cart when quantity changes
            $('.quantity-input').on('input', function() {
                var index = $(this).data('index');
                cart[index].quantity = $(this).val();
                updateCartDisplay();
            });

            // Remove product from cart
            $('.remove-item-btn').on('click', function() {
                var index = $(this).data('index');
                cart.splice(index, 1);
                updateCartDisplay();
            });

        }
        // dicount calculation
        $('#discount').on('input', function() {

            var discount = $(this).val();
            var total = $('#total').val();
            discountPercentage = discount;
            discountAmount = (total/100)*discount;
            // console.log(discountAmount);
            if(discountPercentage>0){ 
                var grandTotal = total - discountAmount;
                $('#grand-total').text(grandTotal.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));
                
            }else{
                discountAmount = 0;
                $('#grand-total').text(total);
                $('#grand_total').val(total);
            }
        });

        // Place order button
        $('#place-order').on('click', function() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            var customer_name = $('#customer_name').val();
            var customer_mobile = $('#customer_mobile').val();
            var total = $('#total').val();
            var grand_total = $('#grand_total').val();
            var discount_percentage = discountPercentage;
            var discount_amount = discountAmount;
            var payable_amount = $('#payable_amount').val();;
            var payment_method = $('#payment_method').val();;

            $.ajax({
                url: '/admin/invoice/data/store',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cart: cart,
                    customer_name:customer_name,
                    customer_mobile:customer_mobile,
                    total:total,
                    grand_total:grand_total,
                    discount_percentage:discount_percentage,
                    discount_amount:discount_amount,
                    payable_amount:payable_amount,
                    payment_method:payment_method
                },
                success: function(response) {  console.log(response);
                    // alert('Order placed successfully!');
                    cart = [];
                    $('#customer_name').val('');
                    $('#customer_mobile').val('');
                    $('#discount').val('');
                    $('#payable_amount').val('');
                    $('#payment_method').val('');
                    updateCartDisplay();

                    printFormData(response);
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        });
        // print
        function printFormData(data) {
            $('#printTbody').empty();
            $.each(data.sku, function (index, item) {
                var row = '<tr><td>' + ++index + '</td><td>' + item.name +'</td><td>' + item.quantity + 'x' + item.price + '</td><td>' + item.subtotal + '</td></tr>';
                $('#printTbody').append(row);
            });

            $('#shopName').html(data.shopName);
            $('#shopMobile').html(data.shopMobile);
            $('#shopAddress').html(data.shopAddress);
            $('#invoiceNo').html(data.invoice.invoice_no);
            $('#subTotal').html(data.invoice.total);
            $('#discount2').html(data.invoice.discount_amount);
            // $('#discountParcentage').html(data.invoice.discount_percentage);
            $('#grandTotal2').html(data.invoice.grand_total);
            $('#payableAmount').html(data.invoice.payable_amount);
            if(data.invoice.payable_amount){
                $('#paymentMethod').html(data.invoice.payment_method);
            }else{
                $('.due').css('display','inline-block');
                $('#due').html(data.invoice.due);
            }
            $('#delivery_charge').html(data.invoice.delivery_charge);
            $('#customerMobile').html(data.invoice.customer_mobile);
            if(data.invoice.due>0){
                $('.due').css('display','inline-block');
                $('#due').html(data.invoice.due);
            }
            var printWindow = window.open();
            printWindow.document.write(contentToPrint.innerHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            location.reload(true);
        }
    });
</script>
<!-- End -->
<!-- Search Script Start -->
<script>
    $(document).ready(function() {
        // Initially show all product cards
        // $('.product-card').show();

        let initialItems = 9;
        let totalItems = $(".product-card").length;
        $(".product-card").slice(initialItems).hide();

        // Filter product cards based on input field
        $('#product-name-input').on('input', function() {
            var searchValue = $(this).val().toLowerCase();  

            $('.product-card').each(function() {
                var productName = $(this).data('name').toLowerCase();
                var genericName = $(this).data('group').toLowerCase();
                
                if (productName.includes(searchValue)||genericName.includes(searchValue)) {
                    $(this).show();  
                } else {
                    $(this).hide();  
                }
            });
        });
    });
</script>
<!-- Search Script End -->
<!-- Handle Reset button click to remove all product cards -->
<script>
    $(document).ready(function() {

        $("#reset-btn").on("click", function() {
            $("#customer_name").val('');
            $("#customer_mobile").val('');
            $("#total-amount").empty();
            $("#discount").val('');
            $("#grand-total").empty();
            $("#cart-items-list").empty();
        });
    });
</script>
<!-- Stock Edit start -->
<script>

    function openStockUpdateModal(button) {
    
    var valueToDisplay2 = button.getAttribute('data-value');
    var modalText2 = document.getElementById("modalText3");

    $.ajax({
        url: '/admin/medicine/stock/' + valueToDisplay2,
        method: 'GET',
        // dataType: 'json',
        success: function (response) {
            console.log(response.medicine.stock);
            $('#name').val(response.medicine.name);
            $('#available_stock').val(response.medicine.available_stock);
        },
        error: function () {
            alert('Failed to fetch record data.');
        }
    });

    modalText2.innerHTML =  valueToDisplay2;

    $('#medicine_id').val(valueToDisplay2); 

    var modal = document.getElementById("myStockUpdateModal");
    modal.style.display = "block";
    }

    function closeModal() {

    var modal = document.getElementById("myStockUpdateModal");
    modal.style.display = "none";
    }
</script>
<!-- Stock Edit end -->
@endpush
