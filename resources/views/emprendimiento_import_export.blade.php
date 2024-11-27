<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar y Exportar Emprendimientos</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Importar y Exportar Emprendimientos</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de Importación -->
        <form action="{{ route('emprendimiento.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Selecciona un archivo para importar</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar Emprendimientos</button>
        </form>

        <!-- Botón de Exportación -->
        <div class="mt-4">
            <a href="{{ route('emprendimiento.export') }}" class="btn btn-success">Exportar Emprendimientos</a>
        </div>
    </div>
</body>
</html>
