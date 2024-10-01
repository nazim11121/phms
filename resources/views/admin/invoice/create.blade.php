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
                                @foreach($medicineList as $product)
                                    <div class="col-sm-4" style="margin-right: -12px">
                                        <div class="card" style="width: max-content;">
                                            <!-- <div class="card-body"> -->
                                                <div class="product-card" 
                                                    data-id="{{ $product->id }}" 
                                                    data-name="{{ $product->name }}" 
                                                    data-price="{{ $product->stock->selling_price }}"
                                                    data-group="{{ $product->group->name }}">
                                                    <h6>{{ $product->name }}</h6>
                                                    <p class="m-0">{{ $product->group->name }}</p>
                                                    <p class="m-0">Price: ৳{{ $product->stock->selling_price }}</p>
                                                    <button class="btn btn-primary add-to-cart-btn text-center"><i class="fa fa-shopping-cart"></i></button>
                                                </div>
                                            <!-- </div> -->
                                        </div>        
                                    </div>
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
                            <h5>Subtotal: ৳<span id="total-amount">0</span></h5>
                            
                            <div class="form-inline">
                                <label>Discount(৳)</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" name="discount" id="discount">
                                </div>
                            </div>
                            <h5>Grandtotal: ৳<span id="grand-total">0</span></h5>
                            <button class="btn btn-success" id="place-order">Place Order</button>
                            <button class="btn btn-danger" id="reset-btn">Reset</button>
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
    </style>
@endpush

@push('script')
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var totalAmount = 0;
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

    // Place order button
    $('#place-order').on('click', function() {
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        $.ajax({
            url: '/place-order',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                cart: cart
            },
            success: function(response) {
                alert('Order placed successfully!');
                cart = [];  // Clear cart after placing order
                updateCartDisplay();
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
            }
        });
    });
});
</script>

<script>
    $(document).ready(function() {
        // Initially show all product cards
        // $('.product-card').show();

        let initialItems = 3;
        let totalItems = $(".product-card").length;
        $(".product-card").slice(initialItems).hide();

        // Filter product cards based on input field
        $('#product-name-input').on('input', function() {
            var searchValue = $(this).val().toLowerCase();  

            $('.product-card').each(function() {
                var productName = $(this).data('name').toLowerCase();

                if (productName.includes(searchValue)) {
                    $(this).show();  
                } else {
                    $(this).hide();  
                }
            });
        });
    });
</script>
<!-- Handle Reset button click to remove all product cards -->
<script>
$(document).ready(function() {

    $("#reset-btn").on("click", function() {
        $("#total-amount").empty();
        $("#discount").empty();
        $("#grand-total").empty();
        $("#cart-items-list").empty();
    });
});
</script>

@endpush
