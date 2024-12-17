<?php
session_start();
include 'php/conexion_be.php';

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); window.location = "../index.php";</script>';
    exit();
}

// Determinar el rol del usuario
$rol_usuario = $_SESSION['role'];
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";
// Obtener los usuarios de la base de datos
$usuarios = mysqli_query($conexion, "SELECT id, nombre_completo, correo, role, status FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
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
        function buscarUsuario() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("buscador");
            filter = input.value.toLowerCase();
            table = document.getElementById("tablaUsuarios");
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

        function confirmarDesactivacion(id) {
            if (confirm("¿Estás seguro de que quieres cambiar el estado de este usuario?")) {
                window.location.href = "php/desactivar_usuario.php?id=" + id;
            }
        }

        function openEditModal(id) {
            fetch(`php/get_user_data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editModal').classList.remove('hidden');
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editNombre').value = data.nombre_completo;
                    document.getElementById('editCorreo').value = data.correo;
                })
                .catch(error => console.error('Error:', error));
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-yellow-400"><span class="text-blue-400">ITEE</span>-RESERVATIONS</a>
        <div class="space-x-4">
            <a href="../../Vista Principal/index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Vista Usuario</a>
            <a href="../php/cerrar_sesion.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="flex flex-1">
        <aside class="w-64 bg-gray-900 text-white p-4 min-h-screen">
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
                    <a href="cursos.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Cursos</a>
                </li>
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
        <main class="flex-1 p-6">
            <div class="container mx-auto">
                <h2 class="text-2xl font-semibold mb-4">Gestión de Usuarios</h2>
                <a href="register.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4 inline-block">Crear Usuario</a>
                <input class="form-input mb-4" type="text" id="buscador" onkeyup="buscarUsuario()" placeholder="Buscar usuario por nombre o correo">

                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md" id="tablaUsuarios">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2 border-b">Nombre</th>
                            <th class="px-4 py-2 border-b">Correo</th>
                            <th class="px-4 py-2 border-b">Rol</th>
                            <th class="px-4 py-2 border-b">Status</th>
                            <th class="px-4 py-2 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($usuario = mysqli_fetch_assoc($usuarios)): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($usuario['correo']) ?></td>
                            <td class="px-4 py-2"><?= $usuario['role'] == 1 ? 'Administrador' : 'Consultor' ?></td>
                            <td class="px-4 py-2"><?= $usuario['status'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                            <td class="px-4 py-2">
                                <?php if ($usuario['status'] == 1): ?>
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-2xl" onclick="confirmarDesactivacion(<?= $usuario['id'] ?>)">Desactivar</button>
                                <?php else: ?>
                                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-2xl" onclick="confirmarDesactivacion(<?= $usuario['id'] ?>)">Activar</button>
                                <?php endif; ?>
                                <button onclick="openEditModal(<?= $usuario['id'] ?>)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Editar</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full relative">
            <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="text-2xl font-semibold mb-4">Editar Usuario</h2>
            <form action="php/actualizar_usuario.php" method="POST">
                <input type="hidden" id="editId" name="ID">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control border border-gray-600 rounded-lg" id="editNombre" name="nombre_completo" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control border border-gray-600 rounded-lg" id="editCorreo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña (dejar en blanco si no deseas cambiarla)</label>
                    <input type="password" class="form-control border border-gray-600 rounded-lg" id="contrasena" name="contrasena">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Guardar cambios</button>
                <a href="modificar.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>
