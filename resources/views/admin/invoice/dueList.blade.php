@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('due_list') }}</h4>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
<!-- Edit Stock Modal Start -->
<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __t('payment') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-validate" action="{{ route('admin.invoice.payment.store')}}" method="POST"
                            enctype="multipart/form-data" id="submitForm">
                @csrf 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin: 0% 10%">
                            <div style="width: 50%;font-size: 14px;display: inline-block">
                                <p style="margin: 0">Invoice No:</p>
                                <p style="margin: 0">Total Amount:</p>
                                <p style="margin: 0">Due Amount:</p>
                            </div>
                            <div style="width: 45%;font-size: 14px;float: right">
                                <p style="margin: 0"><span id="invoice_no"></span></p>
                                <p style="margin: 0">Tk. <span id="grand_total"></span></p>
                                <p style="margin: 0">Tk. <span id="due"></span></p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <hr style="width: 500px;margin-top: 0px;margin-bottom: 0px;">
                        <div class="col-sm-10 row" style="margin: 3% 10%">
                            <div style="width: 50%;font-size: 14px;">
                                <p style="margin: 0">Payable Amount<br><input type="number" name="payable_amount" id="payable_amount" class="form-control w-auto"></p>
                            </div>
                            <div style="width: 45%;font-size: 14px;float: right">
                                <p style="margin: 0">Payment Method<br>
                                <select class="select2 w-auto" name="payment_method">
                                    <option value="Cash">Cash</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Card">Card</option>
                                </select>
                            </p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <input type="hidden" class="form-control" name="order_id" id="order_id" value="" /> 
                        <p id="modalText3" hidden></p>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
    </div>
  </div>
</div>
<!-- Edit Stock Modal End -->
@endsection

@push('script')
<script type="text/javascript">
    function printReport()
    {
        var prtContent = document.getElementById("reportPrinting");
        var WinPrint = window.open();
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>
@endpush

@push('style')
<style>
    .modal-footer{
        justify-content: center!important;
    }
</style>
@include('includes.styles.datatable')
@endpush

@push('script')
<!-- Payment Add start -->
<script>

    function openPaymentModal(button) {
    
    var valueToDisplay2 = button.getAttribute('data-value');
    var modalText2 = document.getElementById("modalText3");

    $.ajax({
        url: '/admin/invoice/due/payment/' + valueToDisplay2,
        method: 'GET',
        // dataType: 'json',
        success: function (response) {
            console.log(response.invoice_no);
            $('#invoice_no').html(response.invoice_no);
            $('#grand_total').html(response.grand_total);
            $('#due').html(response.due);
            $('#payable_amount').val(response.due);
        },
        error: function () {
            alert('Failed to fetch record data.');
        }
    });

    modalText2.innerHTML =  valueToDisplay2;

    $('#order_id').val(valueToDisplay2); 

    var modal = document.getElementById("myPaymentModal");
    modal.style.display = "block";
    }

    function closeModal() {

    var modal = document.getElementById("myPaymentModal");
    modal.style.display = "none";
    }
</script>
<!-- Payment end -->
@include('includes.scripts.datatable')
@endpush