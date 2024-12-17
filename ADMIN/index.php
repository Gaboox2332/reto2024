<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("location: dash/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
        <div class="text-center mb-6">
            <a href="../Vista Principal/index.php" class="block mb-4">
                <img src="assets/images/P1w.png" width="500" alt="Logo">
            </a>
            <p class="text-2xl font-bold text-gray-800">ITEE-RESERVATIONS</p>
            <small class="block text-lg text-gray-500">Iniciar Sesion</small>
        </div>

        <form action="php/login_usuario_be.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="correo" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Correo Electrónico" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                <input type="password" id="password" name="contrasena" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contraseña" required>
            </div>

            <div class="mb-4 text-right">
                <a href="#" class="text-blue-600 hover:underline text-sm">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 font-semibold">Entrar</button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">¿No eres administrador?</p>
            <a href="../USUARIOS/index.php" class="text-blue-600 hover:underline font-semibold">Regresar a la página de reservas</a>
        </div>
    </div>

</body>
</html>
