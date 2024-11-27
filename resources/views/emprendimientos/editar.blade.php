@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header text-white text-center" style="background-color: #439FA5; border-radius: 10px 10px 0 0;">
                    <h4 class="mb-0">{{ __('Editar Emprendimiento') }}</h4>
                </div>

                <div class="card-body" style="background-color: #F9F9F9;">
                    <!-- Formulario con onsubmit para depuración -->
                    <form method="POST" action="{{ route('actualizar.emprendimiento', $emprendimiento->id) }}" 
                          enctype="multipart/form-data" 
                          onsubmit="return verificarFormulario(event)">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">{{ __('Nombre del Emprendimiento') }}</label>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   name="nombre" value="{{ $emprendimiento->nombre }}" required autofocus style="border-radius: 5px;">
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">{{ __('Descripción del Emprendimiento') }}</label>
                            <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      name="descripcion" rows="4" required style="border-radius: 5px;">{{ $emprendimiento->descripcion }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Imágenes Actuales -->
                        <div class="mb-4">
                            <h5 class="form-label">{{ __('Imágenes Actuales') }}</h5>
                            <div class="row">
                                @foreach($emprendimiento->imagenes as $imagen)
                                    <div class="col-md-4 text-center">
                                        <img src="{{ asset('storage/' . $imagen->path) }}" alt="{{ $emprendimiento->nombre }}" 
                                             style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 5px; padding: 5px; margin-bottom: 10px;">

                                        @if ($emprendimiento->imagenes->count() > 1)
                                            <form method="POST" action="{{ route('eliminar.imagen', ['emprendimiento' => $emprendimiento->id, 'imagen' => $imagen->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm mt-2">{{ __('Eliminar') }}</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm mt-2" disabled>{{ __('No se puede eliminar') }}</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subir Nuevas Imágenes -->
                        <div class="mb-4">
                            <label for="imagenes" class="form-label">{{ __('Subir Nuevas Imágenes') }}</label>
                            <input id="imagenes" type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                                   name="imagenes[]" accept="image/*" multiple style="border-radius: 5px;">
                            <small class="text-muted">{{ __('Puedes subir múltiples imágenes seleccionándolas desde tu dispositivo.') }}</small>
                            @error('imagenes.*')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="categoria" class="form-label">{{ __('Categoría') }}</label>
                            <select id="categoria" class="form-select @error('categoria_id') is-invalid @enderror" 
                                    name="categoria_id" required style="border-radius: 5px;">
                                <option value="" disabled>{{ __('Selecciona una categoría') }}</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ $emprendimiento->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de Enviar -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary custom-btn">{{ __('Actualizar Emprendimiento') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de Error -->
    @if ($errors->any())
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="alert alert-danger shadow-sm" style="border-radius: 10px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Script de depuración -->
<script>
    function verificarFormulario(event) {
        console.clear();
        console.log('Formulario enviado');
        console.log('Datos del formulario:', new FormData(event.target));
        return true; // Cambia a false para detener el envío y revisar datos en consola.
    }
</script>

<!-- Estilos Personalizados -->
<style>
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

    .form-label {
        color: #0C969C;
        font-weight: bold;
    }
</style>
@endsection
