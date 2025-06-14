<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompleted;
use PDF;
use App\Models\StockAdjustment;

class OrderController extends Controller
{
    public function pending()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with('member')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('orders.pending', compact('orders'));
    }

    public function complete(Order $order)
    {
        $this->authorize('complete', $order);

        // 1) Actualizar estado
        $order->status === 'completed';
        $order->save();

        // 2) Generar PDF
        $pdf = PDF::loadView('orders.receipt', compact('order'));
        $path = "receipts/order-{$order->id}.pdf";
        Storage::put("public/{$path}", $pdf->output());

        // 3) Actualizar stock y registrar ajustes
        foreach ($order->items as $item) {
            $product = $item->product;
            $old = $product->stock;
            $product->decrement('stock', $item->quantity);

            StockAdjustment::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'old_qty'    => $old,
                'new_qty'    => $product->stock,
                'reason'     => "Pedido #{$order->id}"
            ]);
        }

        // 4) Enviar email con recibo
        Mail::to($order->member->email)
            ->send(new OrderCompleted($order, $path));

        return redirect()->route('orders.pending')
                         ->with('success', "Pedido #{$order->id} marcado como completado.");
    }

    public function cancel(Order $order, CardService $card)
    {
        $this->authorize('cancel', $order);

        // 1) Cambiar estado
        $order->status === 'canceled';
        $order->save();

        // 2) Reembolsar
        $card->refund($order->member->card, $order->total);

        return redirect()->route('orders.pending')
                         ->with('success', "Pedido #{$order->id} cancelado y reembolsado.");
    }
}