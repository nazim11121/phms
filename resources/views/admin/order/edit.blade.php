@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('Order') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.create_manual_order') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="background: black;color: red;">
            <div class="card-body">
                <h4 class="header-title">{{ __('Table Order') }}</h4>

                <form class="form-validate" action="{{ route('admin.order.update', $order->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="container mt-5">
                        <div class="row">
                        <!-- Category List -->
                            <input type="hidden" name="order_type" value="Table Order">
                            <input type="hidden" name="order_no" value="{{$order->order_no}}">
                            <input type="hidden" name="id" value="{{$order->id}}">
                            <div class="form-group row">
                                <h4 class="col-sm-2 col-form-label">Select Table No <span class="requiredStar"> *</span></h4>
                                <div class="col-sm-3">
                                    <select class="form-control select2" name="table_name" id="table_name" required disabled> 
                                        @foreach($tableName as $value)
                                            <option value="{{$value->name}}" {{$order->table_name==$value->name?'selected':''}}>{{$value->name}}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4>Categories</h4>
                                <ul class="category-list">
                                    @foreach($category as $value)
                                        <li class="list-group-item category-item" data-category="{{$value->id}}"  style="border: none">{{$value->name}}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- Item List -->
                            <div class="col-md-8 item-div">
                                <h4>Items</h4>
                                    @foreach($category as $value)
                                    <div id="{{$value->id}}" class="item-group">
                                      @foreach($item as $data)
                                        @if($data->category == $value->id)
                                        <div class="row mb-3 product"data-price="{{$data->price}}" data-name="{{$data->name}}">
                                            <label class="col-md-3 col-form-label">{{$data->name}}</label>
                                            <input type="hidden" class="form-control name-input" name="items[{{$data->name}}][name]" value="{{$data->name}}">
                                            <div class="col-md-3 number-input">
                                                <button onclick="this.parentNode.querySelector('input[type=number]').stepDown();return false" class="minus">-</button>
                                                <input class="quantity quantity-input" name="items[{{$data->name}}][quantity]" data-name="{{$data->name}}" data-price="{{$data->price}}" min="0" value="0" type="number">
                                                <button onclick="this.parentNode.querySelector('input[type=number]').stepUp();return false" class="plus">+</button>
                                            </div>
                                            <!-- <div class="col-md-3">
                                                <input type="number" class="form-control quantity-input" name="items[{{$data->name}}][quantity]" data-name="{{$data->name}}" data-price="{{$data->price}}" placeholder="Quantity" min="0" value="0">
                                            </div> -->
                                            <div class="col-md-3">
                                                <input type="text" class="form-control price" name="items[{{$data->name}}][price]" placeholder="Price" readonly value="{{$data->price}}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control total-price" name="items[{{$data->name}}][subtotal]" placeholder="Total" readonly value="0.00">
                                            </div>
                                        </div>
                                        @endif
                                      @endforeach
                                    </div>
                                    @endforeach

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5>Grand Total: <span id="grandTotal">৳0.00</span></h5>
                                            <!-- <input type="hidden" class="form-control" name="total" id="grandTotals"> -->
                                            <input type="hidden" class="form-control" name="total" id="grandTotals" value="{{$order->total}}">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary mb-4">Place Order</button>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <button type="submit" class="btn btn-info">Kitchen print</button>
                                        </div> -->
                                        <!-- <div class="col-md-4">
                                            <button type="submit" class="btn btn-warning">Cancel</button>
                                        </div> -->
                                    </div>
                            </div>
                        </div>

                        <!-- Selected Items Summary -->
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h4>Selected Items</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedItems">
                                        <!-- Selected items will be added here dynamically -->
                                         @foreach($order->orderSku as $val)
                                            @if($val->kprint!=2)
                                            <tr>
                                                <td>{{$val->item_name}}</td>
                                                <td>{{$val->quantity}}</td>
                                                <td>৳{{$val->subtotal}}</td>
                                            </tr>
                                            @endif
                                         @endforeach
                                    </tbody>
                                </table>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .item-div{
            border: 3px solid saddlebrown;
            height: fit-content;
        }
        .item-group {
            display: none;
        }
        .number-input {
            display: flex;
            align-items: center;
        }
        .number-input button {
            background-color: #ddd;
            border: none;
            padding: inherit;
            font-size: 20px;
            cursor: pointer;
        }

        .number-input input[type=number] {
            width: 45px;
            text-align: end;
            border: none;
            background: black;
            color: white;
        }

        .number-input .minus {
            border-radius: 16px;
            color: red;
        }

        .number-input .plus {
            border-radius: 16px;
            color: green;
        }
        .category-list .category-item{
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }
        .category-list .category-item:hover {
            background-color: #f0f0f0;
        }

        .category-list .category-item.active {
            background-color: red;
            color: white;
        }
    </style>
@endpush

@push('script')
<script>
        $(document).ready(function () {
            var grandTotals = $('#grandTotals').val();
            $('.item-group').hide();
            // Show items based on selected category
            $('.category-item').on('click', function () {
                const category = $(this).data('category');
                // const item = $(this).data('item');
                // console.log(category);
                $('.item-group').hide();
                $('#' + category).show();
            });

            // quantity handle using button start
            $('.product').each(function() {
                var $product = $(this);
                var pricePerItem = parseFloat($product.data('price'));
                var $quantityInput = $product.find('.quantity-input');
                var quantity = parseInt($quantityInput.val());
                var itemName = $product.data('name');
                
                // Function to update total price for each product
                function updateTotalPrice() {
                    var total = (pricePerItem * quantity).toFixed(2);
                    $product.find('.total-price').val(total);
                    updateSelectedItems(itemName, quantity, total);
                    calculateGrandTotal();
                }

                // Increase quantity
                $product.find('.plus').on('click', function() {
                    quantity++;
                    $quantityInput.val(quantity);
                    updateTotalPrice();
                });

                // Decrease quantity
                $product.find('.minus').on('click', function() {
                    if (quantity > 0) {
                        quantity--;
                        $quantityInput.val(quantity);
                        updateTotalPrice();
                    }
                });

                // Initial total price calculation
                updateTotalPrice();
            });
            // quantity handle using button end
            
            // Calculate item total and update selected items list
            $('.quantity-input').on('input', function () {
                const quantity = $(this).val();
                const price = $(this).data('price');
                const itemName = $(this).data('name');
                const totalElement = $(this).closest('.row').find('.total-price');
                const total = (quantity * price).toFixed(2);
                totalElement.val(total);

                updateSelectedItems(itemName, quantity, total);
                calculateGrandTotal();
            });

            // Function to update selected items list
            function updateSelectedItems(name, quantity, total) {
                const selectedItem = $('#selectedItems').find(`[data-item="${name}"]`);

                if (quantity > 0) {
                    if (selectedItem.length > 0) {
                        selectedItem.find('.selected-quantity').text(quantity);
                        selectedItem.find('.selected-total').text('৳' + total);
                    } else {
                        $('#selectedItems').append(`
                            <tr data-item="${name}">
                                <td>${name}</td>
                                <td class="selected-quantity">${quantity}</td>
                                <td class="selected-total">৳${total}</td>
                            </tr>
                        `);
                    }
                } else {
                    selectedItem.remove();
                }
            }

            // Function to calculate the grand total
            function calculateGrandTotal() {
                let grandTotalF = 0;
                let grandTotal = 0;
                $('.total-price').each(function () {
                    const total = parseFloat($(this).val().replace('৳', ''));
                    if (!isNaN(total)) {
                        grandTotal += total;
                    }
                });
                
                grandTotalF = parseFloat(grandTotal)+parseFloat(grandTotals);
                
                $('#grandTotal').text('৳' + grandTotalF.toFixed(2));
                $('#grandTotals').val(grandTotalF);
            }
        });
    </script>
@endpush
