<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar y Exportar Datos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Importación y Exportación de Carreras</h2>

        <!-- Mensaje de Éxito -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de Importación -->
        <form action="{{ route('carrera.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Selecciona un archivo para importar</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar Carreras</button>
        </form>

        <!-- Botón de Exportación -->
        <div class="mt-4">
            <a href="{{ route('carrera.export') }}" class="btn btn-success">Exportar Carreras</a>
        </div>
    </div>
</body>
</html>
