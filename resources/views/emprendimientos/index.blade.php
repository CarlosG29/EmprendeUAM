@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <!-- Mensajes de éxito o error -->
    @if (session('success'))
        <div class="alert alert-success shadow-sm" style="border-radius: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger shadow-sm" style="border-radius: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="mb-4 text-center" style="color: #439FA5;">Listado de Emprendimientos</h2>

    <!-- Botón de Exportación -->
    @auth
    <div class="mb-4 d-flex justify-content-end">
        <a href="{{ route('emprendimientos.exportListado') }}" class="btn btn-success shadow-sm" style="border-radius: 5px;">Exportar Listado de Emprendimientos</a>
    </div>
    @endauth

    <!-- Barra de búsqueda y filtro de categorías -->
    <div class="mb-4">
        <form action="{{ route('emprendimientos.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o descripción" value="{{ request()->query('search') }}">
            <select name="category" class="form-control">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request()->query('category') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary" style="background-color: #439FA5; border-color: #439FA5;">Buscar</button>
        </form>
    </div>

    <!-- Listado de emprendimientos verificados -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($emprendimientos as $emprendimiento)
            @if ($emprendimiento->estado_emp && $emprendimiento->estado_emp->nombre === 'VERIFICADO')
                <div class="col">
                    <div class="card h-100 shadow-sm" style="border-radius: 10px;">
                        
                        <!-- Carrusel de imágenes -->
                        @if($emprendimiento->imagenes->count() > 0)
                            <div id="carousel-{{ $emprendimiento->id }}" class="carousel slide mb-3" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($emprendimiento->imagenes as $index => $imagen)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $imagen->path) }}" 
                                                 class="d-block w-100" 
                                                 alt="Imagen {{ $index + 1 }}" 
                                                 style="height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
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
                        @endif

                        <div class="card-body d-flex flex-column" style="background-color: #F9F9F9; border-radius: 0 0 10px 10px;">
                            <h5 class="card-title text-center" style="color: #439FA5;">{{ $emprendimiento->nombre }}</h5>
                            <hr>
                            <p class="card-text"><strong>Emprendedor:</strong> {{ $emprendimiento->emprendedor->nombre }} {{ $emprendimiento->emprendedor->apellido }}</p>
                            <p class="card-text"><strong>Carrera:</strong> {{ $emprendimiento->emprendedor->carrera->nombre }}</p>
                            <p class="card-text"><strong>Teléfono:</strong> {{ $emprendimiento->emprendedor->celular }}</p>
                            <p class="card-text"><strong>Categoría:</strong> {{ $emprendimiento->categoria->nombre }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <!-- Calificación -->
                                <div class="d-flex align-items-center">
                                    <div class="star-rating me-2">
                                        @php
                                            $promedioCalificacion = $emprendimiento->promedioCalificaciones();
                                            $filledStars = floor($promedioCalificacion);
                                            $halfStar = $promedioCalificacion - $filledStars >= 0.5;
                                        @endphp
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $filledStars)
                                                <i class="fas fa-star" style="color: #FFD700;"></i>
                                            @elseif ($i == $filledStars && $halfStar)
                                                <i class="fas fa-star-half-alt" style="color: #FFD700;"></i>
                                            @else
                                                <i class="far fa-star" style="color: #FFD700;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span>{{ number_format($promedioCalificacion, 1) }}</span>
                                </div>

                                <!-- Ver más -->
                                <a href="{{ route('emprendimientos.show', $emprendimiento->id) }}" class="btn btn-primary" style="background-color: #439FA5; border-color: #439FA5;">Ver más</a>
                                
                                <!-- Favoritos -->
                                @auth
                                    @php
                                        $isFavorite = $emprendimiento->preferencias()->where('estudiante_id', auth()->id())->where('favorito', true)->exists();
                                    @endphp
                                    <button class="btn btn-link p-0" onclick="toggleFavorite({{ $emprendimiento->id }}, this)">
                                        <i class="fas fa-heart fa-2x" style="color: {{ $isFavorite ? 'red' : 'gray' }};"></i>
                                    </button>
                                @endauth

                                <!-- Eliminar (solo administrador) -->
                                @if(Auth::check() && Auth::user()->admin)
                                    <form action="{{ route('emprendimientos.destroyAsAdmin', $emprendimiento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este emprendimiento?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    function toggleFavorite(emprendimientoId, button) {
        const heartIcon = button.querySelector('i');
        const isFavorite = heartIcon.style.color === 'red';
        const url = isFavorite ? '{{ route('favorites.remove', ':id') }}' : '{{ route('favorites.add', ':id') }}';

        fetch(url.replace(':id', emprendimientoId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.favorito) {
                    heartIcon.style.color = 'red';
                    alert('Emprendimiento añadido a favoritos!');
                } else {
                    heartIcon.style.color = 'gray';
                    alert('Emprendimiento eliminado de favoritos!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al cambiar el estado de favorito.');
            });
    }
</script>

@endsection
