@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header text-white text-center" style="background-color: #439FA5; border-radius: 10px 10px 0 0;">
                    <h4 class="mb-0">{{ __('Crear Emprendimiento') }}</h4>
                </div>

                <div class="card-body" style="background-color: #F9F9F9;">
                    <form method="POST" action="{{ route('guardar.emprendimiento') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">{{ __('Nombre del Emprendimiento') }}</label>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autofocus style="border-radius: 5px;">
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">{{ __('Descripción del Emprendimiento') }}</label>
                            <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" rows="4" required style="border-radius: 5px;">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Subir Imágenes -->
                        <div class="mb-3">
    <label for="imagenes" class="form-label">{{ __('Imágenes') }}</label>
    <input id="imagenes" type="file" class="form-control @error('imagenes') is-invalid @enderror" name="imagenes[]" multiple required>
    @error('imagenes')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>


                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="categoria" class="form-label">{{ __('Categoría') }}</label>
                            <select id="categoria" class="form-select @error('categoria_id') is-invalid @enderror" name="categoria_id" required style="border-radius: 5px;">
                                <option value="" disabled selected>{{ __('Selecciona una categoría') }}</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de enviar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary custom-btn">{{ __('Crear Emprendimiento') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de error -->
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

<!-- Estilos personalizados -->
<style>
    .custom-btn {
        background-color: #0A7075;
        border-color: #0A7075;
        color: white;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .custom-btn:hover {
        background-color: #032F30;
        border-color: #032F30;
    }

    .form-label {
        color: #0C969C;
        font-weight: bold;
    }
</style>

@endsection
