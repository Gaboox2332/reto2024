<?php
// Incluir el archivo de conexión a la base de datos
include 'php/conexion_be.php';
session_start();

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

include 'php/conexion_be2.php';// Determinar el rol del usuario
$rol_usuario = $_SESSION['role'];
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $hora_inicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : '';

    // Validar los datos
    if (empty($hora_inicio)) {
        $error = "La hora de inicio es obligatoria.";
    } else {
        // Preparar la consulta para insertar el nuevo horario
        $query = "INSERT INTO available_times (time) VALUES (?)";
        $stmt = mysqli_prepare($conexion, $query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
        }

        // Vincular el parámetro y ejecutar la consulta
        mysqli_stmt_bind_param($stmt, 's', $hora_inicio);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Redirigir a la página principal de administración de horarios
            header('Location: horarios.php?msg=agregado&action=delete');
            exit;
        } else {
            $error = "Error al agregar el horario: " . mysqli_error($conexion);
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Horario</title>
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
                    <a href="index.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Horarios</a>
                </li>
                <li>
                    <a href="" class="block py-2 px-4 hover:bg-gray-700 rounded">Cursos</a>
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
        <main class="flex-1 p-4 md:ml-64">
            <div class="container mx-auto">
                <h2 class="text-2xl font-semibold mb-4">Agregar Horario</h2>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 text-red-500 p-4 rounded mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="agregar_horario.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-4">
                        <label for="hora_inicio" class="block text-gray-700">Hora Inicio</label>
                        <input type="time" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm" name="hora_inicio" id="hora_inicio" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Agregar Horario</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
