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

class CartController extends Controller
{
    // Coste fijo de envío
    const SHIPPING_COST = 5.0;

    /**
     * Mostrar carrito: productos, subtotal, envío y total.
     */
    public function show()
    {
        // Recupera el contenido de la sesión (por defecto array vacío)
        $cart = session()->get('cart', []);

        // Si $cart tiene clave 'items', úsala; si no, asumimos que $cart ya es el array de ítems
        if (isset($cart['items']) && is_array($cart['items'])) {
            $items    = $cart['items'];
            $total    = $cart['total']    ?? 0;
            $shipping = $cart['shipping'] ?? 0;
        } else {
            // $cart es el propio array de ítems
            $items    = $cart;

            // Recalcula subtotal si no lo tienes ya
            $total = array_reduce($items, function($carry, $item) {
                // Ajusta según cómo tengas precio y cantidad almacenados
                return $carry + ($item['product']['precio'] * $item['quantity']);
            }, 0);

            // Shipping fijo o lo que corresponda
            $shipping = session()->get('shipping', 0);
        }

        return view('cart.show', compact('items', 'total', 'shipping'));
    }
    /**
     * Añadir (o incrementar) un producto al carrito.
     */
    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $qty  = max(1, (int) $request->input('quantity', 1));
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]= [
                'product'  => $product,
                'quantity' => $cart[$product->id]['quantity'] + $qty,
            ];
        } else {
            $cart[$product->id] = [
                'product'  => $product,
                'quantity' => $qty,
            ];
        }


        // Guardar (o borrar si queda vacío)
        if (empty($cart)) {
            $request->session()->forget('cart');
        } else {
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', "{$product->name} añadido (×{$qty})");
    }

    /**
     * Actualizar la cantidad de un producto.
     * Si la cantidad queda en 0, lo elimina.
     */
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

    /**
     * Eliminar un producto del carrito.
     */
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

    /**
     * Vaciar todo el carrito.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()->with('success', 'Carrito vaciado');
    }
public function checkout(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isMember()) {
            return redirect()->route('home')
                             ->with('error', 'Sólo los socios pueden realizar compras.');
        }

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('warning', 'Tu carrito está vacío.');
        }

        // Calcular totales
        $subtotal = collect($cart)
            ->sum(fn($item) => $item['product']->precio * $item['quantity']);
        $shipping = self::SHIPPING_COST;
        $total    = $subtotal + $shipping;

        // Fondos suficientes?
        $card = $user->card;
        if ($card->balance < $total) {
            return redirect()->route('card.show')
                             ->with('error', 'Fondos insuficientes en tu tarjeta virtual. Por favor, recarga primero.');
        }

        // Debitar tarjeta (Opción B)
        $card->balance -= $total;
        $card->save();

        Operation::create([
            'card_id'     => $card->id,
            'type'        => 'debit',
            'amount'      => $total,
            'user_id'     => $user->id,
            'description' => "Compra por €{$total}",
        ]);

        // Crear orden en “preparing”
        $order = Order::create([
            'member_id'       => $user->id,
            'status'          => 'preparing',
            'date'            => now(),
            'total_items'     => $subtotal,
            'shipping_costs'  => $shipping,
            'total'           => $total,
            'nif'             => $user->nif,
            'delivery_address'=> $user->default_delivery_address,
        ]);

        // Líneas de pedido y check stock
        $outOfStock = false;
        foreach ($cart as $entry) {
            $product = $entry['product'];
            $qty     = $entry['quantity'];

            ItemsOrder::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $qty,
                'price'      => $product->precio,
                'subtotal'   => $product->precio * $qty,
            ]);

            if ($product->stock < $qty) {
                $outOfStock = true;
            }
        }

        // Vaciar carrito
        $request->session()->forget('cart');

        // Mensaje final
        $msg = '¡Pedido confirmado! Tu orden está en preparación.';
        if ($outOfStock) {
            $msg .= ' Atención: algunos productos están sin stock y la entrega puede retrasarse.';
        }

        return redirect()->route('orders.history')
                         ->with('success', $msg);
    }

    /**
 * Mostrar la pantalla de checkout con datos dummy.
 */
public function payment(): \Illuminate\View\View
{
    $user = auth()->user();

    // datos de prueba
    $subtotal = 10.00;
    $shipping = 2.50;
    $total    = $subtotal + $shipping;

    return view('cart.payment', compact('user','subtotal','shipping','total'));
}
}
