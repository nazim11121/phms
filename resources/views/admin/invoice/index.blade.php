@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="float-left header-title">{{ __t('invoice_list') }}</h4>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
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
@include('includes.styles.datatable')
@endpush

@push('script')
@include('includes.scripts.datatable')
@endpush