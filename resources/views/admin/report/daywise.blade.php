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
                    <h4 class="header-title">{{ __('Day Wise Reports') }}</h4><br>
                </div>
                <div class="row mt-4 ml-4 mb-4 no-print">
                    <form class="d-flex mb-2" id="filter-form">
                        <label for="start_date">Date: </label>
                        <input type="text" class="form-control datepicker ml-3" autocomplete="off" id="from_date" name="start_date" required>          
                        <button type="submit" class="btn btn-info ml-3">Filter</button>
                    </form>
                    <div class="col-sm-6">
                        <button id="printBtn" class="btn btn-success ml-4 float-right">Print Report</button>
                    </div>
                </div>
                <div id="printSection">
                    <div class="text-center mt-4">
                        <h4 style="text-align:center;">{{$shopName}}</h4>
                        <h5 style="text-align:center;">{{$shopAddress}}</h5>
                        <h5 style="text-align:center;">{{$shopMobile}}</h5>
                        <h5 style="text-align:center;" id="filterDate"></h5>
                    </div>
                    <div class="col-sm-12">
                        <div class="card text-white bg-primary mb-3" style="max-width: 22rem;margin:auto">
                            <div class="card-header">
                                <div class="form-inline">
                                    <h6 class="card-title">Invoice(<span id="q_table">0</span>)</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="t_table">00</span></h6>
                                </div>
                                <!-- <div class="form-inline">
                                    <h6 class="card-title">New Order(<span id="q_new">0</span>)</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="n_table">00</span></h6>
                                </div>
                                <div class="form-inline">
                                    <h6 class="card-title">Parcel Order(<span id="q_parcel">0</span>)</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="t_parcel">00</span></h6>
                                </div>
                                <div class="form-inline">
                                    <h6 class="card-title">Online Delivery(<span id="q_delivery">0</span>)</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="t_delivery">00</span></h6>
                                </div> -->
                                <hr style="border: 1px solid white">
                                <div class="form-inline">
                                    <h6 class="card-title">Total</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="all_total">00</span></h6>
                                </div>
                                <hr style="border: 1px solid white">
                                <div class="form-inline cash" style="display:none">
                                    <h6 class="card-title">Cash</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="cash">00</span></h6>
                                </div>
                                <div class="form-inline bkash" style="display:none">
                                    <h6 class="card-title">Bkash</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="bkash">00</span></h6>
                                </div>
                                <div class="form-inline rocket" style="display:none">
                                    <h6 class="card-title">Rocket</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="rocket">00</span></h6>
                                </div>
                                <div class="form-inline nagad" style="display:none">
                                    <h6 class="card-title">Nagad</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="nagad">00</span></h6>
                                </div>
                                <div class="form-inline cards" style="display:none">
                                    <h6 class="card-title">Card</h6>
                                    <h6 class="card-title" style="margin-left: auto;">৳ <span id="card">00</span></h6>
                                </div>
                                <hr style="border: 1px solid white">
                            </div>
                            
                            <div class="card-body" style="margin-top: -40px;">
                                <h5 class="card-title">Product List</h5>
                                <p class="card-text" id="report-table"></p>
                            </div>
                        </div>
                    </div>
                    <!-- <table id="report-table" class="table table-border table-striped">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Category</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table> -->
                    <!-- <div>Total: <span id="total"></span></div> -->
                    <div class="endPrint"  style="font-size:12px;text-align:center">
                        <p>Powered by N&N Co.</p>
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
<script>
        $(document).ready(function() {
            // Initialize DataTable
            // var table = $('#report-table').DataTable();

            // Handle form submission to filter data
            $('#filter-form').on('submit', function(event) {
                event.preventDefault();

                let startDate = $('#from_date').val();

                // Fetch data via AJAX
                $.ajax({
                    url: "{{ route('admin.reports.data') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate
                    },
                    success: function(data) {
                        let itemBody = $('#report-table');
                        itemBody.empty(); 
                    
                        $('#t_table').text(data.t_table);
                        $('#q_table').text(data.q_table);
                        $('#all_total').text(data.all_total);
                        $('#cash').text(data.cash);
                        $('#bkash').text(data.bkash);
                        $('#rocket').text(data.rocket);
                        $('#nagad').text(data.nagad);
                        $('#card').text(data.card);
                      
                        if(data.cash>0){
                            $('.cash').css('display','flex');
                        } 
                        if(data.bkash>0){
                            $('.bkash').css('display','flex');
                        }
                        if(data.rocket>0){
                            $('.rocket').css('display','flex');
                        }
                        if(data.nagad>0){
                            $('.nagad').css('display','flex');
                        }
                        if(data.card>0){
                            $('.cards').css('display','flex');
                        }

                        data.brands.forEach(value1 => {
                            
                            if(value1.medicine!=''){
                                var itemBody = '<p style="margin-bottom: auto;color:red">'+value1.name+'</p>';
                            }  
                            value1.medicine.forEach(value => {
                                
                                if(value.sku_sum_quantity!=null){
                                    itemBody+='<tr><td>'+value.sku_sum_quantity+'x'+value.name+ '</td></tr></br>';
                                }   
                            });
                            
                            $('#report-table').append(itemBody);
                               
                            // let totals = report.total;
                            // $('#total').text('৳ ' + totals);
                            var dateParts = startDate.split('-');
                            var year = dateParts[0];
                            var month = dateParts[1];
                            var day = dateParts[2];
                            var formattedDate = day + '-' + month + '-' + year;
                            $('#filterDate').text(formattedDate);
                        });
                    }
                });
            });

            // Print button functionality
            $('#printBtn').on('click', function() {
                window.print();
            });
        });
    </script>


@endpush