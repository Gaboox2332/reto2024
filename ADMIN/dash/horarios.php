<?php
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
// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); window.location = "../index.php";</script>';
    exit();
}

include 'php/conexion_be2.php';
// Determinar el rol del usuario
$rol_usuario = $_SESSION['role'];
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.4/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-cancel {
            font-size: 15px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
    <script>
        function buscarHorario() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscador");
            filter = input.value.toLowerCase();
            table = document.getElementById("tablaHorarios");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");

                for (var j = 1; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
    <script>
    function openEditModal(id) {
        // Obtener los datos del horario usando AJAX
        fetch(`php/get_horario.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                // Rellenar el modal con los datos
                document.getElementById('modalID').value = data.id;
                document.getElementById('hora_inicio').value = data.time;
                
                // Mostrar el modal
                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error al obtener los datos del horario:', error);
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que quieres eliminar este horario?")) {
            window.location.href = "php/eliminar_horario.php?id=" + id;
        }
    }
</script>

</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-yellow-400"><span class="text-blue-400">ITEE</span>-RESERVATIONS</a>
        <div class="space-x-4">
            <a href="../../USUARIOS/index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Vista Usuario</a>
            <a href="../php/cerrar_sesion.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-4 md:fixed md:h-screen md:top-14 md:left-0 flex-shrink-0">
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
        <div class="mt-auto text-gray-500 text-sm">
            Sesión Iniciada Como:<br>
            <?= htmlspecialchars($nombre_rol) ?>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 ml-64 md:ml-64 bg-gray-100">
        <div class="container mx-auto">
            <h2 class="text-2xl font-semibold mb-4">Administrar Horarios</h2>
            <a href="agregar_horario.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4 inline-block">Agregar Horario</a>
            <input class="form-input mb-4" type="text" id="buscador" onkeyup="buscarHorario()" placeholder="Buscar horario">

            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md" id="tablaHorarios">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 border-b">Hora Inicio</th>
                        <th class="px-4 py-2 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí deberías generar las filas de la tabla dinámicamente con PHP -->
                    <?php
                    // Incluir el archivo de conexión a la base de datos
                    include 'php/conexion_be.php';

                    // Consultar los horarios disponibles
                    $result = mysqli_query($conexion, "SELECT id, time FROM available_times");

                    while($horario = mysqli_fetch_assoc($result)):
                    ?>
                    <tr class="border-b">
                        <td class="px-5 py-2"><?= htmlspecialchars($horario['time']) ?></td>
                        <td class="px-4 py-2">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md" onclick="openEditModal(<?= $horario['id'] ?>)">Editar</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md" onclick="confirmarEliminacion(<?= $horario['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

   <!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full relative">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-semibold mb-4">Editar Horario</h2>
        <form action="php/actualizar_horario.php" method="POST">
            <input type="hidden" name="ID" id="modalID" value="">
            <div class="mb-4">
                <label for="hora_inicio" class="block text-gray-700">Hora Inicio</label>
                <input type="time" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm" name="hora_inicio" id="hora_inicio" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

</body>
<!-- Agrega este script al final de tu archivo index.php -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener los parámetros de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');
        const action = urlParams.get('action');

        // Verificar si hay un mensaje y que la acción es 'delete'
        if (msg && action === 'delete') {
            let message = '';

            // Definir el mensaje en función del valor del parámetro 'msg'
            switch (msg) {
                case 'success':
                    message = 'Horario eliminado exitosamente';
                    break;
                case 'error':
                    message = 'Error al eliminar el horario';
                    break;
                case 'invalid_id':
                    message = 'ID de horario inválido';
                    break;
                case 'no_id':
                    message = 'No se recibió el ID del horario';
                    break;
                case 'agregado':
                    message = 'Horario Agregado Exitosamente';
                    break;
                default:
                    message = 'Ocurrió un error desconocido';
            }

            // Mostrar el mensaje en un alert
            alert(message);
        }
    });
</script>

</html>
