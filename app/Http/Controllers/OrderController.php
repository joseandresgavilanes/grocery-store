<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OrderFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{

    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }


    public function pending()
    {

        $orders = Order::with('member')
            ->where('status','pending')
            ->paginate(20);

        return view('orders.pending', compact('orders'));
    }

    /** 5.2. Completar (solo employee y con stock) */
    public function complete(Order $order)
    {

        foreach ($order->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()
                    ->with('alert-type','warning')
                    ->with('alert-msg',"No hay stock suficiente de “{$item->product->name}”");
            }
        }

        DB::transaction(function() use ($order) {
            $order->update(['status'=>'completed']);

            // PDF + guardar ruta
            $pdf  = PDF::loadView('orders.receipt', compact('order'));
            $path = "receipts/order_{$order->id}.pdf";
            \Storage::disk('public')->put($path, $pdf->output());
            $order->update(['pdf_receipt'=>$path]);

            // email
            Mail::to($order->member->email)
                ->send(new OrderCompletedMail($order));

            // restar stock
            foreach ($order->items as $item) {
                $item->product->decrement('stock',$item->quantity);
            }
        });

        return redirect()
            ->route('orders.pending')
            ->with('alert-type','success')
            ->with('alert-msg',"Pedido #{$order->id} completado");
    }

    /** 5.3. Cancelar (solo board) */
    public function cancel(Request $request, Order $order)
    {

        abort_if($order->status!=='pending', 400, 'Solo pendientes pueden cancelarse');

        DB::transaction(function() use($order,$request) {
            $order->update([
                'status'=>'canceled',
                'cancel_reason'=>$request->input('reason'),
            ]);

            // reembolso virtual
            $order->member->card->transactions()->create([
                'type'        =>'credit',
                'amount'      =>$order->total,
                'date'        => now()->toDateString(),
                'credit_type' =>'order_cancellation',
                'order_id'    =>$order->id,
            ]);

            Mail::to($order->member->email)
                ->send(new OrderCanceledMail($order));
        });

        return back()
            ->with('alert-type','success')
            ->with('alert-msg',"Pedido #{$order->id} cancelado y reembolsado");
    }

 
}