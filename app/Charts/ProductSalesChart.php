<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Order;

class ProductSalesChart extends Chart
{
    public function __construct()
    {
        parent::__construct();
    }

    public function build($productId, $startDate, $endDate)
    {
        $query = Order::query()
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->where('product_id', $productId)
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $query->pluck('date');
        $data = $query->pluck('total');

        return $this->labels($labels)
            ->dataset('Ventas por día', 'line', $data)
            ->backgroundColor('rgba(54, 162, 235, 0.5)')
            ->color('blue');
    }
}

