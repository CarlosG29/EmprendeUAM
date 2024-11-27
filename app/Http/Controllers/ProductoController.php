<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Emprendimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Show the form for creating a new product.
     */
    public function create($emprendimiento_id)
    {
        $emprendimiento = Emprendimiento::findOrFail($emprendimiento_id);
        return view('productos.create', compact('emprendimiento'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request, $emprendimiento_id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'precio' => 'required|numeric|min:0', // Precio positivo
            'oculto' => 'boolean',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen' => $request->file('imagen')->store('productos', 'public'),
            'precio' => $request->precio,
            'oculto' => $request->oculto,
            'emprendimiento_id' => $emprendimiento_id,
        ]);

        return redirect()->route('emprendimiento.productos', $emprendimiento_id)
                         ->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Emprendimiento $emprendimiento, Producto $producto)
    {
        return view('productos.editar', compact('emprendimiento', 'producto'));
    }

    public function update(Request $request, Emprendimiento $emprendimiento, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'precio' => 'required|numeric|min:0', // Precio positivo
            'oculto' => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');

            // Eliminar la imagen anterior
            if ($producto->imagen) {
                \Storage::disk('public')->delete($producto->imagen);
            }

            $producto->imagen = $path;
        }

        $producto->update($request->only(['nombre', 'descripcion', 'precio', 'oculto']));

        return redirect()->route('emprendimiento.productos', $emprendimiento->id)
                         ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Emprendimiento $emprendimiento, Producto $producto)
    {
        // Validar permisos: Solo el creador del emprendimiento o un administrador pueden eliminar
        if (Auth::user()->id !== $emprendimiento->emprendedor_id && !Auth::user()->admin) {
            return redirect()->route('emprendimiento.productos', $emprendimiento->id)
                             ->with('error', 'No tienes permiso para eliminar este producto.');
        }

        // Eliminar el producto
        if ($producto->imagen) {
            \Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('emprendimiento.productos', $emprendimiento->id)
                         ->with('success', 'Producto eliminado exitosamente.');
    }
}
