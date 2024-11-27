@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Perfil</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto de Perfil -->
                <div class="mb-3 text-center">
                    @if ($user->foto_perfil)
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" alt="Foto de perfil" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                </div>

                <!-- Subir nueva foto -->
                <div class="mb-3">
                    <label for="foto_perfil" class="form-label">Cambiar Foto de Perfil</label>
                    <input type="file" class="form-control @error('foto_perfil') is-invalid @enderror" id="foto_perfil" name="foto_perfil" accept="image/*">
                    @error('foto_perfil')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Apellido -->
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido', $user->apellido) }}" required>
                    @error('apellido')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label for="celular" class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" value="{{ old('celular', $user->celular) }}" required>
                    @error('celular')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Carrera -->
                <div class="mb-3">
                    <label for="carrera_id" class="form-label">Carrera</label>
                    <select class="form-select @error('carrera_id') is-invalid @enderror" id="carrera_id" name="carrera_id" required>
                        <option value="" disabled selected>Selecciona una carrera</option>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->id }}" {{ old('carrera_id', $user->carrera_id) == $carrera->id ? 'selected' : '' }}>
                                {{ $carrera->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('carrera_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Contraseña Actual -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Nueva Contraseña -->
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nueva Contraseña (Opcional)</label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                    @error('new_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Confirmar Nueva Contraseña -->
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary w-100 mt-3" style="background-color: #439FA5; border-color: #439FA5;">
                    Actualizar Perfil
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
