<?php

namespace App\DataTables;

use PDF;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * InvoiceDataTable
 */
class InvoiceDataTable extends DataTable
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
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoice.edit', $item->id) . '" title="Edit" onclick="openEditModal(this)" data-toggle="modal" data-target="#myEditModal" data-value="'.$item->id.'"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.edit') . '</a>';
                }
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<form action="' . route('admin.invoice.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="dropdown-item text-danger delete-list-data"  data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
                    ';
                }

                return '<div class="dropdown btn-type dropup">
                    <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';
            })->editColumn('group.name', function ($item) {
                return $item->group->name ?? '';
            })->editColumn('sku', function ($item) {
                $data = '';
                foreach ($item->sku as $value) {
                    
                    $data .= $value->quantity .'x'. $value->medicine->name . '<br>';
                }
                return $data;
            })->editColumn('entry.date', function ($item) {
                $date = date('d-m-Y', strtotime($item->created_at));
                return $date ?? '';
            })->rawColumns(['sku', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        return $model::with('sku')->newQuery()->orderBy('id','desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['invoice']    = [[1, 'desc']];

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
            Column::make('grand_total', 'grand_total')->title(__('Total')),
            Column::make('payable_amount', 'payable_amount')->title(__('Paid Amount')),
            Column::make('payment_method', 'payment_method')->title(__('Method')),
            Column::make('payment_status', 'payment_status')->title(__('Status')),
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
        return 'invoice_' . date('YmdHis');
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
