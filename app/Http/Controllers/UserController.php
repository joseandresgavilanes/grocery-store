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
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        $users = $query->paginate(20)->withQueryString();

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
        $msg = "Usuario <a href='{$url}'><u>" . e($user->name) . "</u></a> creado correctamente.";
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

    public function update(UserFormRequest $request): RedirectResponse
    {
        $user = $request->user(); // o auth()->user()

        $validated = $request->validated();
        $updateData = [];

        // Recorremos los campos validados y comparamos
        foreach ($validated as $key => $value) {
            if ($key === 'password') {
                // Solo si se proporcionó y es diferente (asumiendo que la contraseña nunca se devuelve plana)
                if (!empty($value)) {
                    $updateData[$key] = bcrypt($value);
                }
            } elseif ($user->$key !== $value) {
                $updateData[$key] = $value;
            }
        }

        // Foto de perfil (si se sube una nueva)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $filename = basename($path); // Obtiene solo "abc123.jpg"
            $updateData['photo'] = $filename;

        }



        // Solo actualiza si hay algo nuevo
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return redirect()->route('edit')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Perfil actualizado correctamente.');
    }


    // public function destroy(User $user): RedirectResponse
    // {
    //     try {
    //         $orders   = $user->orders()->count();
    //         $txns     = $user->transactions()->count();
    //         if ($orders === 0 && $txns === 0) {
    //             $user->delete();
    //             $type = 'success';
    //             $msg  = "Usuario {$user->name} eliminado correctamente.";
    //         } else {
    //             $type = 'warning';
    //             $parts = [];
    //             if ($orders > 0) $parts[] = "$orders pedidos";
    //             if ($txns   > 0) $parts[] = "$txns transacciones";
    //             $just = implode(' y ', $parts);
    //             $msg  = "El usuario {$user->name} no puede borrarse porque tiene $just.";
    //         }
    //     } catch (\Exception $e) {
    //         $type = 'danger';
    //         $msg  = "Error al eliminar el usuario {$user->name}.";
    //     }

    //     return redirect()->back()
    //                      ->with('alert-type', $type)
    //                      ->with('alert-msg', $msg);
    // }

    public function block(User $user): RedirectResponse
    {
        $this->authorize('block', $user);
        $user->blocked = true;
        $user->save();

        return back()->with('success', "Usuario {$user->name} bloqueado.");
    }

    public function unblock(User $user): RedirectResponse
    {
        $this->authorize('unblock', $user);
        $user->blocked = false;
        $user->save();

        return back()->with('success', "Usuario {$user->name} desbloqueado.");
    }

    public function promote(User $user): RedirectResponse
    {
        $this->authorize('promote', $user);
        $user->type = 'board';
        $user->save();

        return back()->with('success', "Usuario {$user->name} promovido a board.");
    }

    public function demote(User $user): RedirectResponse
    {
        $this->authorize('demote', $user);
        $user->type = 'member';
        $user->save();

        return back()->with('success', "Privilegios de board revocados para {$user->name}.");
    }

    // Ajusta destroy() para soft delete de miembros (no permitir borrar a ti mismo)
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete(); // requiere SoftDeletes en el modelo User
        return back()->with('success', "Membresía de {$user->name} cancelada.");
    }
}
