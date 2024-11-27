@extends('layouts.app')

@section('content')

<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse ($emprendimientos as $emprendimiento)
        @if ($emprendimiento->estado_emp && $emprendimiento->estado_emp->nombre === 'PENDIENTE')
            <div class="col">
                <div class="card h-100 shadow-sm" style="border-radius: 10px;">
                    <!-- Mostrar múltiples imágenes con un carrusel -->
                    @if($emprendimiento->imagenes->isNotEmpty())
                        <div id="carousel-{{ $emprendimiento->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($emprendimiento->imagenes as $imagen)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $imagen->path) }}" 
                                             class="d-block w-100" 
                                             alt="{{ $emprendimiento->nombre }}" 
                                             style="height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $emprendimiento->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $emprendimiento->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column" style="background-color: #F9F9F9;">
                        <h5 class="card-title text-center" style="color: #439FA5;">{{ $emprendimiento->nombre }}</h5>
                        <p class="card-text text-center">
                            {{ \Illuminate\Support\Str::limit($emprendimiento->descripcion, 100, '...') }}
                        </p>
                        <p class="card-text"><strong>Emprendedor:</strong> {{ $emprendimiento->emprendedor->nombre }}</p>
                        <p class="card-text"><strong>Teléfono:</strong> {{ $emprendimiento->emprendedor->celular }}</p>
                        <p class="card-text"><strong>Categoría:</strong> {{ $emprendimiento->categoria->nombre }}</p>
                        <p class="card-text"><strong>Estado:</strong> {{ $emprendimiento->estado_emp->nombre }}</p>
                        <div class="mt-auto d-flex flex-column gap-2">
                            <!-- Botón de Validar -->
                            <form action="{{ route('emprendimientos.validar', $emprendimiento->id) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary w-100" style="background-color: #439FA5; border-color: #439FA5;">Validar</button>
                            </form>
                            <!-- Botón de Rechazar -->
                            <form action="{{ route('emprendimientos.rechazar', $emprendimiento->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger w-100">Rechazar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12">
            <p class="text-center">No hay emprendimientos pendientes de validación.</p>
        </div>
    @endforelse
</div>


@endsection
