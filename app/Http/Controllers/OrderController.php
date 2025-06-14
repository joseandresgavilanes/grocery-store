<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Operation;
use App\Services\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompleted;
use App\Models\StockAdjustment;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

public function receipt(Order $order)
{
    $this->authorize('view', $order); // Lo mantenemos para seguridad

    $pdf = Pdf::loadView('pdf.receipt', compact('order'));

    return $pdf->download("recibo-pedido-{$order->id}.pdf");
}

public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        $order->status = 'canceled';
        $order->save();

        $card = $order->member->card;
        $card->increment('balance', $order->total);

        Operation::create([
            'card_id'           => $card->id,
            'order_id'          => $order->id,
            'type'              => 'credit',
            'value'             => $order->total,
            'date'              => now()->toDateString(),
            'debit_type'        => null,
            'credit_type'       => 'order_cancellation',
            'payment_type'      => null,
            'payment_reference' => null,
            'custom'            => null,
        ]);

        return redirect()->route('orders.pending')
                         ->with('success', "Pedido #{$order->id} cancelado y reembolsado.");
    }

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


        $order->status ='completed';
        $order->save();


        $pdf = PDF::loadView('orders.receipt', compact('order'));
        $path = "receipts/order-{$order->id}.pdf";
        Storage::put("public/{$path}", $pdf->output());


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


        Mail::to($order->member->email)
            ->send(new OrderCompleted($order, $path));

        return redirect()->route('orders.pending')
                         ->with('success', "Pedido #{$order->id} marcado como completado.");
    }


}
