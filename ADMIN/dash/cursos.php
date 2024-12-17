<?php
session_start();
// Incluir el archivo de conexión a la base de datos
include 'php/conexion_be.php';
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
// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); window.location = "../index.php";</script>';
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
    <title>Administrar Cursos</title>
    <!-- Agrega aquí tus enlaces de CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-yellow-400"><span class="text-blue-400">ITEE</span>-RESERVATIONS</a>
        <div class="space-x-4">
            <a href="../../USUARIOS/index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Vista Usuario</a>
            <a href="../php/cerrar_sesion.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="flex flex-1 flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-gray-900 text-white p-4 md:fixed md:h-screen md:top-15 md:left-0">
            <div class="text-gray-400 mb-4">Centro Principal</div>
            <ul>
                <li><a href="index.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Panel de Control</a></li>
            </ul>
            <div class="text-gray-400 mt-4 mb-2">Administración</div>
            <ul>
                <li><a href="modificar.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Usuarios</a></li>
                <li><a href="horarios.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Horarios</a></li>
                <li><a href="cursos.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Cursos</a></li>
            </ul>
            <div class="text-gray-400 mt-4 mb-2">Estadísticas</div>
            <ul>
                <li><a href="charts.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Tipo Calendario</a></li>
            </ul>
            <div class="mt-4 text-gray-500 text-sm">
                Sesión Iniciada Como:<br>
                <?= htmlspecialchars($nombre_rol) ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-64">
            <div class="container mx-auto">
                <h2 class="text-2xl font-semibold mb-4">Administrar Cursos</h2>
                <a href="php/agregar_curso.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4 inline-block">Agregar Curso</a>
                <input class="form-input mb-4 rounded-md border border-gray-700" type="text" id="buscador" onkeyup="buscarCurso()" placeholder="Buscar curso">

                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg" id="tablaCursos">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-2 py-2 border-b">Nombre del Curso</th>
                            <th class="px-2 py-2 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consultar los cursos disponibles
                        $result = mysqli_query($conexion, "SELECT id, nombre_curso FROM cursos");

                        while($curso = mysqli_fetch_assoc($result)):
                        ?>
                        <tr class="border-b">
                            <td class="px-2 py-2"><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                            <td class="px py-2">
                                <a href="php/editar_curso.php?id=<?= $curso['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Editar</a>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md" onclick="confirmarEliminacion(<?= $curso['id'] ?>)">Eliminar</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Script para confirmación de eliminación -->
    <script>
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este curso?')) {
                window.location.href = `php/eliminar_curso.php?id=${id}`;
            }
        }

        function buscarCurso() {
            const input = document.getElementById('buscador');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('tablaCursos');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td')[1];
                if (td) {
                    let txtValue = td.textContent || td.innerText;
                    tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>
