<?php
session_start();
include 'php/conexion_be2.php';

// Verificar sesión y rol
if (!isset($_SESSION['usuario'])) {
    echo '<script>alert("Debes iniciar sesión para acceder a esta página."); window.location = "../index.php";</script>';
    exit();
}

// Obtener el rol del usuario desde la sesión
$rol_usuario = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";

// Obtener datos para la tabla
$query = "SELECT * FROM reservations"; // Ajusta la consulta según tu estructura de base de datos
$result = mysqli_query($conexion2, $query);
$reservas = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Estilos para ocultar filas de la tabla */
        .hidden {
            display: none;
        }
        body {
            padding-top: 0px; /* Espacio para el navbar */
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 text-warning" href="index.php"><span class="text-primary">ITEE-</span>RESERVATIONS</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <div class="text-right mb-2 d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <a href="./assets/fpdf/PruebaH.php" class="btn btn-success"><i class="fa-regular fa-file-pdf"></i> Generar Reportes</a>
        </div>
        <div class="">
            <a href="../../Vista Principal/index.php" class="btn btn-primary"><i class="fa-solid fa-desktop"></i> Vista Usuario</a>
        </div>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-address-card"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../php/cerrar_sesion.php">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Centro Principal</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Panel de Control
                        </a>
                        <div class="sb-sidenav-menu-heading">Administracion</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Administrar Usuarios
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="register.php">Registrar Usuario</a>
                                <a class="nav-link" href="modificar.php">Modificar Usuario</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Estadisticas</div>
                        <a class="nav-link" href="charts.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Tipo Graficas
                        </a>
                        <a class="nav-link" href="tables.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tipo Tablas
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Sesión Iniciada Como:</div>
                    <?= htmlspecialchars($nombre_rol) ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <h2 class="mb-4">Tabla de Reservas</h2>
                    
                    <!-- Filtros -->
                    <div class="d-flex justify-content-between mb-4">
                        <!-- Buscador -->
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, correo o sala" style="width: 70%;">
                        <!-- Filtro por fecha -->
                        <input type="date" id="dateFilter" class="form-control" style="width: 25%;">
                    </div>

                    <!-- Tabla de Reservas -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Sala</th>
                                <th>Fecha y Hora</th>
                                <th>Grado</th>
                            </tr>
                        </thead>
                        <tbody id="reservasTableBody">
                            <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['id']) ?></td>
                                <td><?= htmlspecialchars($reserva['name']) ?></td>
                                <td><?= htmlspecialchars($reserva['email']) ?></td>
                                <td><?= htmlspecialchars($reserva['room']) ?></td>
                                <td><?= htmlspecialchars($reserva['datetime']) ?></td>
                                <td><?= htmlspecialchars($reserva['grade']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#reservasTableBody tr');

            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                var found = Array.from(cells).some(function(cell) {
                    return cell.textContent.toLowerCase().includes(filter);
                });
                row.classList.toggle('hidden', !found);
            });
        });

        document.getElementById('dateFilter').addEventListener('input', function() {
            var selectedDate = this.value;
            var rows = document.querySelectorAll('#reservasTableBody tr');

            rows.forEach(function(row) {
                var cellDate = row.cells[4].textContent; // Celda de "Fecha y Hora"
                var rowDate = cellDate.split(' ')[0]; // Obtener solo la fecha

                var matchesDate = selectedDate === '' || rowDate === selectedDate;
                row.classList.toggle('hidden', !matchesDate);
            });
        });
    </script>
</body>
</html>
