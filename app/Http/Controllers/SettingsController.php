<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SettingsFormRequest;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $setting = Settings::first();
        return view('settings.edit', compact('setting'));
    }

    public function update(SettingsFormRequest $request): RedirectResponse
    {
        $setting = Settings::first();
        $setting->update($request->validated());
        $msg = "Tarifa de membresía actualizada correctamente.";
        return redirect()->route('settings.edit')
                         ->with('alert-type', 'success')
                         ->with('alert-msg', $msg);
    }
}
