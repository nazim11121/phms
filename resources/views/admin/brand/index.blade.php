@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('brand_list') }}</h4>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
<!-- Edit Category Modal -->
<div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __t('edit_brand') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-validate" action="{{ route('admin.brand.data.update')}}" method="POST" enctype="multipart/form-data">
        @csrf   
        <div class="modal-body">
            <div class="row">
                
                <div class="form-group col-sm-6 mt-2">
                    <label for="name">Brand Name <span class="requiredStar"> *</span></label>
                    <input type="text" class="form-control" name="name" id="name2" required>
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div><br>
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
<!-- Edit Category Modal -->

@endsection

@push('script')

<script type="text/javascript">
  $(document).ready(function () {
    $('#example').DataTable();
  });
</script>
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
<script>

  function openModal(button) {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
  }

  function closeModal() {
   
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
  }

</script>
<script>

  function openEditModal(button) {
    
    var valueToDisplay2 = button.getAttribute('data-value');
    var modalText2 = document.getElementById("modalText2");

    $.ajax({
        url: 'brand/' + valueToDisplay2+'/edit/',
        method: 'GET',
        // dataType: 'json',
        success: function (response) {
            // console.log(response);
            $('#name2').val(response.name);
            if(response.status == 'Active'){
              $('#status').prop('checked', response.status);
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