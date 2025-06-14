<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductSalesExport;

class StatsController extends Controller
{
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            if (auth()->user()?->type !== 'board') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get();

        $selectedProduct = $request->input('product_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterType = $request->input('filter_type', 'units');

        $salesChart = null;

        if ($selectedProduct && $startDate && $endDate) {
            $sales = DB::table('items_orders')
                ->selectRaw('DATE(orders.created_at) as date, ' .
                    ($filterType === 'amount'
                        ? 'SUM(items_orders.quantity * items_orders.unit_price) as total'
                        : 'SUM(items_orders.quantity) as total'))
                ->join('orders', 'orders.id', '=', 'items_orders.order_id')
                ->where('items_orders.product_id', $selectedProduct)
                ->where('orders.status', 'completed')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $salesChart = (new LarapexChart)->lineChart()
                ->setTitle($filterType === 'amount' ? 'Monto de ventas por día' : 'Unidades vendidas por día')
                ->setXAxis($sales->pluck('date')->toArray())
                ->addData($filterType === 'amount' ? 'Monto total ($)' : 'Unidades vendidas', $sales->pluck('total')->toArray());
        }

        return view('stats.index', compact(
            'products',
            'selectedProduct',
            'startDate',
            'endDate',
            'salesChart',
            'filterType'
        ));
    }

    public function export(Request $request)
    {
        $selectedProduct = $request->input('product_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterType = $request->input('filter_type', 'units');

        if (!$selectedProduct || !$startDate || !$endDate) {
            return redirect()->route('stats.index')->with('error', 'Debes seleccionar producto y fechas para exportar.');
        }

        return Excel::download(
            new ProductSalesExport($selectedProduct, $startDate, $endDate, $filterType),
            'ventas_producto_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
