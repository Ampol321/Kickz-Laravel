<?php

namespace App\DataTables;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BrandsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {   
        return datatables()
            ->eloquent($query)
            ->addColumn('img_path', function($row) {
                $imageSrc = asset($row->img_path);
                $image = '<img src="' . $imageSrc . '" width="100px" height="100px">';
                return $image;
            })
            ->addColumn('action',  function($row){
                $actionBtn = '<a href="'.route('brand.edit',$row->id).'"class="edit btn btn-success">Edit</a> 
                <form method="POST" action="'.route('brand.destroy',$row->id).'">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" class="btn btn-danger">Delete</button>
                </form>';
                return $actionBtn; 
            })->rawColumns(['img_path','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Brand $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('brands-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                    ->title('Brand ID'),
            Column::make('img_path'),
            Column::make('brand_name'),
            Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(150)
                    ->addClass('text-center'),
            // Column::make('id'),
            // Column::make('add your columns'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Brands_' . date('YmdHis');
    }
}
