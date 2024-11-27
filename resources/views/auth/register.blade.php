@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow" style="border-radius: 10px; background-color: #032F30; color: #FFFFFF;">
                <div class="card-header text-center" style="background-color: #0A7075; color: #FFFFFF; border-radius: 10px 10px 0 0; font-size: 1.5rem; font-weight: bold;">
                    {{ __('Registro') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- CIF -->
                        <div class="mb-4">
                            <label for="cif" class="form-label">{{ __('CIF') }}</label>
                            <input id="cif" type="text" class="form-control @error('cif') is-invalid @enderror" name="cif" value="{{ old('cif') }}" required autofocus style="border-radius: 5px;">
                            @error('cif')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required style="border-radius: 5px;">
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Apellido -->
                        <div class="mb-4">
                            <label for="apellido" class="form-label">{{ __('Apellido') }}</label>
                            <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required style="border-radius: 5px;">
                            @error('apellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Celular -->
                        <div class="mb-4">
                            <label for="celular" class="form-label">{{ __('Celular') }}</label>
                            <input id="celular" type="text" class="form-control @error('celular') is-invalid @enderror" name="celular" value="{{ old('celular') }}" required style="border-radius: 5px;">
                            @error('celular')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required style="border-radius: 5px;">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required style="border-radius: 5px;">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required style="border-radius: 5px;">
                        </div>

                        <!-- Carrera -->
                        <div class="mb-4">
                            <label for="carrera_id" class="form-label">{{ __('Carrera') }}</label>
                            <select id="carrera_id" name="carrera_id" class="form-control" style="border-radius: 5px;">
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ (old('carrera_id') == $carrera->id) ? 'selected' : '' }}>{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón Registrar -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #0C969C; border-color: #0C969C; border-radius: 5px;">
                                {{ __('Registrar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
