<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Operation;
use App\Models\Order;
use App\Models\ItemsOrder;
use App\Services\Payment;
use App\Models\SettingsShippingCosts;

class CartController extends Controller
{
    
private function getCartData(): array
{
    $cart = session('cart', []);
    $products = Product::findMany(array_keys($cart))->keyBy('id');

    $items = array_map(fn($qty, $prod) => [
        'product'  => $products[$prod],
                'quantity' => (int) $qty,
    ], $cart, array_keys($cart));

    $subtotal = array_reduce($items, fn($sum,$i)=> $sum + $i['product']->price*$i['quantity'], 0);

    // --- Shipping dinámico
    $rule = SettingsShippingCosts::query()
      ->where('min_value_threshold', '<=', $subtotal)
      ->where(function($q) use($subtotal){
         $q->where('max_value_threshold', '>=', $subtotal)
           ->orWhereNull('max_value_threshold');
      })
      ->first();
    $shipping = $rule?->shipping_cost ?? 0;

    $total = $subtotal + $shipping;

    return compact('items','subtotal','shipping','total');
}


    public function show(): View
    {
        $data = $this->getCartData();
        return view('cart.show', $data);
    }

    public function payment(): View
{
    $data = $this->getCartData();
    return view('cart.payment', $data);
}

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $qty  = max(1, (int) $request->input('quantity', 1));
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = [
                'product'  => $product,
                'quantity' => $cart[$product->id]['quantity'] + $qty,
            ];
        } else {
            $cart[$product->id] = [
                'product'  => $product,
                'quantity' => $qty,
            ];
        }

        if (empty($cart)) {
            $request->session()->forget('cart');
        } else {
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', "{$product->name} añadido (×{$qty})");
    }

    public function updateQuantity(Request $request, Product $product): RedirectResponse
    {
        $newQty = max(0, (int) $request->input('quantity', 0));
        $cart   = $request->session()->get('cart', []);

        if (! isset($cart[$product->id])) {
            return back()->with('warning', "{$product->name} no estaba en el carrito");
        }

        if ($newQty === 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['quantity'] = $newQty;
        }

        if (empty($cart)) {
            $request->session()->forget('cart');
        } else {
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', "{$product->name} actualizado a ×{$newQty}");
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);

            if (empty($cart)) {
                $request->session()->forget('cart');
            } else {
                $request->session()->put('cart', $cart);
            }

            return back()->with('success', "{$product->name} eliminado del carrito");
        }

        return back()->with('warning', "{$product->name} no estaba en el carrito");
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()->with('success', 'Carrito vaciado');
    }


public function checkout(Request $r): RedirectResponse
{
    $user = auth()->user();

    // Sólo socios pueden comprar
    if (! $user->isMember()) {
        return redirect()->route('login')
                         ->with('error', 'Sólo socios pueden comprar. Por favor, identifícate.');
    }

    // Validar datos mínimos (para testing solo aceptamos virtual)
    $r->validate([
        'delivery_address' => 'required|string',
        'nif'              => 'required|string',
        'payment_method'   => 'required|in:virtual',
    ]);

    // Carga carrito
    extract($this->getCartData()); // $items, $subtotal, $shipping, $total

    if (empty($items)) {
        return back()->with('warning', 'Tu carrito está vacío.');
    }

    // Sólo virtual card por ahora…
    $card = $user->card;
    if ($card->balance < $total) {
        return back()->with('error', 'Fondos insuficientes en tarjeta virtual.');
    }

    $card->decrement('balance', $total);

    // 1) Crear orden
    $order = Order::create([
        'member_id'        => $user->id,
        'status'           => 'pending',
        'date'             => now(),
        'total_items'      => $subtotal,
        'shipping_cost'   => $shipping,
        'total'            => $total,
        'nif'              => $r->nif,
        'delivery_address' => $r->delivery_address,
    ]);

    Operation::create([
        'card_id'           => $card->id,
        'order_id'          => $order->id,
        'type'              => 'debit',
        'value'             => $total,
        'date'              => now()->toDateString(),
        'debit_type'        => 'order',
        'credit_type'       => null,
        'payment_type'      => null,
        'payment_reference' => null,
    ]);

    // 3) Crear cada línea de pedido
    $outOfStock = false;
    foreach ($items as $i) {
        $p = $i['product'];
        $q = $i['quantity'];

        ItemsOrder::create([
            'order_id'   => $order->id,
            'product_id' => $p->id,
            'quantity'   => $q,
            'unit_price'      => $p->price,
            'discount'   => ($p->discount && $q >= $p->discount_min_qty)
                          ? round($p->price * $p->discount * $q, 2)
                          : 0,
            'subtotal'   => $p->price * $q,
        ]);

        if ($p->stock < $q) {
            $outOfStock = true;
        }
    }

    // 4) Vaciar carrito
    session()->forget('cart');

    // 5) Mensaje de éxito
    $msg = 'Pedido confirmado. Se está preparando.';
    if ($outOfStock) {
        $msg .= ' Algunos productos pueden retrasarse por falta de stock.';
    }

    return redirect()->route('products.index')
                     ->with('success', $msg);
}

}
