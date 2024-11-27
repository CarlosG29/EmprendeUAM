<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\RegisterController,
    ComentarioController,
    EmprendimientosController,
    EmprendimientoController,
    EstudianteController,
    PreferenciaController,
    ProductoController,
    CarreraController

};

// Rutas de autenticación
Auth::routes();

// Rutas para vistas principales
Route::get('/', [EmprendimientosController::class, 'index'])->name('emprendimientos.index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas para estudiantes
Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
Route::get('/misEmprendimientos', [EmprendimientosController::class, 'misEmprendimientos'])->name('misEmprendimientos');

// Rutas de Emprendimientos (Creación, gestión y visualización)
Route::get('/crear-emprendimiento', [EmprendimientosController::class, 'create'])->name('crear.emprendimiento');
Route::post('/post-emprendimiento', [EmprendimientosController::class, 'store'])->name('guardar.emprendimiento');
Route::get('/emprendimiento/{emprendimiento}', [EmprendimientosController::class, 'show'])->name('emprendimientos.show');
Route::put('/emprendimiento/{id}/editarbd', [EmprendimientosController::class, 'update'])->name('actualizar.emprendimiento');

// Agrupación de rutas bajo middleware auth para acceso restringido
Route::middleware(['auth'])->group(function () {
    // Importación y Exportación
    Route::post('/import-carrera', [CarreraController::class, 'importCarrera'])->name('carrera.import');
    Route::get('/export-carrera', [CarreraController::class, 'exportCarrera'])->name('carrera.export');
    Route::post('/import-emprendimiento', [EmprendimientosController::class, 'importEmprendimiento'])->name('emprendimiento.import');
    Route::get('/export-emprendimiento', [EmprendimientosController::class, 'exportEmprendimiento'])->name('emprendimiento.export');
    Route::get('/export-listado-emprendimientos', [EmprendimientosController::class, 'exportListadoEmprendimientos'])->name('emprendimientos.exportListado');
    Route::get('/export-favoritos', [EmprendimientosController::class, 'exportFavoritos'])->name('emprendimientos.exportFavoritos');
    Route::get('/export-estudiantes', [EstudianteController::class, 'exportEstudiantes'])->name('estudiantes.export');
    Route::post('/estudiantes/import', [EstudianteController::class, 'import'])->name('estudiantes.import');

    Route::put('/emprendimiento/{emprendimiento}/editarbd', [EmprendimientosController::class, 'update'])->name('actualizar.emprendimiento');


    // Otras rutas protegidas
    Route::get('/profile/{id}/edit', [EstudianteController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [EstudianteController::class, 'update'])->name('profile.update');
    // ... aquí puedes incluir otras rutas de gestión que requieren autenticación
});
Route::put('/emprendimiento/{emprendimiento}', [EmprendimientosController::class, 'update'])->name('actualizar.emprendimiento');


Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');

Route::get('/', [App\Http\Controllers\EmprendimientosController::class, 'index'])->name('emprendimientos.index');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/estudiantes', [App\Http\Controllers\EstudianteController::class, 'index']) -> name('estudiantes.index');

Route::get('/usuarios/buscar', [EstudianteController::class, 'buscar'])->name('usuarios.buscar');

Route::get('/misEmprendimientos', [App\Http\Controllers\EmprendimientosController::class, 'misEmprendimientos']) ->name('misEmprendimientos');

Route::get('/emprendimientos', [App\Http\Controllers\EmprendimientosController::class, 'index'])->name('emprendimientos.index');

Route::get('/crear-emprendimiento', [App\Http\Controllers\EmprendimientosController::class, 'create'])->name('crear.emprendimiento');

// Route::get('/categorias', [App\Http\Controllers\CategoriasController::class, 'index'])->name('categorias.index');

Route::post('/post-emprendimiento', [App\Http\Controllers\EmprendimientosController::class, 'store'])->name('guardar.emprendimiento');

Route::get('/emprendimiento/{emprendimiento}', [App\Http\Controllers\EmprendimientosController::class, 'show'])->name('emprendimientos.show');

Route::get('/emprendimiento/{id}/editarscreen', [App\Http\Controllers\EmprendimientosController::class, 'showEmprendimientoEditScreen'])->name('editar.emprendimiento');

Route::put('/emprendimiento/{id}/editarbd', [App\Http\Controllers\EmprendimientosController::class, 'update'])->name('actualizar.emprendimiento');

Route::delete('/emprendimiento/{id}/eliminar', [App\Http\Controllers\EmprendimientosController::class, 'destroy'])->name('eliminar.emprendimiento');

Route::get('/emprendimiento/{id}/productos', [App\Http\Controllers\EmprendimientosController::class, 'emprendimientoProductos'])->name('emprendimiento.productos');

Route::get('emprendimientos/{emprendimiento_id}/productos/create', [App\Http\Controllers\ProductoController::class, 'create'])->name('crear.producto');

Route::post('/emprendimientos/{emprendimiento_id}/productos', [App\Http\Controllers\ProductoController::class, 'store'])->name('crear.producto.store');

Route::get('/emprendimientos/{emprendimiento}/productos/{producto}/editar', [App\Http\Controllers\ProductoController::class, 'edit'])->name('editar.producto');

Route::put('/emprendimientos/{emprendimiento}/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'update'])->name('editar.producto.update');

Route::post('/comentarios', [App\Http\Controllers\ComentarioController::class, 'store'])->name('comentarios.store');
Route::delete('/emprendimientos/{id}/admin-destroy', [EmprendimientosController::class, 'destroyAsAdmin'])
    ->name('emprendimientos.destroyAsAdmin')
    ->middleware('auth');

Route::get('/favorites', [App\Http\Controllers\EmprendimientosController::class, 'favoritos'])->name('favorites');
Route::post('/favorites/add/{emprendimiento}', [PreferenciaController::class, 'addFavorite'])->name('favorites.add');
Route::post('/favorites/remove/{emprendimiento}', [PreferenciaController::class, 'removeFavorite'])->name('favorites.remove');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{id}/edit', [EstudianteController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [EstudianteController::class, 'update'])->name('profile.update');
});

Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes');
Route::post('/estudiantes/{id}/activar', [EstudianteController::class, 'activar'])->name('estudiantes.activar');
Route::post('/estudiantes/{id}/desactivar', [EstudianteController::class, 'desactivar'])->name('estudiantes.desactivar');

Route::post('/calificar-emprendimiento', [PreferenciaController::class, 'storeOrUpdate'])->name('calificar.emprendimiento');
Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
Route::patch('/comentarios/{comentario}', [ComentarioController::class, 'update'])->name('comentarios.update');

Route::get('/emprendimientos/pendientes', [EmprendimientosController::class, 'pendientes'])->name('emprendimientos.pendientes');

Route::patch('/emprendimientos/{id}/validar', [EmprendimientosController::class, 'validar'])->name('emprendimientos.validar');
Route::patch('/emprendimientos/{id}/rechazar', [EmprendimientosController::class, 'rechazar'])->name('emprendimientos.rechazar');

Route::get('/perfil/{id}/editar', [EstudianteController::class, 'edit'])->name('profile.edit');
Route::put('/perfil/{id}', [EstudianteController::class, 'update'])->name('profile.update');
Route::delete('/imagenes/{id}', [EmprendimientosController::class, 'eliminarImagen'])->name('eliminar.imagen');
Route::delete('/emprendimiento/{emprendimiento}/imagen/{imagen}', [EmprendimientosController::class, 'eliminarImagen'])->name('eliminar.imagen');
Route::delete('/emprendimientos/{emprendimiento}/imagen/{imagen}', [EmprendimientosController::class, 'eliminarImagen'])
->name('eliminar.imagen');
Route::put('/emprendimiento/{id}/editarbd', [EmprendimientosController::class, 'update'])->name('actualizar.emprendimiento');


Route::delete('/emprendimientos/{emprendimiento}/productos/{producto}', [ProductoController::class, 'destroy'])
    ->name('eliminar.producto');

Route::middleware(['auth'])->group(function () {
Route::put('/comentarios/{comentario}', [ComentarioController::class, 'update'])->name('comentarios.update');

Route::get('/comentarios/{id}/edit', [ComentarioController::class, 'edit'])->name('comentarios.edit');
Route::put('/comentarios/{id}', [ComentarioController::class, 'update'])->name('comentarios.update');
Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
    });
    