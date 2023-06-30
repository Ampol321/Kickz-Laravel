<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->addColumn('action',  function ($row) {
                $actionBtn = '<form method="POST" action="' . route('order.update', $row->id) . '">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" class="btn btn-success">Update</button>
                </form>';
                return $actionBtn;
        })
        ->addColumn('name', function ($row) {
            return $row->user->name;
        })
        ->addColumn('shipment_name', function ($row) {
            return $row->shipment->shipment_name;
        })
        ->addColumn('payment_name', function ($row) {
            return $row->payment->payment_name;
        })  
        ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        // $orders = DB::table('orders')
        //     ->join('users', 'users.id', '=', 'orders.user_id')
        //     ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
        //     ->join('payments', 'payments.id', '=', 'orders.payment_id')
        //     ->select(
        //         'orders.*',
        //         'users.name',
        //         'shipments.shipment_name',
        //         'shipments.shipment_cost',
        //         'payments.payment_name'
        //     )
        //     ->where('status', 'On Delivery');
        $orders = Order::with(['user','shipment','payment'])
        ->where('status', 'On Delivery')
        ->get();

        return $orders;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('orders-table')
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
                ->title('Order ID'),
            Column::make('name')
                ->title('Customer')
                ->searchable(false),
            Column::make('shipment_name')
                ->title('Shipment Type')
                ->searchable(false),
            Column::make('payment_name')
                ->title('Payment Type')
                ->searchable(false),
            // Column::make('credit_card'),
            Column::make('shipping_address'),
            Column::make('status'),
            Column::make('date_ordered'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
