<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Barryvdh\Debugbar\Facades\Debugbar;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->addColumn('product_img', function ($row) {
                $imageSrc = asset($row->product_img);
                $image = '<img src="' . $imageSrc . '" width="100px" height="100px">';
                return $image;
            })
            ->addColumn('action',  function ($row) {
                $actionBtn = '<a href="' . route('product.edit', $row->id) . '"class="edit btn btn-success">Edit</a> 
                <form method="POST" action="' . route('product.destroy', $row->id) . '">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" class="btn btn-danger">Delete</button>
                </form>';
                return $actionBtn;
            })
            ->addColumn('brand_name', function ($row) {
                return $row->brand->brand_name;
            })
            ->addColumn('type_name', function ($row) {
                return $row->type->type_name;
            })
            ->addColumn('stocks', function ($row) {
                return $row->stock ? $row->stock->stock : "0";
            })
            ->rawColumns(['product_img', 'action']);
            
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        // $products = DB::table('products')
        //     ->join('brands', 'brands.id', "=", 'products.brand_id')
        //     ->join('types', 'types.id', "=", 'products.type_id')
        //     ->join('stocks', 'stocks.product_id', "=", 'products.id')
        //     ->select('products.*', 'brands.brand_name', 'types.type_name', 'stocks.stock');

        $products = Product::with(['brand', 'type', 'stock'])
            ->get();

        Debugbar::info($products);

        return $products;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('products-table')
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
    public function getColumns()
    {
        return [
            Column::make('id')
                ->title('Product Id'),
            Column::make('product_img'),
            Column::make('product_name'),
            Column::make('colorway'),
            Column::make('size'),
            Column::make('price'),
            Column::make('brand_name')
                ->searchable(false),
            Column::make('type_name')
                ->searchable(false),
            Column::make('stocks')
                ->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
