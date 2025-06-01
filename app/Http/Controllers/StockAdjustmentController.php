<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StockAdjustmentFormRequest;

class StockAdjustmentController extends Controller
{
    public function index(): View
    {
        $adjs = StockAdjustment::paginate(20);
        return view('stock_adjustments.index', compact('adjs'));
    }

    public function create(): View
    {
        return view('stock_adjustments.create')->with('stockAdjustment', new StockAdjustment());
    }

    public function store(StockAdjustmentFormRequest $request): RedirectResponse
    {
        $sa = StockAdjustment::create($request->validated());
        $url = route('stock_adjustments.show', ['stock_adjustment' => $sa]);
        $msg = "Ajuste de stock <a href='$url'><u>#{$sa->id}</u></a> creado correctamente.";
        return redirect()->route('stock_adjustments.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(StockAdjustment $stockAdjustment): View
    {
        return view('stock_adjustments.show', compact('stockAdjustment'));
    }

    public function edit(StockAdjustment $stockAdjustment): View
    {
        return view('stock_adjustments.edit', compact('stockAdjustment'));
    }

    public function update(StockAdjustmentFormRequest $request, StockAdjustment $stockAdjustment): RedirectResponse
    {
        $stockAdjustment->update($request->validated());
        $url = route('stock_adjustments.show', ['stock_adjustment' => $stockAdjustment]);
        $msg = "Ajuste de stock <a href='$url'><u>#{$stockAdjustment->id}</u></a> actualizado correctamente.";
        return redirect()->route('stock_adjustments.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(StockAdjustment $stockAdjustment): RedirectResponse
    {
        try {
            $stockAdjustment->delete();
            $type = 'success';
            $msg  = "Ajuste de stock eliminado correctamente.";
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el ajuste de stock.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}