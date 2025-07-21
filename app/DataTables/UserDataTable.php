<?php

namespace App\DataTables;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $first_user = User::first()->id;

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->longRelativeDiffForHumans();
            })
            ->editColumn('updated_at', function ($data) {
                return Carbon::parse($data->updated_at)->longRelativeDiffForHumans();
            })
            ->editColumn('status', function ($data) {
                if ($data->status) {
                    return '<span class="badge bg-success">Active</span>';
                }

                return '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($data) use ($first_user) {
                $html = '';
                if ($data->id !== $first_user) {
                    $html .= '<div class="btn-group">';
                    $html .= getEditButton(route('user.edit', ['user' => $data->id]), "", "", "user-edit");
                    $html .= getDeleteButton("javascript:;", "deleteModel", "data-id=\"$data->id\"", "user-delete");
                    $html .= '</div>';
                }
                return $html;
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->addTableClass(['table-striped', 'table-nowrap', 'datatable'])
            ->lengthMenu([100, 200, 500, 1000])
            ->minifiedAjax()
            ->searching(true)
            ->serverSide(true)
            ->scrollX(true)
            ->columns($this->getColumns());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->orderable(false)
                ->searchable(false)
                ->title('#'),

            Column::make('name')
                ->title('Name'),

            Column::make('email')
                ->title('Email'),

            Column::make('phone')
                ->title('Phone'),

            Column::make('status')
                ->title('Status'),

            Column::make('created_at')
                ->title('Created At'),

            Column::make('updated_at')
                ->title('Updated At'),

            Column::make('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
