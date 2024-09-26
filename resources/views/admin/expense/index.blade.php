@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('category') }}</h4>
                <table class="table table-border">
                  <thead>
                    <tr>
                      <td>Name</td>
                      <td>Comment</td>
                      <td>Amount</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                            @foreach($data as $value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->comments }}</td>
                                    <td>{{ $value->amount }}</td>
                                    <td class="action-column">
                                      
                                        <a class="mr-3 btn btn-sm btn-primary" href="{{route('admin.expense.edit', $value->id)}}"><i class="fas fa-edit"></i>Edit</a>
                                    
                                            {!! Form::open(['method' => 'DELETE','route' => ['admin.expense.destroy', $value->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-default" data-from-text = "You won\'t be able to revert this! <br/> " data-from-name="" data-from-id=""  title="Delete"><i class="mdi mdi-trash-can-outline"></i>Delete</button> 
                                          
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')

@endpush

@push('script')



@endpush