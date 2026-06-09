<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registros = Registro::with('usuario')->paginate(10); // Paginación para mejor rendimiento
        return view('registros.index', compact('registros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'datos_qr' => 'nullable|string',
        ]);

        Registro::create($request->all());

        return redirect()->route('registros.index')->with('success', 'Registro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Registro $registro)
    {
        return view('registros.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registro $registro)
    {
        return view('registros.edit', compact('registro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registro $registro)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'datos_qr' => 'nullable|string',
        ]);

        $registro->update($request->all());

        return redirect()->route('registros.index')->with('success', 'Registro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registro $registro)
    {
        $registro->delete();

        return redirect()->route('registros.index')->with('success', 'Registro eliminado exitosamente.');
    }
}