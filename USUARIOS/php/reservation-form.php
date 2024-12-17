<?php
  $datetime = isset($_GET['datetime']) ? $_GET['datetime'] : '';
  $room = isset($_GET['room']) ? $_GET['room'] : '';

  $conn = new mysqli('localhost', 'root', '', 'reservations_db');
  $sql = "SELECT id, nombre_curso FROM cursos";
  $resultado = $conn->query($sql);

  $cursos = [];
  if ($resultado->num_rows > 0) {
      while ($row = $resultado->fetch_assoc()) {
          $cursos[] = $row;
      }
  }
  $conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reservación</title>
    <script src="https://cdn.tailwindcss.com"></script>



</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="../Vista Principal/index.php" class="text-2xl font-bold text-blue-600">ITEE<span class="text-2xl font-bold text-yellow-600">-RESERVATIONS</span></a>
            <div class="ml-auto flex items-center">
                <button id="menuButton" class="md:hidden text-gray-600 focus:outline-none">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <div id="menu" class="hidden md:flex space-x-4">
                    <a href="#" class="text-xl text-blue-600 hover:underline font-bold">INICIO</a>
                    <a href="#" class="text-xl text-blue-600 hover:underline font-bold">ADMIN</a>
                </div>
            </div>
        </div>
    </nav>

    <div id="mobileMenu" class="hidden md:hidden bg-white shadow-md">
        <div class="flex flex-col p-4">
            <a href="#" class="text-xl text-blue-600 hover:underline font-bold">INICIO</a>
            <a href="#" class="text-xl text-blue-600 hover:underline font-bold">ADMIN</a>
        </div>
    </div>

    <!-- Formulario de Reservación -->
    <div class="container mx-auto px-6 py-10">
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Formulario de Confirmacion</h1>
            <form id="reservationForm" action="save-reservation.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre:</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Correo Electrónico Institucional:</label>
                    <input type="email" id="email" name="email" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        pattern="^[a-zA-Z0-9._%+-]+@iteesa\.edu\.hn$" 
                        required 
                        title="El correo electrónico debe terminar en @iteesa.edu.hn">
                    <small class="block text-gray-500">Debe terminar en @iteesa.edu.hn</small>
                </div>
                <div class="mb-4">
                    <label for="course" class="block text-gray-700 font-semibold mb-2">Curso al que pertenece:</label>
                    <select id="course" name="course" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione un curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?php echo htmlspecialchars($curso['nombre_curso']); ?>">
                                <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Campos ocultos -->
                <input type="hidden" id="datetime" name="datetime" value="<?php echo htmlspecialchars($datetime); ?>">
                <input type="hidden" id="room" name="room" value="<?php echo htmlspecialchars($room); ?>">

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 font-semibold">Reservar</button>
            </form>
            <button class="w-full bg-gray-500 text-white py-2 mt-4 rounded-lg hover:bg-gray-600" onclick ="window.location.href='../index.php'">Cancelar</button>

        </div>
    </div>

    <script>
        document.getElementById('reservationForm').addEventListener('submit', function(event) {
            var emailField = document.getElementById('email');
            var emailValue = emailField.value;
            var regex = /^[a-zA-Z0-9._%+-]+@iteesa\.edu\.hn$/;

            if (!regex.test(emailValue)) {
                alert('El correo electrónico debe terminar en @iteesa.edu.hn');
                event.preventDefault(); // Evita que el formulario se envíe
            }
        });

        document.getElementById('menuButton').addEventListener('click', function() {
            var menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
