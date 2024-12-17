<?php 
session_start();
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

// Obtener el rol del usuario desde la sesión
$rol_usuario = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$nombre_rol = ($rol_usuario == 2) ? "Consultor" : "Administrador";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Reservas - Administrador</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.4/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #calendar {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
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
    </style>
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
            <h2 class="text-3xl font-bold mb-4">Calendario de Reservas</h2>
            <!-- Contenedor del Calendario -->
            <div id="calendar" class="bg-white rounded-lg shadow-md p-4">
            </div>
        </main>
    </div>

    <!-- Modal para Editar Reserva -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Editar Reserva</h3>
            <form id="editForm">
                <input type="hidden" id="eventId" name="eventId">
                <div class="mb-4">
                    <label for="eventTitle" class="block text-gray-700">Título:</label>
                    <input type="text" id="eventTitle" name="eventTitle" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="eventDate" class="block text-gray-700">Fecha:</label>
                    <input type="text" id="eventDate" name="eventDate" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div class="mb-4">
                    <label for="eventTime" class="block text-gray-700">Hora:</label>
                    <select id="eventTime" name="eventTime" class="w-full p-2 border border-gray-300 rounded">
                        <option value="8:00:00">8:00AM</option>
                        <option value="9:00:00">9:00AM</option>
                        <option value="10:00:00">10:00AM</option>
                        <option value="11:00:00">11:00AM</option>
                        <option value="12:00:00">12:00PM</option>
                        <option value="13:00:00">1:00PM</option>
                        <option value="14:00:00">2:00PM</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">Cerrar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var modal = document.getElementById('editModal');
        var closeModalBtn = document.getElementById('closeModal');
        var eventIdInput = document.getElementById('eventId');
        var eventTitleInput = document.getElementById('eventTitle');
        var eventDateInput = document.getElementById('eventDate');
        var eventTimeInput = document.getElementById('eventTime');
        var editForm = document.getElementById('editForm');
        
        // Configurar Flatpickr para la selección de fecha
        flatpickr(eventDateInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr) {
                fetchAvailableTimes(dateStr); 
            }
        });

        // Inicializar el calendario de FullCalendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            hiddenDays: [0, 6], 
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch('php/fetch_reservations.php')
                    .then(response => response.json())
                    .then(data => {
                        successCallback(data);
                    });
            },
            editable: true,
            eventClick: function(info) {
                // Obtener detalles de la reserva y mostrarlos en el modal
                eventIdInput.value = info.event.id;
                eventTitleInput.value = info.event.title;
                eventDateInput.value = info.event.start.toISOString().split('T')[0]; 
                eventTimeInput.value = info.event.start.toTimeString().split(' ')[0]; 
                modal.classList.remove('hidden');
            }
        });
        calendar.render();

        // Cerrar el modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Enviar el formulario para actualizar la reserva
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var eventId = eventIdInput.value;
            var eventTitle = eventTitleInput.value;
            var eventDate = eventDateInput.value;
            var eventTime = eventTimeInput.value;

            if (!eventId || !eventTitle || !eventDate || !eventTime) {
                alert('Por favor, completa todos los campos.');
                return;
            }

            // Realizar la petición para actualizar el evento
            fetch('php/update_reservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: eventId,
                    title: eventTitle,
                    date: eventDate,
                    time: eventTime
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reserva actualizada correctamente');
                    modal.classList.add('hidden');
                    calendar.refetchEvents();
                } else {
                    alert('Error al actualizar la reserva');
                }
            })
            .catch(error => {
                alert('Ocurrió un error: ' + error);
            });
        });

        // Función para obtener horarios disponibles
        function fetchAvailableTimes(date) {
            fetch('php/fetch_available_times.php?date=' + date)
                .then(response => response.json())
                .then(data => {
                    eventTimeInput.innerHTML = '';
                    data.forEach(function(time) {
                        var option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        eventTimeInput.appendChild(option);
                    });
                });
        }
    });
    </script>
</body>
</html>
