<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservación de Salas</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- FullCalendar CSS -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
  <style>
    /* Personaliza el estilo general */
    body {
      font-family: 'Arial', sans-serif;
      background-image: url("assets/images/Sala1.jpg");
      background-size: cover;
      background-position: center;
      backdrop-filter: blur(10px);
    }
    #calendar {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 1 40px 50px rgba(0, 0, 0, 0.1);
      max-width: 600px; /* Limita el ancho del calendario */
      margin: auto; /* Centra el calendario */
    }
    .fc-toolbar {
      margin-bottom: 20px;
    }
    .fc-button {
      background-color: #1d4ed8;
      border: none;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
    }
    .fc-button:hover {
      background-color: #2563eb;
    }
    .fc-dayGridMonth-button {
      text-transform: capitalize;
    }
    .fc-header-toolbar .fc-toolbar-title {
      text-transform: capitalize;
    }
    .fc-daygrid-month-view .fc-toolbar-title::after {
      content: ''; /* Quita el año */
    }
    .fc-toolbar-title {
      font-size: 1.5rem;
    }
    .fc-day-header {
      text-transform: uppercase;
    }
  </style>
</head>
<body class="bg-gray-100">
  <!-- Navbar -->
  <nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 sm:px-6 py-4 flex justify-between items-center">
      <a href="#" class="text-2xl font-bold text-blue-600">ITEE<span class="text-yellow-600">-RESERVATIONS</span></a>
      <button id="menuToggle" class="sm:hidden p-2 text-blue-600 focus:outline-none">
        <!-- Icono de menú -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
      </button>
      <div id="menu" class="hidden sm:flex space-x-2">
        <a href="../ADMIN/index.php" class="text-lg text-blue-600 hover:underline font-bold">ADMIN</a>
        <a href="index.php" class="text-lg text-blue-600 hover:underline font-bold">CANCELAR</a>
      </div>
    </div>
    <!-- Menu desplegable -->
    <div id="dropdownMenu" class="hidden sm:hidden bg-white shadow-md">
      <a href="../ADMIN/index.php" class="block px-4 py-2 text-blue-600 hover:bg-gray-200">ADMIN</a>
      <a href="index.php" class="block px-4 py-2 text-blue-600 hover:bg-gray-200">CANCELAR</a>
    </div>
  </nav>

  <!-- Header -->
  <header class="bg-gradient-to-r from-blue-500 to-gray-500 text-white py-8">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl sm:text-5xl font-bold">Selecciona la Fecha y Hora</h2>
      <p class="mt-4 text-lg">Elige la fecha y hora que prefieras para reservar tu sala de innovación.</p>
    </div>
  </header>

  <!-- Calendar Section -->
  <section class="container mx-auto my-10 px-4 sm:px-6">
    <div id="calendar" class="shadow-md rounded-lg"></div>
  </section>
  
  <!-- Footer -->
  <footer class="bg-white shadow-md mt-10">
        <div class="container mx-auto px-6 py-4 text-center">
            <p class="text-gray-600">&copy; 2024 ITEE Reservations. Todos los derechos reservados.</p>
        </div>
    </footer>

  <!-- Modal para selección de hora -->
  <div id="timeModal" class="fixed z-50 inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 sm:p-10 rounded-lg shadow-lg max-w-xl w-full mx-auto">
      <h3 class="text-xl font-bold mb-5">Seleccionar Hora</h3>
      <form id="timeForm">
        <div class="form-group mb-4">
          <label for="selectedTime" class="block mb-2">Hora:</label>
          <select class="form-control w-full p-2 border border-gray-300 rounded-lg" id="selectedTime" name="selectedTime" required>
            <!-- Opciones de tiempo serán insertadas aquí por JavaScript -->
          </select>
        </div>
        <input type="hidden" id="selectedDate" name="selectedDate">
        <input type="hidden" id="selectedRoom" name="selectedRoom">
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Confirmar</button>
      </form>
      <button id="cerrarModal" class="mt-4 text-red-500 hover:text-red-700">Cancelar</button>
    </div>
  </div>

  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var selectedDate = ''; // Variable para almacenar la fecha seleccionada
      var selectedRoom = new URLSearchParams(window.location.search).get('sala'); // Obtener la sala de la URL

      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        selectable: true,
        editable: false,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth'
        },
        validRange: {
          start: new Date().toISOString().slice(0, 10)
        },
        hiddenDays: [0, 6],
        dateClick: function(info) {
          selectedDate = info.dateStr; // Guardar la fecha seleccionada
          document.getElementById('selectedDate').value = selectedDate;
          document.getElementById('selectedRoom').value = selectedRoom;
          fetchAvailableTimes(selectedDate, selectedRoom); // Obtener horarios disponibles
        }
      });

      function fetchAvailableTimes(date, room) {
        fetch('php/available-times.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ date: date, room: room })
        })
        .then(response => response.text()) // Primero obtener como texto para depuración
        .then(text => {
          console.log('Respuesta del servidor:', text); // Verifica el contenido
          return JSON.parse(text); // Intenta parsear el JSON
        })
        .then(data => {
          var timeSelect = document.getElementById('selectedTime');
          timeSelect.innerHTML = ''; // Limpiar opciones anteriores
          data.forEach(time => {
            var option = document.createElement('option');
            option.value = time;
            option.textContent = time;
            timeSelect.appendChild(option);
          });
          document.getElementById('timeModal').classList.remove('hidden');
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al obtener los horarios disponibles. Por favor, inténtelo de nuevo más tarde.');
        });
      }

      document.getElementById('timeForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        var selectedDate = document.getElementById('selectedDate').value;
        var selectedTime = document.getElementById('selectedTime').value;
        var room = document.getElementById('selectedRoom').value;

        if (selectedTime) {
          var datetime = `${selectedDate}T${selectedTime}`;
          window.location.href = `php/reservation-form.php?datetime=${encodeURIComponent(datetime)}&room=${encodeURIComponent(room)}`;
        }
      });

      document.getElementById('cerrarModal').addEventListener('click', function() {
        document.getElementById('timeModal').classList.add('hidden');
      });

      // Manejo del menú desplegable
      document.getElementById('menuToggle').addEventListener('click', function() {
        var dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
      });

      calendar.render();
    });
  </script>
</body>
</html>
