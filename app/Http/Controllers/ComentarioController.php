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
        $request->validate([
            'comentario' => 'required|string|max:255',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'emprendimiento_id' => 'required|exists:emprendimientos,id',
        ]);

        try {
            Comentario::create([
                'comentario' => $request->comentario,
                'estudiante_id' => $request->estudiante_id,
                'emprendimiento_id' => $request->emprendimiento_id,
            ]);

            return redirect()->route('emprendimientos.show', $request->emprendimiento_id)
                             ->with('success', 'Comentario añadido exitosamente.');
        } catch (\Exception $e) {
            \Log::error("Error al guardar comentario: " . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al añadir el comentario. Inténtalo de nuevo.');
        }
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comentario $comentario)
    {
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
        if (auth()->id() === $comentario->estudiante_id || auth()->user()->admin) {
            $request->validate([
                'comentario' => 'required|string|max:255',
            ]);

            try {
                $comentario->update([
                    'comentario' => $request->comentario,
                ]);

                return redirect()->back()->with('success', 'Comentario actualizado con éxito.');
            } catch (\Exception $e) {
                \Log::error("Error al actualizar comentario: " . $e->getMessage());
                return redirect()->back()->with('error', 'Hubo un problema al actualizar el comentario.');
            }
        }

        return redirect()->back()->with('error', 'No tienes permiso para actualizar este comentario.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comentario $comentario)
    {
        if (auth()->id() !== $comentario->estudiante_id && !auth()->user()->admin) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        try {
            $comentario->delete();
            return redirect()->back()->with('success', 'Comentario eliminado con éxito.');
        } catch (\Exception $e) {
            \Log::error("Error al eliminar comentario: " . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al eliminar el comentario.');
        }
    }
}
