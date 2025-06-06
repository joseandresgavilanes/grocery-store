<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', null);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Product $product)
    {
        // 1. Intentamos leer de sesión la clave "cart". Si no existe, $cart queda en null.
        $cart = session('cart', null);
    
        // 2. Si $cart es null, significa que aún no hay nada en el carrito.
        //    Entonces creamos una Collection con este primer producto y la guardamos en sesión.
        if (! $cart) {
            // collect([$product]) crea una colección que contiene el modelo $product
            $cart = collect([$product]);
            $request->session()->put('cart', $cart);
        }
        else {
            // 3. Si ya existe una Collection en sesión, comprobamos si el producto ya está
            //    usando firstWhere('id', $product->id). Si lo encuentra, devolvemos advertencia.
            if ($cart->firstWhere('id', $product->id)) {
                $alertType = 'warning';
                $htmlMessage = "Product <a href='google.com'>#{$product->id}
                    <strong>\"{$product->name}\"</strong></a> was not added to the cart
                    because it is already included in the cart!";
    
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
            else {
                // 4. Si NO está en la Collection, lo agregamos con push() y volvemos a 
                //    sobrescribir la Collection en sesión.
                $cart->push($product);
                $request->session()->put('cart', $cart);
            }
        }
    
        // 5. Si el flujo llega aquí, quiere decir que se agregó el producto correctamente.
        //    Preparamos el mensaje de éxito y devolvemos con back() (para la misma página).
        $alertType = 'success';
        $htmlMessage = "Product <a href='googlr.com'>#{$product->id}
            <strong>\"{$product->name}\"</strong></a> was added to the cart.";
    
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

 /*
    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $url = route('product.show', ['product' => $product]);
        $cart = session('cart', null);

        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Product <a href='$url'>#{$product->id}</a>
                <strong>\"{$product->name}\"</strong> was not removed from the cart
                because the cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $element = $cart->firstWhere('id', $product->id);
            if ($element) {
                $cart->forget($cart->search($element));

                if ($cart->count() === 0) {
                    $request->session()->forget('cart');
                } else {
                    $request->session()->put('cart', $cart);
                }

                $alertType = 'success';
                $htmlMessage = "Product <a href='$url'>#{$product->id}</a>
                <strong>\"{$product->name}\"</strong> was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Product <a href='$url'>#{$product->id}</a>
                <strong>\"{$product->name}\"</strong> was not removed from the cart
                because it is not in the cart!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }

    //despues editar
    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
    {
        $cart = session('cart', null);
        if (!$cart || ($cart->count() == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } else {
            $student = Student::where('number', $request->validated()['student_number'])->first();
            if (!$student) {
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "Student number does not exist on the database!");
            }
            $insertDisciplines = [];
            $disciplinesOfStudent = $student->disciplines;
            $ignored = 0;
            foreach ($cart as $discipline) {
                $exist = $disciplinesOfStudent->where('id', $discipline->id)->count();
                if ($exist) {
                    $ignored++;
                } else {
                    $insertDisciplines[$discipline->id] = [
                        "discipline_id" => $discipline->id,
                        "repeating" => 0,
                        "grade" => null,
                    ];
                }
            }
            $ignoredStr = match ($ignored) {
                0 => "",
                1 => "<br>(1 discipline was ignored because student was already enrolled in it)",
                default => "<br>($ignored disciplines were ignored because student was already
                            enrolled on them)"
            };
            $totalInserted = count($insertDisciplines);
            $totalInsertedStr = match ($totalInserted) {
                0 => "",
                1 => "1 discipline registration was added to the student",
                default => "$totalInserted disciplines registrations were added to the student",
            };
            if ($totalInserted == 0) {
                $request->session()->forget('cart');
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "No registration was added to the student!$ignoredStr");
            } else {
                DB::transaction(function () use ($student, $insertDisciplines) {
                    $student->disciplines()->attach($insertDisciplines);
                });
                $request->session()->forget('cart');
                if ($ignored == 0) {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'success')
                        ->with('alert-msg', "$totalInsertedStr.");
                } else {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'warning')
                        ->with('alert-msg', "$totalInsertedStr. $ignoredStr");
                }
            }
        }
    }*/
}
