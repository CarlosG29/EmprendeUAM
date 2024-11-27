<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmprendeUAM</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Tipografía moderna */
        body, .navbar-brand, .nav-link, .dropdown-item, .btn {
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .custom-navbar {
            background-color: #0A7075;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .custom-navbar .navbar-brand,
        .custom-navbar .nav-link {
            color: white;
            padding-left: 20px;
        }

        .custom-navbar .nav-link:hover {
            color: #dddddd;
            transition: color 0.3s;
        }

        /* Badge de Admin */
        .admin-badge {
            background-color: #F47E17;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Transición y sombra en botones */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Espaciado y estilos en el menú desplegable */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 10px 20px;
        }

        .dropdown-item:hover {
            background-color: #439FA5;
            color: white;
            transition: background-color 0.3s;
        }

        /* Logo y espaciado en navbar */
        .navbar-brand img {
            height: 36px;
            margin-right: 10px;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
            }

            .custom-navbar .nav-link {
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg custom-navbar">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <span>EmprendeUAM</span>
        </a>
        @if (Auth::check() && Auth::user()->admin)
            <span class="admin-badge">ADMIN</span>
        @endif
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Registrarme
                            </a>
                        </li>
                    @endif
                @else
                    <!-- Nombre del usuario -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-user"></i> {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @unless (Auth::user()->admin)
                                <li><a class="dropdown-item" href="{{ route('profile.edit', Auth::user()->id) }}">
                                    <i class="fas fa-user-edit"></i> Editar Cuenta
                                </a></li>
                            @endunless
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    <!-- Emprendimientos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownEmprendimientos" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-lightbulb"></i> Emprendimientos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownEmprendimientos">
                            <li><a class="dropdown-item" href="{{ route('misEmprendimientos') }}">
                                <i class="fas fa-folder"></i> Mis Emprendimientos
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('crear.emprendimiento') }}">
                                <i class="fas fa-plus-circle"></i> Crear Emprendimiento
                            </a></li>
                        </ul>
                    </li>

                    <!-- Favoritos -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('favorites') ? 'active' : '' }}" href="{{ route('favorites') }}">
                            <i class="fas fa-heart"></i> Favoritos
                        </a>
                    </li>

                    <!-- Opciones exclusivas para administradores -->
                    @if (Auth::user()->admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('estudiantes') ? 'active' : '' }}" href="{{ route('estudiantes') }}">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('emprendimientos/pendientes') ? 'active' : '' }}" href="{{ route('emprendimientos.pendientes') }}">
                                <i class="fas fa-tasks"></i> Solicitudes
                            </a>
                        </li>
                    @endif

                    <!-- Mensaje para cuentas deshabilitadas -->
                    @if (!Auth::user()->status)
                        <li class="nav-item">
                            <span class="nav-link text-warning">
                                <i class="fas fa-exclamation-circle"></i> Tu cuenta está inhabilitada. Solo puedes ver y calificar emprendimientos.
                            </span>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
