<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;

class CartController extends Controller
{
    // Coste fijo de envío
    const SHIPPING_COST = 5.0;

private function getCartData(): array
{
    $cart = session()->get('cart', []);

    $productIds = array_keys($cart);

    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    $items = [];

    foreach ($cart as $productId => $item) {
        if (isset($products[$productId])) {
            $items[] = [
                'product' => $products[$productId],
                'quantity' => $item['quantity'],
            ];
        }
    }

    $subtotal = array_reduce($items, function ($carry, $item) {
        return $carry + ($item['product']->price * $item['quantity']);
    }, 0);

    $shipping = self::SHIPPING_COST;

    $total = $subtotal + $shipping;

    //dd(compact('items', 'subtotal', 'shipping', 'total'));

    return compact('items', 'subtotal', 'shipping', 'total');
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
}
