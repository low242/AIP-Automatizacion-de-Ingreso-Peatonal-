<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('perfil', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();


        $data = $request->validateWithBag('profileUpdate', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'compania' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $profileData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'compania' => $data['compania'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'telefono' => $data['telefono'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            $profilesPath = public_path('assets/img/profiles');
            File::ensureDirectoryExists($profilesPath);

            if ($user->foto && str_starts_with($user->foto, 'assets/img/profiles/')) {
                File::delete(public_path($user->foto));
            }

            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move($profilesPath, $filename);

            $profileData['foto'] = 'assets/img/profiles/' . $filename;
        }

        $user->update($profileData);

        return back()
            ->with('status', 'Perfil actualizado correctamente.')
            ->with('active_tab', 'profile-edit');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()
            ->with('status', 'Contraseña actualizada correctamente.')
            ->with('active_tab', 'profile-change-password');
    }
}
