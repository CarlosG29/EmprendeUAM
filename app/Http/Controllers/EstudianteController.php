<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\EstudianteExport;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Estudiante::with('carrera')->where('admin', false)->paginate(10);
        return view('estudiantes.index', compact('usuarios'));
    }

    /**
     * Activar el estado de un usuario.
     */
    public function activar($id)
    {
        $usuario = Estudiante::findOrFail($id);
        $usuario->status = true;
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Usuario activado correctamente']);
    }

    /**
     * Desactivar el estado de un usuario.
     */
    public function desactivar($id)
    {
        $usuario = Estudiante::findOrFail($id);
        $usuario->status = false;
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Usuario desactivado correctamente']);
    }

    /**
     * Alterna el estado de un usuario.
     */
    public function toggleStatus($id)
    {
        $usuario = Estudiante::findOrFail($id);
        $usuario->status = !$usuario->status;
        $usuario->save();

        $message = $usuario->status ? 'Usuario activado correctamente' : 'Usuario desactivado correctamente';
        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Buscar usuarios según CIF o nombre/apellido.
     */
    public function buscar(Request $request)
    {
        $query = Estudiante::where('admin', false);

        if ($request->has('cif')) {
            $query->where('cif', 'LIKE', '%' . $request->input('cif') . '%');
        }

        if ($request->has('nombre')) {
            $nombre = $request->input('nombre');
            $query->where(function ($q) use ($nombre) {
                $q->where('nombre', 'LIKE', '%' . $nombre . '%')
                  ->orWhere('apellido', 'LIKE', '%' . $nombre . '%');
            });
        }

        $usuarios = $query->paginate(10);
        return view('estudiantes.index', compact('usuarios'));
    }

    /**
     * Actualizar el perfil del usuario.
     */
    public function update(Request $request, $id)
{
    $estudiante = Estudiante::findOrFail($id);

    // Validar los datos del formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'carrera_id' => 'required|exists:carreras,id',
        'celular' => 'nullable|string|max:15', // Validar el celular
        'current_password' => 'required|string',
        'new_password' => 'nullable|string|min:8|confirmed',
        'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Verificar la contraseña actual
    if (!Hash::check($request->current_password, $estudiante->password)) {
        return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])->withInput();
    }

    // Actualizar datos del estudiante
    $estudiante->nombre = $request->nombre;
    $estudiante->apellido = $request->apellido;
    $estudiante->carrera_id = $request->carrera_id;

    // Actualizar el celular si está presente
    if ($request->filled('celular')) {
        $estudiante->celular = $request->celular;
    }

    // Manejar la foto de perfil
    if ($request->hasFile('foto_perfil')) {
        $path = $request->file('foto_perfil')->store('perfiles', 'public');

        // Eliminar la imagen anterior si existe
        if ($estudiante->foto_perfil) {
            \Storage::disk('public')->delete($estudiante->foto_perfil);
        }

        $estudiante->foto_perfil = $path;
    }

    // Actualizar contraseña si fue enviada
    if ($request->filled('new_password')) {
        $estudiante->password = Hash::make($request->new_password);
    }

    $estudiante->save();

    return redirect()->route('profile.edit', $id)->with('success', 'Perfil actualizado correctamente.');
}



    /**
     * Mostrar formulario de creación (actualmente vacío).
     */
    public function create()
    {
        // Código para mostrar el formulario de creación si fuera necesario
    }

    /**
     * Guardar un nuevo recurso (actualmente vacío).
     */
    public function store(Request $request)
    {
        // Código para almacenar un nuevo recurso si fuera necesario
    }

    /**
     * Mostrar el recurso especificado (actualmente vacío).
     */
    public function show(string $id)
    {
        // Código para mostrar un recurso específico si fuera necesario
    }

    /**
     * Mostrar formulario de edición del perfil.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if ($user->id != $id) {
            return redirect()->route('home')->with('error', 'No puedes editar el perfil de otro usuario.');
        }
        $carreras = Carrera::all();

        return view('estudiantes.edit', compact('user', 'carreras'));
    }

    /**
     * Eliminar el recurso especificado.
     */
    public function destroy($id)
    {
        $usuario = Estudiante::findOrFail($id);
        $usuario->delete();

        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    }

    /**
     * Exportar usuarios estudiantes a un archivo XLSX.
     */
    public function exportEstudiantes()
    {
        return Excel::download(new EstudianteExport, 'usuarios_estudiantes.xlsx');
    }

    /**
     * Importar usuarios estudiantes desde un archivo XLSX.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new EstudiantesImport, $request->file('file'));
            return redirect()->route('usuarios.estudiantes')->with('success', 'Usuarios importados correctamente.');
        } catch (\Exception $e) {
            Log::error("Error en la importación: " . $e->getMessage());
            return back()->with('error', 'Error al importar los usuarios: ' . $e->getMessage());
        }
    }
}
