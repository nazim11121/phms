@extends('admin.layouts.master-pdf')

@section('content')
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-top-table-heads">
                <tr>
                    <td class="ic-table-app-name">
                        {{ Config::get('app.name', 'Laravel') }}</td>
                    <td class="text-right">{{ __('custom.invoice_id') }} # {{ make8digits($data->id) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-invoice-table-heads">
    <tr>
        <td class="ic-table-td">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td class="ic-table-inner-td">
                        <P class="ic-table-inner-td"><b>{{ __('custom.billed_to') }}:</b></P>
                        <p class="ic-table-tr-td-text">
                            {{ $data->billing_info['name'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->billing_info['email'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->billing_info['phone_number'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->billing_info['address_line_1'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->billing_info['address_line_2'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->billing_info['zip'] ?? '' }},
                            {{ $data->billing_info['city'] ?? ''}},
                            {{ $data->billing_info['state'] ?? ''}}, {{ $data->billing_info['country'] ?? ''}}</p>
                    </td>
                </tr>
            </table>
        </td>
        <td class="ic-table-td-right" valign="top">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td class="ic-table-text-right">
                        <P class="ic-table-inner-td"><b>{{ __('custom.shipped_to') }}:</b></P>
                        <p class="ic-table-tr-td-text">
                            {{ $data->shipping_info['name'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->shipping_info['email'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->shipping_info['phone_number'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->shipping_info['address_line_1'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->shipping_info['address_line_2'] ?? '' }}</p>
                        <p class="ic-table-tr-td-text">{{ $data->shipping_info['zip'] ?? '' }},
                            {{ $data->shipping_info['city'] ?? ''}},
                            {{ $data->shipping_info['state'] ?? ''}}, {{ $data->shipping_info['country'] ?? ''}}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-invoice-table-heads">
    <tr>
        <td class="ic-table-td">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td class="ic-table-inner-td"><b>{{ __('custom.status') }}:</b></td>
                </tr>
                <tr>
                    <td class="ic-padding">
                        <p class="ic-table-tr-td-text">
                            {{ \App\Models\Invoice::INVOICE_ALL_STATUS[$data->status] }}</p>
                    </td>
                </tr>
            </table>
        </td>
        <td class="ic-table-td-right" valign="top">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td class="ic-table-text-right"><b>{{ __('custom.date') }}:</b></td>
                </tr>
                <tr>
                    <td class="ic-table-text-right">
                        <p class="ic-table-tr-td-text">{{ custom_date($data->date) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-invoice-table-heads">
    <tr>
        <td class="ic-table-td">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td class="ic-table-inner-td"><b>{{ __('custom.summary') }}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-invoice-table-heads">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="ic-table-fixed-layout">
                <thead>
                    <tr>
                        <th class="ic-table-th">
                            {{ __('custom.sku') }}</th>
                        <th width="40%" class="ic-table-th ">
                            {{ __('custom.name') }}</th>
                        <th class="ic-table-th ic-table-th-text-center">
                            {{ __('custom.quantity') }}</th>
                        <th class="ic-table-th">
                            {{ __('custom.price') }}</th>
                        <th class="ic-table-th">
                            {{ __('custom.discount') }}</th>
                        <th class="ic-table-th ic-table-th-text-right">
                            {{ __('custom.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data->items))
                    @foreach ($data->items as $item)
                    <tr>
                        <td class="ic-table-td-style">
                            <b>{{ $item->sku ?? '' }}</b>
                        </td>
                        <td width="40%" class="ic-table-td-style">
                            <b>{{ $item->product_name ?? '' }}</b>
                        </td>
                        <td class="ic-table-td-style">
                            <b>{{ $item->quantity ?? '' }}</b>
                        </td>
                        <td class="ic-table-td-style">
                            <b>{{ $item->price ?? '' }}</b>
                        </td>
                        <td class="ic-table-td-style">
                            <b>{{ $data->discount }}
                                @if($data->discount_type ==
                                \App\Models\Invoice::DISCOUNT_PERCENT)
                                %
                                @endif</b>
                        </td>
                        <td class="ic-table-td-style">
                            <b>{{ $item->sub_total ?? '' }}</b>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan="4"></td>
                        <td class="ic-table-inner-td"><b>{{ __('custom.discount') }}</b></td>
                        <td class="ic-table-text-right">
                            <b>{{ $data->discount_amount }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td class="ic-table-inner-td"><b>{{ __('custom.tax') }}Tax</b></td>
                        <td class="ic-table-text-right">
                            <b>{{ $data->tax_amount }}</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"></td>
                        <td class="ic-table-inner-td"><b>{{ __('custom.total') }}Total</b></td>
                        <td class="ic-table-text-right">
                            <b>{{ $data->total }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
@endsection

@push('style')

@endpush

@push('script')

@endpush