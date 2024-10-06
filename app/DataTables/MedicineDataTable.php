<?php

namespace App\DataTables;

use PDF;
use App\Models\MedicineAdd;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * MedicineAddDataTable
 */
class MedicineDataTable extends DataTable
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
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.medicine.stock', $item->id) . '" title="Stock" onclick="openStockUpdateModal(this)" data-toggle="modal" data-target="#myStockUpdateModal" data-value="'.$item->id.'"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.stock') . '</a>';
                }
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.medicine.edit', $item->id) . '" title="Edit" onclick="openEditModal(this)" data-toggle="modal" data-target="#myEditModal" data-value="'.$item->id.'"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.edit') . '</a>';
                }
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<form action="' . route('admin.medicine.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
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
            })->editColumn('brand.name', function ($item) {
                return $item->brand->name ?? '';
            })->editColumn('type.name', function ($item) {
                return $item->type->name ?? '';
            })->editColumn('suplier.name', function ($item) {
                return $item->suplier->name ?? '';
            })->editColumn('buying_price', function ($item) {
                return $item->stock->buying_price ?? '';
            })->editColumn('selling_price', function ($item) {
                return $item->stock->selling_price ?? '';
            })->editColumn('entry.date', function ($item) {
                $date = date('d-m-Y', strtotime($item->stock->created_at));
                return $date ?? '';
            })->editColumn('expired.date', function ($item) {
                $date = date('d-m-Y', strtotime($item->stock->expired_date));
                return $date ?? '';
            })->editColumn('status', function ($item) {
                $badge = $item->status == MedicineAdd::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })->rawColumns(['status', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MedicineAdd $model)
    {
        return $model->newQuery()->orderBy('id','desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['medicine']    = [[1, 'desc']];

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
            Column::make('name', 'name')->title(__('custom.name')),
            Column::make('group.name', 'group.name')->title(__('Group')),
            Column::make('brand.name', 'brand.name')->title(__('Brand')),
            Column::make('type.name', 'type.name')->title(__('Type')),
            Column::make('suplier.name', 'suplier.name')->title(__('Supplier')),
            Column::make('available_stock', 'available_stock')->title(__('Stock(Pc)')),
            Column::make('buying_price', 'buying_price')->title(__('Buying P')),
            Column::make('selling_price', 'selling_price')->title(__('Selling P')),
            Column::make('entry.date', 'entry.date')->title(__('Entry Date')),
            Column::make('expired.date', 'expired.date')->title(__('Expired Date')),
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
        return 'medicine_' . date('YmdHis');
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
