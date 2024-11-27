<!-- resources/views/auth/passwords/email.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #439FA5;">
                    <h4 class="mb-0">Restablecer Contraseña</h4>
                </div>
                <div class="card-body">
                    <p>
                        ¿Olvidaste tu contraseña? No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace
                        para restablecer tu contraseña.
                    </p>

                    <!-- Muestra mensajes de éxito si el enlace fue enviado -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Formulario de restablecimiento de contraseña -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Campo de correo electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Botón de enviar enlace de restablecimiento -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" style="background-color: #439FA5; border-color: #439FA5;">
                                Enviar Enlace de Restablecimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
