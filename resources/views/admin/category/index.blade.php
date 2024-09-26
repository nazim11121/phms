@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('category') }}</h4>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-validate" action="{{ route('admin.category.store')}}" method="POST" enctype="multipart/form-data">
        @csrf     
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-sm-6 mt-2">
                    <label for="name">Category Name <span class="requiredStar"> *</span></label>
                    <input type="text" class="form-control" name="name" id="name" required>
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div><br>
                <div class="form-group col-sm-6 mt-2">
                    <label for="image">Image</label><br>
                    <label class="custom-file-upload">
                        <input type="file" name="image" id="file-input">Upload File
                        <i class="fas fa-cloud-upload-alt" style="color:green"></i> 
                    </label>

                    @error('image')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" class="form-control" name="order_id" id="order_id" value="" /> 
                <p id="modalText" hidden></p>
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
<!-- Add Category Modal -->

<!-- Edit Category Modal -->
<div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-validate" action="{{ route('admin.category.data.update')}}" method="POST" enctype="multipart/form-data">
        @csrf   
        <div class="modal-body">
            <div class="row">
                
                <div class="form-group col-sm-6 mt-2">
                    <label for="name">Category Name <span class="requiredStar"> *</span></label>
                    <input type="text" class="form-control" name="name" id="name2" required>
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div><br>
                <div class="form-group col-sm-6 mt-2">
                    <label for="image">Image</label><br>
                    <label class="custom-file-upload">
                        <input type="file" name="image" id="file-input">Upload File
                        <i class="fas fa-cloud-upload-alt" style="color:green"></i> 
                    </label>

                    @error('image')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" class="form-control" name="id" id="id" value="" /> 
                <p id="modalText2" hidden></p>
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
<!-- Edit Category Modal -->

@endsection

@push('style')

    <style>
        .header-title{
            color: #213166;
        }
        .table > thead > tr > td{
            color: #213166;
            font-weight: 500;
            font-size: 15px;
        }
        .table > tbody > tr > td{
            font-weight: 500;
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

        .custom-file-upload {
            display: inline-block;
            position: relative;
            cursor: pointer;
            padding: 10px 20px; 
            background-color: #fff; 
            color: black; 
            border-radius: 5px; 
            text-align: center;
            overflow: hidden;
            border: 1px solid #d7ddf5;
        }

        .custom-file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .custom-file-upload i {
            margin-right: 5px; 
        }

        .requiredStar{
            color: red;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            border: none;
        }

        .btn-default:hover{
            color: #333 !important;
            background-color: white!important;
            border: none!important;
            border-color: white!important;
        }

        .form-group .bootstrap-select.btn-group{
            border: 1px solid #ced4da;
        }
        
        .dropdown-toggle::after{
            display: none!important;
        }

        .list-inline>li{
            display: none!important;
        }
    </style>
@endpush

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
console.log(modalText2);
    $.ajax({
        url: 'category/edit/' + valueToDisplay2,
        method: 'GET',
        // dataType: 'json',
        success: function (response) {
            console.log(response);
            $('#name2').val(response.name);
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
@include('includes.styles.datatable')
@endpush

@push('script')
@include('includes.scripts.datatable')

@endpush