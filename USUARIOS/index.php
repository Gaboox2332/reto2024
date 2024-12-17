<?php
// Verificar si hay mensajes
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de Salas de Innovación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-blue-600">ITEE<span class="text-2xl font-bold text-yellow-600">-RESERVATIONS</span></a>
            <div class="ml-auto flex space-x-4">
                <a href="../ADMIN/index.php" class="text-xl text-blue-600 hover:underline font-bold">ADMIN</a>
            </div>
        </div>
    </nav>


    <!-- Mensajes de éxito o error -->
    <div class="container mx-auto px-6 my-4">
        <?php if ($success): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">¡Reserva realizada con éxito!</p>
                <p>Por favor, acude a la oficina del Ing. Freddy Estrada para solicitar tu pase de entrada.</p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Lo sentimos</p>
                <p>El horario seleccionado ya está reservado. Intenta con otro horario.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-teal-500 text-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-6xl font-bold">Reserva tu Sala de Innovación</h2>
            <p class="mt-4 text-xl">Elige entre nuestras modernas salas para tus necesidades creativas.</p>
        </div>
    </header>

    <!-- Lista de Salas -->
    <section class="container mx-auto my-10 px-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6"></h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sala 1 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="assets/images/Sala2.JPG" alt="Sala 1" class="w-full h-48 object-cover rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Sala de Innovación 1</h3>
                <p class="text-gray-600 mt-2">Espacio dedicado para trabajo colaborativo. <br><b>Duración: 1hr</b></p>
                <a href="calendario.php?sala=1" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 block text-center">Seleccionar</a>
            </div>
            <!-- Sala 2 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="assets/images/Sala3.JPG" alt="Sala 2" class="w-full h-48 object-cover rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Sala de Innovación 2</h3>
                <p class="text-gray-600 mt-2">Espacio dedicado para trabajo colaborativo. <br><b>Duración: 1hr</b></p>
                <a href="calendario.php?sala=2" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 block text-center">Seleccionar</a>
            </div>
            <!-- Sala 3 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="assets/images/Sala2.JPG" alt="Sala 3" class="w-full h-48 object-cover rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Sala de Innovación 3</h3>
                <p class="text-gray-600 mt-2">Espacio dedicado para trabajo colaborativo. <br><b>Duración: 1hr</b></p>
                <a href="calendario.php?sala=3" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 block text-center">Seleccionar</a>
            </div>
            <!-- Sala 4 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="assets/images/Sala1.JPG" alt="Sala 4" class="w-full h-48 object-cover rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mt-4">Sala de Innovación 4</h3>
                <p class="text-gray-600 mt-2">Espacio dedicado para trabajo colaborativo. <br><b>Duración: 1hr</b></p>
                <a href="calendario.php?sala=4" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 block text-center">Seleccionar</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-10">
        <div class="container mx-auto px-6 py-4 text-center">
            <p class="text-gray-600">&copy; 2024 ITEE Reservations. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
