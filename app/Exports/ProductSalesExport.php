<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSalesExport implements FromCollection, WithHeadings
{
    protected $productId;
    protected $startDate;
    protected $endDate;
    protected $filterType;

    public function __construct($productId, $startDate, $endDate, $filterType = 'units')
    {
        $this->productId = $productId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterType = $filterType;
    }

    public function collection()
    {
        $query = DB::table('items_orders')
            ->join('orders', 'orders.id', '=', 'items_orders.order_id')
            ->where('items_orders.product_id', $this->productId)
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->selectRaw('DATE(orders.created_at) as date');

        if ($this->filterType === 'amount') {
            $query->selectRaw('SUM(items_orders.quantity * items_orders.unit_price) as total');
        } else {
            $query->selectRaw('SUM(items_orders.quantity) as total');
        }

        return $query
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            $this->filterType === 'amount' ? 'Monto total ($)' : 'Unidades vendidas',
        ];
    }
}
