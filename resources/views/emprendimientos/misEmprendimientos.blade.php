@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center" style="color: #439FA5;">Mis Emprendimientos</h2>

    <!-- Mensajes de Éxito o Error -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm" style="border-radius: 10px;">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger shadow-sm" style="border-radius: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Botón de Exportación -->
    <div class="d-flex justify-content-end my-4">
        <a href="{{ route('emprendimiento.export') }}" class="btn btn-success shadow-sm" style="border-radius: 5px; font-size: 14px;">
            <i class="fas fa-file-export me-1"></i> Exportar Emprendimientos
        </a>
    </div>

    <!-- Tabla de Emprendimientos -->
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <thead style="background-color: #439FA5; color: white;">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Imágenes</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emprendimientos as $emprendimiento)
                <tr style="height: 80px;">
                    <td style="vertical-align: middle;">{{ $emprendimiento->nombre }}</td>
                    <td style="vertical-align: middle;">{{ \Illuminate\Support\Str::limit($emprendimiento->descripcion, 100, '...') }}</td>
                    <td style="vertical-align: middle;">{{ $emprendimiento->categoria->nombre }}</td>
                    <td style="vertical-align: middle;">
                        <span class="badge" style="background-color: {{ $emprendimiento->estado_emp->nombre === 'VERIFICADO' ? '#28a745' : '#dc3545' }}; color: white;">
                            {{ $emprendimiento->estado_emp->nombre }}
                        </span>
                    </td>
                    <!-- Carrusel de Imágenes -->
                    <td style="vertical-align: middle;">
                        @if($emprendimiento->imagenes->count() > 0)
                            <div id="carousel-{{ $emprendimiento->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($emprendimiento->imagenes as $index => $imagen)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $imagen->path) }}" 
                                                 class="d-block w-100" 
                                                 alt="Imagen {{ $index + 1 }}" 
                                                 style="height: 100px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $emprendimiento->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $emprendimiento->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        @else
                            <span class="text-muted">Sin imágenes</span>
                        @endif
                    </td>
                    <!-- Acciones -->
                    <td style="vertical-align: middle;">
                        <div class="action-buttons d-flex justify-content-center">
                            <!-- Botón Editar -->
                            <a href="{{ route('editar.emprendimiento', $emprendimiento->id) }}" 
                               class="btn custom-btn btn-secondary shadow-sm" 
                               style="font-size: 14px;">
                               <i class="fas fa-edit me-1"></i> Editar
                            </a>
                            
                            <!-- Botón Gestionar Productos -->
                            <a href="{{ route('emprendimiento.productos', $emprendimiento->id) }}" 
                               class="btn custom-btn btn-primary shadow-sm" 
                               style="font-size: 14px;">
                               <i class="fas fa-box-open me-1"></i> Productos
                            </a>
                            
                            <!-- Botón Eliminar -->
                            <form action="{{ route('eliminar.emprendimiento', $emprendimiento->id) }}" 
                                  method="POST" 
                                  style="display:inline;" 
                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este emprendimiento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn custom-btn btn-danger shadow-sm" style="font-size: 14px;">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .action-buttons {
        gap: 10px;
    }
    .custom-btn {
        white-space: nowrap;
        border-color: #439FA5;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    .btn-primary {
        background-color: #439FA5;
    }
    .btn-primary:hover {
        background-color: #367f85;
        border-color: #367f85;
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 12px;
    }
</style>

@endsection
