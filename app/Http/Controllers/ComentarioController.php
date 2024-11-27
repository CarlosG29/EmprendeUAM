<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'comentario' => 'required|string|max:255',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'emprendimiento_id' => 'required|exists:emprendimientos,id',
        ]);

        // Crear un nuevo comentario
        Comentario::create([
            'comentario' => $request->comentario,
            'estudiante_id' => $request->estudiante_id,
            'emprendimiento_id' => $request->emprendimiento_id,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('emprendimientos.show', $request->emprendimiento_id)
                         ->with('success', 'Comentario añadido exitosamente.');
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comentario $comentario)
    {
        // Verificar si el usuario tiene permisos para editar el comentario
        if (auth()->id() !== $comentario->estudiante_id) {
            abort(403, 'No tienes permiso para editar este comentario.');
        }

        return view('comentarios.edit', compact('comentario'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comentario $comentario)
{
    // Verifica si el usuario autenticado es el autor del comentario o un administrador
    if (auth()->id() === $comentario->estudiante_id || auth()->user()->admin) {
        $request->validate([
            'comentario' => 'required|string|max:255',
        ]);

        $comentario->update([
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Comentario actualizado con éxito.');
    }

    return redirect()->back()->with('error', 'No tienes permiso para actualizar este comentario.');
}

    

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comentario $comentario)
    {
        // Verificar si el usuario tiene permisos para eliminar el comentario
        if (auth()->id() !== $comentario->estudiante_id && !auth()->user()->admin) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado con éxito.');
    }
}
