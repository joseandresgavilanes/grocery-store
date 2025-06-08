<?php
namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplyOrderController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', SupplyOrder::class);
        $orders = SupplyOrder::with('product')->paginate(20);
        return view('supply-orders.index', compact('orders'));
    }

    public function create(): View
    {
        $this->authorize('create', SupplyOrder::class);
        return view('supply-orders.create', [
            'order'=> new SupplyOrder(),
            'products'=>\App\Models\Product::all(),
        ]);
    }

    public function store(Request $r)
    {
        $this->authorize('create', SupplyOrder::class);
        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
        ]);
        SupplyOrder::create(array_merge($data,['status'=>'requested']));
        return redirect()->route('supply-orders.index')
            ->with('alert-type','success')
            ->with('alert-msg','Supply order creada');
    }

    public function complete(SupplyOrder $supplyOrder)
    {
        $this->authorize('complete', $supplyOrder);

        DB::transaction(function() use ($supplyOrder) {
            $supplyOrder->update(['status'=>'completed']);
            $supplyOrder->product
                        ->increment('stock',$supplyOrder->quantity);
        });

        return back()
            ->with('alert-type','success')
            ->with('alert-msg',"SupplyOrder #{$supplyOrder->id} completada");
    }

    public function destroy(SupplyOrder $supplyOrder)
    {
        $this->authorize('delete', $supplyOrder);
        $supplyOrder->delete();
        return back()
            ->with('alert-type','success')
            ->with('alert-msg',"SupplyOrder #{$supplyOrder->id} eliminada");
    }
}