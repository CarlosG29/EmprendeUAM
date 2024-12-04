<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Emprendimiento;
    use App\Models\Estudiante;
    use App\Models\Categoria;
    use App\Models\EstadoEmp;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Imports\EmprendimientoImport;
    use App\Exports\EmprendimientoExport;
    use App\Models\EmprendimientoImagen;
    use Illuminate\Support\Facades\Storage;




    class EmprendimientosController extends Controller
    {

        protected $redirectTo = '/';

        /**
         * Display a listing of the resource.
         */
        public function index(Request $request)
        {
            $query = Emprendimiento::query();

            $query->whereHas('emprendedor', function ($q) {
                $q->where('status', true);
            });

            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->has('category') && $request->category != '') {
                $query->where('categoria_id', $request->category);
            }

            $emprendimientos = $query->get();
            $categorias = Categoria::all();

            return view('emprendimientos.index', compact('emprendimientos', 'categorias'));
        }



        public function misEmprendimientos()
        {
            $user = Auth::user();
            $emprendimientos = $user->emprendimientos;
            return view('emprendimientos.misEmprendimientos', compact('emprendimientos'));
        }

        public function emprendimientoProductos($id)
        {
            $emprendimiento = Emprendimiento::findOrFail($id);
            $productos = $emprendimiento->productos;
            return view('emprendimientos.productosEmprendimiento', compact('emprendimiento', 'productos'));
        }

        public function showEmprendimientoEditScreen($id)
        {
            $emprendimiento = Emprendimiento::findOrFail($id);
            $categorias = Categoria::all(); // Assuming you have a Categoria model
            return view('emprendimientos.editar', compact('emprendimiento', 'categorias'));
        }

        public function listarPendientes()
    {
        $categorias = Categoria::all();

        // Cargar los emprendimientos con sus imágenes relacionadas y en estado PENDIENTE
        $emprendimientos = Emprendimiento::whereHas('estado_emp', function ($query) {
            $query->where('nombre', 'PENDIENTE');
        })
        ->with(['emprendedor', 'categoria', 'imagenes'])
        ->get();

        return view('emprendimientos.showPendientes', compact('emprendimientos', 'categorias'));
    }

        public function pendientes(Request $request)
        {
            $search = $request->query('search');
            $category = $request->query('category');

            $query = Emprendimiento::whereHas('estado_emp', function ($q) {
                $q->where('nombre', 'PENDIENTE');
            });

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('descripcion', 'LIKE', "%{$search}%");
                });
            }

            if ($category) {
                $query->where('categoria_id', $category);
            }

            $emprendimientos = $query->get();
            $categorias = Categoria::all();

            return view('emprendimientos.showPendientes', compact('emprendimientos', 'categorias'));
        }

        public function create()
        {
            $categorias = Categoria::all();
            return view('emprendimientos.create', compact('categorias'));
        }

        public function store(Request $request)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'imagenes' => 'required',
        'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'categoria_id' => 'required|integer|exists:categorias,id',
    ]);

    // Asigna el emprendedor_id al usuario autenticado
    $validatedData['emprendedor_id'] = Auth::id();

    // Crear el emprendimiento
    $emprendimiento = Emprendimiento::create($validatedData);

    // Almacenar las imágenes
    if ($request->hasFile('imagenes')) {
        foreach ($request->file('imagenes') as $imagen) {
            $path = $imagen->store('emprendimientos', 'public');
            EmprendimientoImagen::create([
                'emprendimiento_id' => $emprendimiento->id,
                'path' => $path,
            ]);
        }
    }

    return redirect()->route('emprendimientos.index')->with('success', 'Emprendimiento creado con éxito.');
}


        


        /**
         * Display the specified resource.
         */
        public function show(Emprendimiento $emprendimiento)
        {
            $productos = $emprendimiento->productos;
            return view('emprendimientos.show', compact('emprendimiento', 'productos'));
            //
        }

/**
 * Show the form for editing the specified resource.
 */
public function update(Request $request, Emprendimiento $emprendimiento)
{
    try {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria_id' => 'required|integer|exists:categorias,id',
        ]);

        // Verificar que la instancia de $emprendimiento sea válida
        if (!$emprendimiento || !$emprendimiento->id) {
            throw new \Exception('La instancia de emprendimiento es inválida o no contiene un ID.');
        }

        // Actualizar los datos principales del emprendimiento
        $emprendimiento->update([
            'nombre' => $validatedData['nombre'],
            'descripcion' => $validatedData['descripcion'],
            'categoria_id' => $validatedData['categoria_id'],
        ]);

        // Manejar las imágenes
        if ($request->hasFile('imagenes')) {
            // Eliminar las imágenes antiguas solo si se han subido nuevas
            foreach ($emprendimiento->imagenes as $imagen) {
                if (Storage::exists('public/' . $imagen->path)) {
                    Storage::delete('public/' . $imagen->path);
                }
                $imagen->delete();
            }

            // Guardar las nuevas imágenes
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('emprendimientos', 'public');
                EmprendimientoImagen::create([
                    'emprendimiento_id' => $emprendimiento->id,
                    'path' => $path,
                ]);
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('misEmprendimientos')->with('success', 'Emprendimiento actualizado correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al actualizar emprendimiento: ' . $e->getMessage());

        // Redirigir con mensaje de error
        return redirect()->back()->with('error', 'Hubo un problema al actualizar el emprendimiento. Por favor, inténtalo de nuevo.');
    }
}







        // EmprendimientoController.php

        public function favoritos()
        {
            $emprendimientos = Emprendimiento::whereHas('emprendedor', function ($query) {
                $query->where('status', true); 
            })->whereHas('preferencias', function ($query) {
                $query->where('estudiante_id', auth()->id())->where('favorito', true);
            })->get();

            return view('emprendimientos.favoritos', compact('emprendimientos'));
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(int $id)
        {
            $emprendimiento = Emprendimiento::findOrFail($id);
            $emprendimiento->delete();

            return redirect()->route('misEmprendimientos')->with('success', 'Emprendimiento eliminado con éxito');
        }

        public function destroyAsAdmin(int $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->admin) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este emprendimiento.');
        }

        $emprendimiento = Emprendimiento::findOrFail($id);

        // Verificar que el emprendimiento esté verificado antes de eliminar
        if ($emprendimiento->estado_emp && $emprendimiento->estado_emp->nombre === 'VERIFICADO') {
            $emprendimiento->delete();
            return redirect()->route('emprendimientos.index')->with('success', 'Emprendimiento verificado eliminado con éxito');
        }

        return redirect()->back()->with('error', 'Solo se pueden eliminar emprendimientos en estado verificado.');
    }


        public function validar($id)
    {
        $emprendimiento = Emprendimiento::findOrFail($id);
        
        \Log::info("Intentando validar el emprendimiento con ID: {$id}");
        
        $estadoVerificado = EstadoEmp::where('nombre', 'VERIFICADO')->first();
        
        if ($estadoVerificado) {
            $emprendimiento->estado_emp_id = $estadoVerificado->id;
            $emprendimiento->save();
            
            \Log::info("Emprendimiento ID: {$id} validado correctamente.");
            return redirect()->route('emprendimientos.pendientes')->with('success', 'Emprendimiento validado exitosamente.');
        } else {
            \Log::error("Error: Estado 'VERIFICADO' no encontrado.");
            return redirect()->route('emprendimientos.pendientes')->with('error', 'Error al validar el emprendimiento: Estado "VERIFICADO" no encontrado.');
        }
    }


        public function rechazar($id)
        {
            $emprendimiento = Emprendimiento::findOrFail($id);
            $estadoRechazado = EstadoEmp::where('nombre', 'RECHAZADO')->first();

            if ($estadoRechazado) {
                $emprendimiento->estado_emp_id = $estadoRechazado->id;
                $emprendimiento->save();

                return redirect()->route('emprendimientos.pendientes')->with('success', 'Emprendimiento rechazado exitosamente.');
            } else {
                return redirect()->route('emprendimientos.pendientes')->with('error', 'Error al rechazar el emprendimiento.');
            }
        }
        public function eliminarImagen($emprendimientoId, $imagenId)
    {
        $imagen = EmprendimientoImagen::where('id', $imagenId)
                    ->where('emprendimiento_id', $emprendimientoId)
                    ->first();

        if (!$imagen) {
            return redirect()->back()->with('error', 'La imagen no existe o no pertenece al emprendimiento.');
        }

        // Eliminar el archivo físico
        if (Storage::exists('public/' . $imagen->path)) {
            Storage::delete('public/' . $imagen->path);
        }

        $imagen->delete();

        // Si no quedan imágenes, agregar una predeterminada
        $emprendimiento = Emprendimiento::findOrFail($emprendimientoId);
        if ($emprendimiento->imagenes()->count() === 0) {
            $defaultImagePath = 'emprendimientos/default.jpg'; // Ruta a tu imagen predeterminada
            EmprendimientoImagen::create([
                'emprendimiento_id' => $emprendimiento->id,
                'path' => $defaultImagePath,
            ]);
        }

        return redirect()->back()->with('success', 'Imagen eliminada con éxito.');
    }






        public function importEmprendimiento(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new EmprendimientoImport, $request->file('file'));

        return redirect()->back()->with('success', 'Emprendimientos importados correctamente');
    }

    public function exportEmprendimiento()
    {
        return Excel::download(new EmprendimientoExport, 'emprendimientos.xlsx');
    }
    public function exportListadoEmprendimientos()
    {
        // Pasar el estado "VERIFICADO" a la clase de exportación
        return Excel::download(new EmprendimientoExport('VERIFICADO'), 'listado_emprendimientos.xlsx');
    }
    public function exportFavoritos()
    {
        // Pasar una condición de favoritos a la clase de exportación
        return Excel::download(new EmprendimientoExport('favoritos'), 'emprendimientos_favoritos.xlsx');
    }
    }

