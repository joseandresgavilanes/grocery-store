<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::paginate(20);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create')->with('user', new User());
    }

    public function store(UserFormRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        $url  = route('users.show', ['user' => $user]);
        $msg  = "Usuario <a href='$url'><u>{$user->name}</u></a> creado correctamente.";
        return redirect()->route('users.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserFormRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        $url  = route('users.show', ['user' => $user]);
        $msg  = "Usuario <a href='$url'><u>{$user->name}</u></a> actualizado correctamente.";
        return redirect()->route('users.index')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $orders   = $user->orders()->count();
            $txns     = $user->transactions()->count();
            if ($orders === 0 && $txns === 0) {
                $user->delete();
                $type = 'success';
                $msg  = "Usuario {$user->name} eliminado correctamente.";
            } else {
                $type = 'warning';
                $parts = [];
                if ($orders > 0) $parts[] = "$orders pedidos";
                if ($txns   > 0) $parts[] = "$txns transacciones";
                $just = implode(' y ', $parts);
                $msg  = "El usuario {$user->name} no puede borrarse porque tiene $just.";
            }
        } catch (\Exception $e) {
            $type = 'danger';
            $msg  = "Error al eliminar el usuario {$user->name}.";
        }

        return redirect()->back()
                         ->with('alert-type', $type)
                         ->with('alert-msg', $msg);
    }
}