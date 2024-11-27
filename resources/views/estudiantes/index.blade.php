@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h2 class="mb-4 text-center">Usuarios Estudiantes</h2>

    <!-- Botones de Exportación e Importación -->
    <div class="mb-4 d-flex justify-content-end">
        <a href="{{ route('estudiantes.export') }}" class="btn btn-success me-2">Exportar Usuarios Estudiantes</a>
        <form action="{{ route('estudiantes.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".xls,.xlsx" required class="form-control me-2" style="width: auto; display: inline-block;">
            <button type="submit" class="btn btn-primary">Importar Usuarios Estudiantes</button>
        </form>
    </div>

    <!-- Barra de búsqueda -->
    <form action="{{ route('usuarios.buscar') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="cif" class="form-label">CIF:</label>
                <input type="text" class="form-control" id="cif" name="cif" value="{{ request()->input('cif') }}">
            </div>
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre/Apellido:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ request()->input('nombre') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #439FA5; border-color: #439FA5;">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de Usuarios -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>CIF</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Carrera</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->cif }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->apellido }}</td>
                    <td>{{ $usuario->carrera ? $usuario->carrera->nombre : 'Sin asignar' }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <button type="button" class="btn {{ $usuario->status ? 'btn-danger' : 'btn-success' }}"
                                onclick="toggleStatus({{ $usuario->id }}, this)">
                            {{ $usuario->status ? 'Desactivar' : 'Activar' }}
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser({{ $usuario->id }})">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron usuarios.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleStatus(userId, button) {
        const url = button.classList.contains('btn-danger') 
                    ? `{{ route('estudiantes.desactivar', ':id') }}`
                    : `{{ route('estudiantes.activar', ':id') }}`;
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        fetch(url.replace(':id', userId), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.classList.toggle('btn-danger');
                button.classList.toggle('btn-success');
                button.textContent = data.message === 'Usuario activado correctamente' ? 'Desactivar' : 'Activar';
                alert(data.message);
            } else {
                alert('Hubo un error al cambiar el estado del usuario.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al cambiar el estado del usuario.');
        });
    }

    function deleteUser(userId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;

        fetch(`{{ route('estudiantes.destroy', ':id') }}`.replace(':id', userId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Hubo un error al eliminar el usuario.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar el usuario.');
        });
    }
</script>

@endsection
