<?php
session_start();

// Authentication check
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

include 'php/conexion_be2.php';

// Pagination variables
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get data with pagination and search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conexion2, $_GET['search']) : '';
$filter_date = isset($_GET['filter_date']) ? mysqli_real_escape_string($conexion2, $_GET['filter_date']) : '';
$filter_room = isset($_GET['filter_room']) ? mysqli_real_escape_string($conexion2, $_GET['filter_room']) : '';

$query = "SELECT * FROM reservations WHERE 1=1";

if ($search) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($filter_date) {
    $query .= " AND DATE(datetime) = '$filter_date'";
}

if ($filter_room) {
    $query .= " AND room = '$filter_room'";
}

$query .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($conexion2, $query);
$reservas = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get total records
$total_query = "SELECT COUNT(*) AS total FROM reservations WHERE 1=1";

if ($search) {
    $total_query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($filter_date) {
    $total_query .= " AND DATE(datetime) = '$filter_date'";
}

if ($filter_room) {
    $total_query .= " AND room = '$filter_room'";
}

$total_result = mysqli_query($conexion2, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_reservas = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_reservas / $limit);

// Get today's reservations
$now = date('Y-m-d H:i:s');
$query_reservas_hoy = "SELECT * FROM reservations WHERE DATE(datetime) = CURDATE()";
$result_reservas_hoy = mysqli_query($conexion2, $query_reservas_hoy);
$reservas_hoy = mysqli_fetch_all($result_reservas_hoy, MYSQLI_ASSOC);

// Determine user role
$rol_usuario = $_SESSION['role'];
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista ADMIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.4/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }
        body {
            padding-top: 60px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                transition: 0.3s;
            }
            .sidebar.active {
                left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-yellow-400"><span class="text-blue-400">ITEE</span>-RESERVATIONS</a>
        <button id="menuButton" class="md:hidden focus:outline-none">☰</button>
        <div class="space-x-2 md:space-x-4 hidden md:flex">
            <a href="../../USUARIOS/index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 md:px-4 md:py-2 rounded text-sm md:text-base">Vista Usuario</a>
            <a href="../php/cerrar_sesion.php" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 md:px-4 md:py-2 rounded text-sm md:text-base">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="flex flex-1 flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="sidebar w-full md:w-64 bg-gray-900 text-white p-4 md:fixed md:h-screen md:top-15 md:left-0">
            <button id="sidebarToggle" class="md:hidden mb-4 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded">Toggle Sidebar</button>
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
            <div class="mt-4 text-gray-500 text-sm">
                Sesión Iniciada Como:<br>
                <?= htmlspecialchars($nombre_rol) ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 md:ml-64">
            <h1 class="text-2xl md:text-3xl font-bold mb-4">Panel de Control</h1>

            <!-- Cards para las reservas más cercanas -->
            <section class="mb-6">
    <h2 class="text-xl md:text-2xl font-semibold mb-4">Reservas de Hoy (<?php echo date('d/m/Y'); ?>)</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if (count($reservas_hoy) > 0): ?>
            <?php foreach ($reservas_hoy as $reserva): ?>
                <?php if (!$reserva['is_confirmed']): // Solo mostrar reservas no confirmadas ?>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-md">
                        <h3 class="text-lg md:text-xl font-semibold mb-2">Sala: <?php echo htmlspecialchars($reserva['room']); ?></h3>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($reserva['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email']); ?></p>
                        <p><strong>Hora de Reserva:</strong> <?php echo date('H:i', strtotime($reserva['datetime'])); ?></p>
                        <button onclick="confirmReservation(<?php echo $reserva['id']; ?>)" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Confirmada</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-blue-100 p-4 rounded-lg shadow-md col-span-1 sm:col-span-2 lg:col-span-3">
                <p>No hay reservas para hoy.</p>
            </div>
        <?php endif; ?>
    </div>
</section>


            <!-- Tabla con la paginación -->
            <section>
                <h2 class="text-xl md:text-2xl font-semibold mb-4">Reservas</h2>
                
                <!-- Buscador -->
                <!-- Filtros adicionales -->
            <div class="mb-4 flex items-center">
                <input type="text" name="search" id="search" placeholder="Buscar por nombre o email" class="p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($search); ?>">

                <!-- Filtro por fecha -->
                <input type="date" name="filter_date" id="filter_date" class="p-2 border border-gray-300 rounded ml-2" value="<?php echo isset($_GET['filter_date']) ? htmlspecialchars($_GET['filter_date']) : ''; ?>">
                
                <!-- Filtro por sala -->
                <select name="filter_room" id="filter_room" class="p-2 border border-gray-300 rounded ml-2">
                    <option value="">Todas las salas</option>
                    <option value="1" <?php if (isset($_GET['filter_room']) && $_GET['filter_room'] == 'Sala 1') echo 'selected'; ?>>Sala 1</option>
                    <option value="2" <?php if (isset($_GET['filter_room']) && $_GET['filter_room'] == 'Sala 2') echo 'selected'; ?>>Sala 2</option>
                    <option value="3" <?php if (isset($_GET['filter_room']) && $_GET['filter_room'] == 'Sala 2') echo 'selected'; ?>>Sala 3</option>
                    <option value="4" <?php if (isset($_GET['filter_room']) && $_GET['filter_room'] == 'Sala 2') echo 'selected'; ?>>Sala 4</option>
                    <!-- Agrega más salas según sea necesario -->
                </select>

                <button onclick="searchReservations()" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Buscar</button>
                    <a href="./assets/fpdf/PruebaH.php?search=<?php echo urlencode($search); ?>&limit=<?php echo $limit; ?>" class="ml-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Generar Reportes</a>
                </div>

                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">

                            <th class="py-2 px-4 border-b">Nombre</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Sala</th>
                            <th class="py-2 px-4 border-b">Fecha y Hora</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr class="text-gray-700">

                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($reserva['name']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($reserva['email']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($reserva['room']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo date('d/m/Y H:i', strtotime($reserva['datetime'])); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="cancelar_reserva.php?id=<?php echo htmlspecialchars($reserva['id']); ?>" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Cancelar Reserva</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="flex justify-between items-center mt-4">
                    <div>
                        <span>Página <?php echo $page; ?> de <?php echo $total_pages; ?></span>
                    </div>
                    <div>
                        <label for="limit">Mostrar:</label>
                        <select id="limit" class="border border-gray-300 rounded" onchange="changeLimit()">
                            <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                            <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                        </select>
                    </div>
                    <div>
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Anterior</a>
                        <?php endif; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Siguiente</a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function changeLimit() {
            const limit = document.getElementById('limit').value;
            window.location.href = `?page=1&limit=${limit}&search=${encodeURIComponent('<?php echo $search; ?>')}`;
        }

        document.getElementById('sidebarToggle').onclick = function() {
            document.querySelector('.sidebar').classList.toggle('active');
        };

        document.getElementById('menuButton').onclick = function() {
            document.querySelector('.sidebar').classList.toggle('active');
        };

        function confirmReservation(id) {
    if (confirm("¿Estás seguro de que deseas confirmar esta reserva?")) {
        fetch(`confirm_reservation.php?id=${id}`)
            .then(response => {
                if (response.ok) {
                    location.reload(); // Recargar la página para actualizar las reservas
                } else {
                    alert("Error al confirmar la reserva. Por favor, inténtalo de nuevo.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar confirmar la reserva.");
            });
    }
}

    </script>

    <script>
        // Para manejar la funcionalidad del menú en pantallas pequeñas
        document.getElementById('menuButton').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('active');
        });

        // Para la funcionalidad de búsqueda con filtros
        function searchReservations() {
            const search = document.getElementById('search').value;
            const filterDate = document.getElementById('filter_date').value;
            const filterRoom = document.getElementById('filter_room').value;

            let url = `?search=${encodeURIComponent(search)}&limit=${<?php echo $limit; ?>}`;
            
            if (filterDate) {
                url += `&filter_date=${encodeURIComponent(filterDate)}`;
            }
            
            if (filterRoom) {
                url += `&filter_room=${encodeURIComponent(filterRoom)}`;
            }

            window.location.href = url;
        }

    </script>
</body>
</html>