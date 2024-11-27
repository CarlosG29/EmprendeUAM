@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center" style="color: #439FA5;">Productos: {{ $emprendimiento->nombre }}</h2>

    <!-- Botón Agregar Producto -->
    <div class="text-center mb-4">
        <a href="{{ route('crear.producto', $emprendimiento->id) }}" class="btn btn-lg custom-btn shadow-sm">
            <i class="fas fa-plus me-2"></i>Agregar Producto
        </a>
    </div>

    <!-- Listado de Productos -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($emprendimiento->productos as $producto)
        <div class="col">
            <div class="card h-100 shadow-sm" style="border-radius: 10px;">
                <!-- Imagen del producto -->
                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                     class="card-img-top" 
                     alt="{{ $producto->nombre }}" 
                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title" style="color: #439FA5;">{{ $producto->nombre }}</h5>
                    <p class="card-text flex-grow-1">{{ \Illuminate\Support\Str::limit($producto->descripcion, 100, '...') }}</p>
                    <p class="card-text precio"><strong>Precio:</strong> C${{ $producto->precio }}</p>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-between">
                        <!-- Botón Editar -->
                        <a href="{{ route('editar.producto', ['emprendimiento' => $emprendimiento->id, 'producto' => $producto->id]) }}" 
                           class="btn btn-sm btn-secondary shadow-sm">
                           <i class="fas fa-edit"></i> Editar
                        </a>
                        
                        <!-- Botón Eliminar -->
                        <form method="POST" action="{{ route('eliminar.producto', ['emprendimiento' => $emprendimiento->id, 'producto' => $producto->id]) }}" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Estilos Personalizados -->
<style>
    .custom-btn {
        background-color: #439FA5;
        border-color: #439FA5;
        color: white;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .custom-btn:hover {
        background-color: #367f85;
        border-color: #367f85;
    }
    .precio {
        font-size: 1.1rem;
        color: #333;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }
</style>

@endsection
