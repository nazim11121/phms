<?php

namespace App\DataTables;

use PDF;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * DueDataTable
 */
class DueDataTable extends DataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($item) {
                $buttons = '';

                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoice.payment', $item->id) . '" title="Payment" onclick="openPaymentModal(this)" data-toggle="modal" data-target="#myPaymentModal" data-value="'.$item->id.'"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.payment') . '</a>';
                }

                return '<div class="dropdown btn-type dropup">
                    <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';
            })->editColumn('customer', function ($item) {
                
                return $item->customer_name. '<br>' .$item->customer_mobile;

            })->editColumn('sku', function ($item) {
                $data = '';
                foreach ($item->sku as $value) {
                    
                    $data .= $value->quantity .'x'. $value->medicine->name . '<br>';
                }
                return $data;
            })->editColumn('entry.date', function ($item) {
                $date = date('d-m-Y', strtotime($item->created_at));
                return $date ?? '';
            })->rawColumns(['sku', 'customer', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        return $model->newQuery()->where('due','!=','0')->orderBy('id','desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['due']    = [[1, 'desc']];

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '100%', 'class' => 'text-center', 'printable' => false, 'exportable' => false, 'title' => 'ACTION'])
            ->parameters($params);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', __('custom.sl')),
            Column::make('invoice_no', 'invoice_no')->title(__('custom.invoice_no')),
            Column::make('sku', 'sku')->title(__('Product')),
            Column::make('customer', 'customer')->title(__('Customer')),
            Column::make('grand_total', 'grand_total')->title(__('Total')),
            Column::make('payable_amount', 'payable_amount')->title(__('Paid Amount')),
            Column::make('due', 'due')->title(__('Due')),
            Column::make('payment_method', 'payment_method')->title(__('Method')),
            // Column::make('payment_status', 'payment_status')->title(__('Status')),
            Column::make('entry.date', 'entry.date')->title(__('Date')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'due_invoice_' . date('YmdHis');
    }

    /**
     * pdf
     *
     * @return void
     */
    public function pdf()
    {
        $data = $this->getDataForExport();

        $pdf = PDF::loadView('vendor.datatables.print', [
            'data' => $data
        ]);
        return $pdf->download($this->getFilename() . '.pdf');
    }
}
