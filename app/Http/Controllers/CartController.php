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
}
