@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.staff') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.staff_list') }}</li>
            </ol> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('Edit Purchase List') }}</h4>
                <div class="table-responsive">
                    <form action="{{ route('admin.purchase.update', $purchase_no) }}" method="post" enctype="multipart/form-data">
                    @csrf
					@method('put')
						<input type="hidden" name="purchase_no" value="{{$purchase_no}}">
                        <table id="example" class="table table-border">
                            <thead>
                                <tr>
                                    <td>Sl</td>
                                    <td>Medicine Name</td>
                                    <td>Quantity</td>
                                    <td style="text-align:center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
								@foreach($medicineList as $key=>$value)
									@php
										foreach($purchase as $value1){
											$purchaseData = $value1->where('medicine_id', $value->id)->first();
										}
									@endphp
									<tr><?php //var_dump($purchaseData);exit;?>
										<td>{{++$key}}</td>
										<td>{{$value->name}}</td>
										
										<td><input type="number" class="form-control" name="quantities[{{ $value->id }}]" id="quantity" @if(!empty($purchaseData->purchase_no) && $purchase_no == $purchaseData->purchase_no??'') value="{{$purchaseData ? $purchaseData->quantity:1}}" @endif></td>
										<td><input type="checkbox" class="form-control" name="medicines[]" value="{{ $value->id }}" @if(!empty($purchaseData->purchase_no) && $purchase_no == $purchaseData->purchase_no??''){{ $purchaseData->medicine_id ? 'checked' : '' }}@endif></td>
										
									</tr>
								@endforeach
                            </tbody>
                        </table>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>    
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
@endpush

@push('script')

<script type="text/javascript">
  $(document).ready(function () {
    $('#example').DataTable();
  });
</script>
@endpush