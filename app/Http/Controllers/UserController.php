<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'vigilante')->latest()->get();
        return view('partials.users.index', compact('users'));
    }

    public function create()
    {
        return view('partials.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'compania' => 'nullable|string|max:255',
            'ciudad'   => 'nullable|string|max:255',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('fotos_usuarios', 'public')
            : null;

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'compania' => $request->compania,
            'ciudad'   => $request->ciudad,
            'foto'     => $fotoPath,
            'role'     => 'vigilante',
            'estado'   => 'activo',
        ]);

        return redirect()->route('users.index')->with('success', 'Vigilante creado correctamente.');
    }

    public function show(User $user)
    {
        return view('partials.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('partials.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'estado'   => 'required|in:activo,inactivo',
        ]);

        $data = [
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'estado'   => $request->estado,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('fotos_usuarios', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Vigilante actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->foto) Storage::disk('public')->delete($user->foto);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Vigilante eliminado correctamente.');
    }
}
