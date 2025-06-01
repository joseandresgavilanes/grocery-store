<?php

namespace App\Http\Controllers;

use App\Models\SettingsShippingCosts;
use Illuminate\Http\Request;

class SettingsShippingCostsController extends Controller
{
    public function index(): View
    {
        $costs = SettingsShippingCosts::paginate(20);
        return view('shipping_costs.index', compact('costs'));
    }

    public function create(): View
    {
        return view('shipping_costs.create')->with('shippingCost', new SettingsShippingCosts());
    }
    public function store(SettingsShippingCostsFormRequest $request): RedirectResponse
    {
        $cost = SettingsShippingCosts::create($request->validated());
        $url  = route('shipping_costs.show', ['shipping_cost' => $cost]);
        $msg  = "Tramo de envío <a href='$url'><u>{$cost->min_value_threshold}–{$cost->max_value_threshold}</u></a> creado correctamente.";
        return redirect()->route('shipping_costs.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(SettingsShippingCosts $shippingCost): View
    {
        return view('shipping_costs.show', compact('shippingCost'));
    }

    public function edit(SettingsShippingCosts $shippingCost): View
    {
        return view('shipping_costs.edit', compact('shippingCost'));
    }

    public function update(SettingsShippingCostsFormRequest $request, SettingsShippingCosts $shippingCost): RedirectResponse
    {
        $shippingCost->update($request->validated());
        $url  = route('shipping_costs.show', ['shipping_cost' => $shippingCost]);
        $msg  = "Tramo de envío <a href='$url'><u>{$shippingCost->min_value_threshold}–{$shippingCost->max_value_threshold}</u></a> actualizado correctamente.";
        return redirect()->route('shipping_costs.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(SettingsShippingCosts $shippingCost): RedirectResponse
    {
        try {
            $shippingCost->delete();
            $type = 'success';
            $msg  = "Tramo de envío eliminado correctamente.";
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el tramo de envío.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}
