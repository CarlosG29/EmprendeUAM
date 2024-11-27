@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow" style="border-radius: 10px; background-color: #032F30; color: #FFFFFF;">
                <div class="card-header text-center" style="background-color: #0A7075; color: #FFFFFF; border-radius: 10px 10px 0 0; font-size: 1.5rem; font-weight: bold;">
                    {{ __('Iniciar Sesión') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- CIF -->
                        <div class="mb-4">
                            <label for="cif" class="form-label">{{ __('CIF') }}</label>
                            <input id="cif" type="text" class="form-control @error('cif') is-invalid @enderror" name="cif" value="{{ old('cif') }}" required autocomplete="cif" autofocus style="border-radius: 5px;">
                            @error('cif')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="border-radius: 5px;">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Recuérdame -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember" style="color: #FFFFFF;">
                                {{ __('Recuérdame') }}
                            </label>
                        </div>

                        <!-- Botón de Iniciar Sesión y Enlace de Recuperación -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #0C969C; border-color: #0C969C; border-radius: 5px;">
                                {{ __('Iniciar Sesión') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #6BA3BE;">
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
