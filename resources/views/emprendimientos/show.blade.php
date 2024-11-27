@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Galería de Imágenes del Emprendimiento -->
    <div class="row mb-4 text-center">
        <div class="col">
            @if($emprendimiento->imagenes->isNotEmpty())
                <div id="carouselEmprendimiento" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($emprendimiento->imagenes as $key => $imagen)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $imagen->path) }}" 
                                     class="d-block w-100" 
                                     alt="{{ $emprendimiento->nombre }}" 
                                     style="max-height: 400px; object-fit: cover; border-radius: 10px;">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselEmprendimiento" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselEmprendimiento" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            @else
                <img src="{{ asset('images/default-placeholder.png') }}" 
                     class="img-fluid mb-4" 
                     alt="Sin imágenes" 
                     style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px;">
            @endif
            <h1 class="display-4 text-white" 
                style="background-color: #439FA5; padding: 15px; border-radius: 8px;">
                <strong>{{ $emprendimiento->nombre }}</strong>
            </h1>
            <hr style="border: 2.5px solid #000; width: 70%; margin: 20px auto;">
            <p class="lead" style="max-width: 80%; margin: 0 auto; text-align: center;">
                {{ $emprendimiento->descripcion }}
            </p>
        </div>
    </div>

    <!-- Información detallada del Emprendedor -->
    <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header text-white" style="background-color: #439FA5; border-radius: 10px 10px 0 0;">
                    <h2 class="mb-0">Datos del Emprendedor</h2>
                </div>
                <div class="card-body" style="background-color: #F9F9F9;">
                    <p><strong>Nombre:</strong> {{ $emprendimiento->emprendedor->nombre }}</p>
                    <p><strong>Email:</strong> {{ $emprendimiento->emprendedor->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $emprendimiento->emprendedor->celular }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    <div class="row mb-4 text-center">
        <div class="col">
            <h2 class="mb-4" style="color: #000;">Productos</h2>
            <hr style="border: 2.0px solid #000; width: 50%; margin: 0 auto;">
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($productos as $producto)
            @if(!$producto->oculto)
                <div class="col">
                    <div class="card h-100 shadow-sm" style="border-radius: 10px;">
                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                             class="card-img-top" 
                             alt="{{ $producto->nombre }}" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
                        <div class="card-body" style="background-color: #F9F9F9;">
                            <h5 class="card-title" style="color: #439FA5;">{{ $producto->nombre }}</h5>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($producto->descripcion, 100) }}</p>
                            <p><strong>Precio:</strong> C${{ $producto->precio }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-md-8 offset-md-2 text-center">
                <p>No hay productos disponibles para mostrar en este emprendimiento.</p>
            </div>
        @endforelse
    </div>

    <!-- Sección de Comentarios -->
    <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header text-white" style="background-color: #439FA5; border-radius: 10px 10px 0 0;">
                    <h2 class="mb-0">Comentarios</h2>
                </div>
                <div class="card-body" style="background-color: #F9F9F9;">
                    @auth
                        @if (auth()->user()->status)
                            <form action="{{ route('comentarios.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="comentario" class="form-label">Añadir un comentario:</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
                                    <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="emprendimiento_id" value="{{ $emprendimiento->id }}">
                                </div>
                                <button type="submit" class="btn btn-primary custom-btn">Enviar</button>
                            </form>
                        @else
                            <p class="text-muted">Tu cuenta ha sido deshabilitada. No puedes añadir comentarios.</p>
                        @endif
                    @endauth

                    @guest
                        <p class="text-muted">Inicia sesión para añadir un comentario.</p>
                    @endguest

                    <!-- Listado de comentarios -->
                    <hr>
                    @foreach($emprendimiento->comentarios as $comentario)
                        <div class="d-flex align-items-start mb-4">
                            <img class="rounded-circle me-3" 
                                 src="{{ $comentario->estudiante->foto_perfil ? asset('storage/' . $comentario->estudiante->foto_perfil) : asset('images/default-profile.png') }}" 
                                 alt="{{ $comentario->estudiante->nombre }}" 
                                 style="width: 50px; height: 50px; object-fit: cover;">

                            <div class="flex-grow-1">
                                <h5 class="mb-1 d-flex justify-content-between">
                                    <span>
                                        {{ $comentario->estudiante->nombre }} {{ $comentario->estudiante->apellido }}
                                        <small class="text-muted">
                                            {{ $comentario->editado ? 'Editado ' : '' }}
                                            {{ $comentario->updated_at->diffForHumans() }}
                                        </small>
                                    </span>
                                    @if(auth()->id() === $comentario->estudiante_id || auth()->user()->admin)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(auth()->id() === $comentario->estudiante_id)
                                                <li><button class="dropdown-item" onclick="toggleEditForm({{ $comentario->id }})">Editar</button></li>
                                            @endif
                                            <li>
                                                <form method="POST" action="{{ route('comentarios.destroy', $comentario->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Eliminar</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </h5>

                                <!-- Formulario de edición inline -->
                                <form id="editForm-{{ $comentario->id }}" action="{{ route('comentarios.update', $comentario->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="comentario" class="form-control mb-2" required>{{ $comentario->comentario }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditForm({{ $comentario->id }})">Cancelar</button>
                                </form>

                                <!-- Texto del comentario -->
                                <p id="commentText-{{ $comentario->id }}">{{ $comentario->comentario }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Sección de Calificación -->
    <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header text-white" style="background-color: #439FA5; border-radius: 10px 10px 0 0;">
                    <h2 class="mb-0">Calificación</h2>
                </div>
                <div class="card-body" style="background-color: #F9F9F9;">
                    @auth
                        <form action="{{ route('calificar.emprendimiento' )}}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="calificacion" class="form-label">Calificación (0 al 5):</label>
                                <input type="number" class="form-control" id="calificacion" name="calificacion" min="0" max="5" required>
                                <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="emprendimiento_id" value="{{ $emprendimiento->id }}">
                            </div>
                            <button type="submit" class="btn btn-primary" style="background-color: #439FA5; border-color: #439FA5;">Enviar Calificación</button>
                        </form>
                    @endauth

                    @guest
                        <p class="text-muted">Inicia sesión para dejar una calificación.</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function toggleEditForm(id) {
    const form = document.getElementById(`editForm-${id}`);
    const commentText = document.getElementById(`commentText-${id}`);
    if (form.style.display === "none") {
        form.style.display = "block";
        commentText.style.display = "none";
    } else {
        form.style.display = "none";
        commentText.style.display = "block";
    }
}
</script>

<style>
.custom-btn {
    background-color: #439FA5;
    border-color: #439FA5;
    color: white;
}

.custom-btn:hover {
    background-color: #367f85;
    border-color: #367f85;
}
</style>
