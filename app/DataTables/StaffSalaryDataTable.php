<?php

namespace App\DataTables;

use PDF;
use App\Models\StaffSalary;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * StaffDataTable
 */
class StaffSalaryDataTable extends DataTable
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
            // ->filterColumn('role', function ($query, $keyword) {
            // })
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.staff.salary.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.edit') . '</a>';
                }
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<form action="' . route('admin.staff.salary.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="dropdown-item text-danger delete-list-data"  data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
                    ';
                }

                return '<div class="dropdown btn-group dropup">
                    <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';
            })->editColumn('staff.name', function ($item) {
                return $item->staff->name ?? '';
            })->editColumn('staff.mobile', function ($item) {
                return $item->staff->mobile ?? '';
            })->editColumn('date', function ($item) {
                $date = date('d-m-Y', strtotime($item->created_at));
                return $date ?? '';
            // })->editColumn('role', function ($item) {
            //     return implode(", ", $item->roles->pluck('name')->toArray()) ?? '';
            // })->editColumn('avatar', function ($item) {
            //     return '<img class="img-64" src="' . getStorageImage(Staff::FILE_STORE_PATH, $item->avatar) . '" alt="' . $item->name . '" />';
            // })->editColumn('status', function ($item) {
            //     $badge = $item->status == Staff::Active ? "badge-success" : "badge-danger";
            //     return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })->rawColumns(['status', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StaffSalary $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['staff']    = [[1, 'asc']];

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
            // Column::make('avatar', 'avatar')->title(__('custom.avatar')),
            Column::make('staff.name', 'staff.name')->title(__('custom.name')),
            Column::make('staff.mobile', 'staff.mobile')->title(__('custom.phone')),
            Column::make('salary', 'salary')->title(__('custom.salary')),
            Column::make('payable', 'payable')->title(__('custom.payable')),
            Column::make('payment_method', 'payment_method')->title(__('custom.payment_method')),
            Column::make('month', 'month')->title(__('custom.month')),
            Column::make('date', 'date')->title(__('custom.date')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Staff_' . date('YmdHis');
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
