<?php

namespace App\DataTables;

use PDF;
use App\Models\Stock;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use DB;

/**
 * UserDataTable
 */
class StockListDataTable extends DataTable
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
            })->editColumn('medicine.name', function ($item) {
                return $item->medicine->name ?? ''; 
            })->editColumn('created_at', function ($item) {
                $date = date('d-m-Y', strtotime($item->created_at));
                return $date ?? '';   
            })->editColumn('expired_date', function ($item) {
                $date = date('d-m-Y', strtotime($item->expired_date));
                return $date ?? '';  
            })->rawColumns(['created_at','expired_date','action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Stock $model)
    {
        // return $model->newQuery()->distinct('medicine_id')->orderBy('medicine_id','ASC')->latest('created_at');
        $latestSellings = DB::table('stocks')
            ->select('medicine_id','previous', DB::raw('MAX(id) as latest_id')) // Get the last (latest) entry for each medicine_id
            ->groupBy('medicine_id')
            ->get();

        // Now, fetch the full details of the latest entries
        return $model->newQuery()->with('medicine')->whereIn('id', $latestSellings->pluck('latest_id'));
    
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['stock']    = [[1, 'desc']];

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->addAction(['width' => '100%', 'class' => 'text-center', 'printable' => false, 'exportable' => false, 'title' => 'ACTION'])
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
            Column::make('medicine.name', 'medicine.name')->title(__('Medicine Name')),
            Column::make('previous', 'previous')->title(__('custom.previous')),
            Column::make('new', 'new')->title(__('custom.new')),
            Column::make('available_stock', 'available_stock')->title(__('custom.available_stock')),
            Column::make('buying_price', 'buying_price')->title(__('custom.buying_price')),
            Column::make('selling_price', 'selling_price')->title(__('custom.selling_price')),
            Column::make('created_at', 'created_at')->title(__('custom.entry_date')),
            Column::make('expired_date', 'expired_date')->title(__('custom.expired_date')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Stock_' . date('YmdHis');
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
