<?php

namespace App\DataTables;

use PDF;
use App\Models\Suplier;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * SuplierDataTable
 */
class SuplierDataTable extends DataTable
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
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.suplier.edit', $item->id) . '" title="Edit" onclick="openEditModal(this)" data-toggle="modal" data-target="#myEditModal" data-value="'.$item->id.'"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.edit') . '</a>';
                }
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<form action="' . route('admin.suplier.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="dropdown-item text-danger delete-list-data"  data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   Suplier="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
                    ';
                }

                return '<div class="dropdown btn-type dropup">
                    <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';
            })->editColumn('brand.name', function ($item) {
                return $item->brand->name ?? '';
            })->editColumn('status', function ($item) {
                $badge = $item->status == Suplier::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })->rawColumns(['status', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Suplier $model)
    {
        return $model->newQuery()->with('brand')->orderBy('id','desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['suplier']    = [[1, 'desc']];

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
            Column::make('brand.name', 'brand.name')->title(__('Brand Name')),
            Column::make('name', 'name')->title(__('custom.name')),
            Column::make('mobile', 'mobile')->title(__('custom.name')),
            Column::make('status', 'status')->title(__('custom.status')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Supplier_' . date('YmdHis');
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
