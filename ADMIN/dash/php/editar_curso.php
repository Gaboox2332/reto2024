<?php
session_start();
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo '<script>
    alert("Debes iniciar sesión para acceder a esta página.");
    window.location = "../index.php";
    </script>';
    exit();
}

if ($_SESSION['status'] != 1) {
    echo '<script>
    alert("Este usuario está inactivo");
    window.location = "../index.php";
    </script>';
    session_destroy();
    exit();
}

// Obtener el ID del curso desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si el ID del curso es válido, obtener los detalles del curso
if ($id > 0) {
    $query = "SELECT id, nombre_curso FROM cursos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $curso_id, $curso_nombre);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$curso_id) {
        echo '<script>
        alert("Curso no encontrado.");
        window.location = "cursos.php";
        </script>';
        exit();
    }
} else {
    echo '<script>
    alert("ID de curso inválido.");
    window.location = "cursos.php";
    </script>';
    exit();
}

// Procesar el formulario de edición del curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

    if (!empty($nombre)) {
        $query = "UPDATE cursos SET nombre_curso = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);

        if ($stmt === false) {
            echo '<script>
            alert("Error en la preparación de la consulta: ' . mysqli_error($conexion) . '");
            window.location = "editar_curso.php?id=' . $id . '";
            </script>';
            exit();
        }

        mysqli_stmt_bind_param($stmt, 'si', $nombre, $id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo '<script>
            alert("Curso actualizado exitosamente.");
            window.location = "../cursos.php";
            </script>';
        } else {
            echo '<script>
            alert("Error al actualizar el curso: ' . mysqli_stmt_error($stmt) . '");
            window.location = "editar_curso.php?id=' . $id . '";
            </script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<script>
        alert("El nombre del curso no puede estar vacío.");
        window.location = "editar_curso.php?id=' . $id . '";
        </script>';
    }

    mysqli_close($conexion);
    exit();
}
// Determinar el rol del usuario
$rol_usuario = $_SESSION['role'];
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <!-- Agrega aquí tus enlaces de CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-yellow-400"><span class="text-blue-400">ITEE</span>-RESERVATIONS</a>
        <div class="space-x-4">
            <a href="./assets/fpdf/PruebaH.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Generar Reportes</a>
            <a href="../../USUARIOS/index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Vista Usuario</a>
            <a href="../php/cerrar_sesion.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="flex flex-1 flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-gray-900 text-white p-4 md:fixed md:h-screen md:top-15 md:left-0">
            <div class="text-gray-400 mb-4">Centro Principal</div>
            <ul>
                <li>
                    <a href="index.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Panel de Control</a>
                </li>
            </ul>
            <div class="text-gray-400 mt-4 mb-2">Administración</div>
            <ul>
                <li>
                    <a href="modificar.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Usuarios</a>
                </li>
                <li>
                    <a href="horarios.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Horarios</a>
                </li>
                <li>
                    <a href="cursos.php" class="block py-2 px-4 bg-gray-700 rounded">Cursos</a>
                </li>
            </ul>
            <div class="text-gray-400 mt-4 mb-2">Estadísticas</div>
            <ul>
                <li><a href="charts.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Tipo Gráficas</a></li>
                <li><a href="tables.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Tipo Tablas</a></li>
            </ul>
            <div class="mt-4 text-gray-500 text-sm">
                Sesión Iniciada Como:<br>
                <?= htmlspecialchars($nombre_rol) ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-64">
            <div class="container mx-auto">
                <h2 class="text-2xl font-semibold mb-4">Editar Curso</h2>
                <form action="editar_curso.php?id=<?= $id ?>" method="POST">
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700">Nombre del Curso</label>
                        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($curso_nombre) ?>" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
