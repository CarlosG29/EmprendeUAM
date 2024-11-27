@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h2 class="mb-4 text-center">Emprendimientos Favoritos</h2>

    <!-- Botón de Exportación -->
    <div class="mb-4 d-flex justify-content-end">
        <a href="{{ route('emprendimientos.exportFavoritos') }}" class="btn btn-success shadow-sm custom-btn" style="border-radius: 5px;">Exportar Emprendimientos Favoritos</a>
    </div>

    @if($emprendimientos->isEmpty())
        <p class="text-center">No tienes emprendimientos favoritos aún.</p>
    @else
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($emprendimientos as $emprendimiento)
        <div class="col">
            <div class="card h-100 shadow-sm" style="border-radius: 10px;">

                <!-- Carrusel para imágenes -->
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

                <div class="card-body d-flex flex-column">
                    <!-- Título del Emprendimiento -->
                    <h5 class="card-title text-center">{{ $emprendimiento->nombre }}</h5>

                    <!-- Descripción -->
                    <p class="card-text text-center flex-grow-1">{{ \Illuminate\Support\Str::limit($emprendimiento->descripcion, 100, '...') }}</p>

                    <!-- Información del Emprendedor -->
                    <p class="card-text"><strong>Emprendedor:</strong> {{ $emprendimiento->emprendedor->nombre }}</p>
                    <p class="card-text"><strong>Teléfono:</strong> {{ $emprendimiento->emprendedor->celular }}</p>
                    <p class="card-text"><strong>Categoría:</strong> {{ $emprendimiento->categoria->nombre }}</p>

                    <!-- Botones -->
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <!-- Botón Ver Más -->
                        <a href="{{ route('emprendimientos.show', $emprendimiento->id) }}" class="btn btn-primary custom-btn">Ver más</a>
                        
                        <!-- Botón Favorito -->
                        @auth
                        @php
                            $isFavorite = $emprendimiento->preferencias()->where('estudiante_id', auth()->id())->where('favorito', true)->exists();
                        @endphp
                        <button class="btn btn-link p-0" onclick="toggleFavorite({{ $emprendimiento->id }}, this)">
                            <i class="fas fa-heart fa-2x" style="color: {{ $isFavorite ? '#F47E17' : 'gray' }};"></i>
                        </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
    function toggleFavorite(emprendimientoId, button) {
        const heartIcon = button.querySelector('i');
        const isFavorite = heartIcon.style.color === '#F47E17';
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
                    heartIcon.style.color = '#F47E17';
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

<style>
    /* Estilos Personalizados */
    .custom-btn {
        background-color: #439FA5;
        border-color: #439FA5;
        color: white;
        transition: all 0.3s ease;
    }

    .custom-btn:hover {
        background-color: #367f85;
        border-color: #367f85;
    }

    .card {
        border: none;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1rem;
    }
</style>
