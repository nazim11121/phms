@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('medicine_list') }}</h4>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
<!-- Edit Medicine Modal Start -->
<div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __t('edit_medicine') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-validate" action="{{ route('admin.medicine.data.update')}}" method="POST" enctype="multipart/form-data">
        @csrf   
        <div class="modal-body">
            <div class="row">
                
                <div class="form-group col-sm-12 mt-2">
                    <label for="name">Medicine Name <span class="requiredStar"> *</span></label>
                    <input type="text" class="form-control" name="name" id="name2" required>
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div><br>
                
            </div></br>
            <div class="row"> 
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Group Name <span class="requiredStar"> *</span></label>
                  <select class="form-control select2" name="group_id" id="group_id" required>
                  </select>
                  @error('group_id')
                  <p class="error">{{ $message }}</p>
                  @enderror
              </div><br>
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Brand Name<span class="requiredStar"> *</span></label>
                  <select class="form-control select2" name="brand_id" id="brand_id" required>
                    </select>
                  @error('brand_id')
                  <p class="error">{{ $message }}</p>
                  @enderror
              </div><br>
            </div> 
            <div class="row"> 
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Type<span class="requiredStar"> *</span></label>
                  <select class="form-control select2" name="type_id" id="type_id" required>
                  </select>
                  @error('type_id')
                  <p class="error">{{ $message }}</p>
                  @enderror
              </div><br>
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Suplier Name</label>
                  <select class="form-control select2" name="suplier_id" id="suplier_id">
                    </select>
                  @error('suplier_id')
                  <p class="error">{{ $message }}</p>
                  @enderror
              </div><br>
            </div> 
            <div class="row"> 
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Quantity<span class="requiredStar"> *</span></label>
                  <input type="number" class="form-control" name="quantity" id="quantity" required>
                  @error('quantity')
                  <p class="error">{{ $message }}</p>
                  @enderror
              </div><br>
              <div class="form-group col-sm-6 mt-2">
                  <label for="name">Expired Date</label>
                  <input type="date" class="form-control" name="expired_date" id="expired_date">
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
                <div class="form-inline col-sm-6 mt-4">
                    <input type="checkbox" class="form-control" name="status" id="status" value="Active" style="width: 30px"> Status
                    @error('status')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" class="form-control" name="id" id="id" value="" /> 
                <p id="modalText2" hidden></p>
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
<!-- Edit Medicine Modal End -->
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
<!-- Medicine Edit start -->
  <script>

    function openEditModal(button) {
      
      var valueToDisplay2 = button.getAttribute('data-value');
      var modalText2 = document.getElementById("modalText2");

      $.ajax({
          url: 'medicine/' + valueToDisplay2+'/edit/',
          method: 'GET',
          // dataType: 'json',
          success: function (response) {
              // console.log(response);
              $('#name2').val(response.medicine.name);
              $('#quantity').val(response.medicine.available_stock);
              $('#buying_price').val(response.medicine.stock.buying_price);
              $('#selling_price').val(response.medicine.stock.selling_price);
              $('#expired_date').val(response.medicine.stock.expired_date);
              const pregroup = response.medicine.group_id;
              const select1 = $('#group_id');
                  select1.empty(); // Clear current options
                  response.groups.forEach(function(option) {
                      select1.append(`<option value="${option.id}" ${option.id == pregroup ? 'selected' : ''}>${option.name}</option>`)
              });
              const prebrand = response.medicine.brand_id;
              const select2 = $('#brand_id');
                  select2.empty(); // Clear current options
                  response.brands.forEach(function(option) {
                      select2.append(`<option value="${option.id}" ${option.id == prebrand ? 'selected' : ''}>${option.name}</option>`)
              });
              const pretype = response.medicine.type_id;
              const select3 = $('#type_id');
                  select3.empty(); // Clear current options
                  response.types.forEach(function(option) {
                      select3.append(`<option value="${option.id}" ${option.id == pretype ? 'selected' : ''}>${option.name}</option>`)
              });
              const presuplier = response.medicine.suplier_id;
              const select4 = $('#suplier_id');
                  select4.empty(); // Clear current options
                  response.supliers.forEach(function(option) {
                      select4.append(`<option value="${option.id}" ${option.id == presuplier ? 'selected' : ''}>${option.name}</option>`)
              });
              if(response.medicine.status == 'Active'){
                $('#status').prop('checked', response.medicine.status);
              }
          },
          error: function () {
              alert('Failed to fetch record data.');
          }
      });

      modalText2.innerHTML =  valueToDisplay2;

      $('#id').val(valueToDisplay2); 

      var modal = document.getElementById("myEditModal");
      modal.style.display = "block";
    }

    function closeModal() {
    
      var modal = document.getElementById("myEditModal");
      modal.style.display = "none";
    }
  </script>
<!-- Medicine Edit end -->
<!-- Stock Edit start -->
  <script>

    function openStockUpdateModal(button) {
      
      var valueToDisplay2 = button.getAttribute('data-value');
      var modalText2 = document.getElementById("modalText3");

      $.ajax({
          url: 'medicine/stock/' + valueToDisplay2,
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
<!-- Medicine Edit end -->
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
@include('includes.scripts.datatable')
@endpush