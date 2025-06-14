<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


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
        $user = $request->user();

        $validated = $request->validated();
        $updateData = [];


        foreach ($validated as $key => $value) {
            if ($key === 'password') {

                if (!empty($value)) {
                    $updateData[$key] = bcrypt($value);
                }
            } elseif ($user->$key !== $value) {
                $updateData[$key] = $value;
            }
        }


        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $filename = basename($path);
            $updateData['photo'] = $filename;

        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return redirect()->route('edit')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Perfil actualizado correctamente.');
    }

    public function updateAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'gender' => 'nullable|in:M,F',
            'delivery_address' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:20',
            'payment_details' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $user->photo = basename($path);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }





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


    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return back()->with('success', "Membresía de {$user->name} cancelada.");
    }
}
