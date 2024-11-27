@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Producto</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #439FA5; color: white; border-radius: 10px 10px 0 0;">
                    Editar Producto
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('editar.producto.update', ['emprendimiento' => $emprendimiento->id, 'producto' => $producto->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nombre del Producto -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required autofocus>
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Descripción del Producto -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" rows="4" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Imagen del Producto -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            @if($producto->imagen)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid" style="max-height: 200px; border-radius: 10px;">
                                </div>
                            @endif
                            <input id="imagen" type="file" class="form-control @error('imagen') is-invalid @enderror" name="imagen" accept="image/*">
                            @error('imagen')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Precio del Producto -->
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input id="precio" type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                            @error('precio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Oculto -->
                        <div class="mb-3 form-check">
                            <input type="hidden" name="oculto" value="0">
                            <input id="oculto" type="checkbox" class="form-check-input @error('oculto') is-invalid @enderror" name="oculto" value="1" {{ old('oculto', $producto->oculto) ? 'checked' : '' }}>
                            <label for="oculto" class="form-check-label">Ocultar Producto</label>
                            @error('oculto')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de Envío -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn custom-btn">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mostrar errores globales si existen -->
    @if ($errors->any())
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Estilos personalizados -->
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

@endsection
