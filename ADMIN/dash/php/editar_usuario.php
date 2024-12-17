<?php
session_start();
include 'conexion_be.php';

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); 
    window.location = "../index.php";</script>';
    exit();
}

// Obtener el ID del usuario a editar
$id = $_GET['id'];

// Obtener los datos actuales del usuario
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = $id");
$usuario = mysqli_fetch_assoc($resultado);

if (!$usuario) {
    echo '<script>alert("Usuario no encontrado."); window.location = "usuarios.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<!-- Modal Background -->
<div id="myModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
  <!-- Modal Container -->
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-xl font-semibold">Selecciona un horario</h2>
        <button id="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          &times;
        </button>
      </div>
      <!-- Modal Body -->
      <div id="modalContent">
        <!-- Aquí va el contenido dinámico del modal -->
         
      </div>
      <!-- Modal Footer -->
      <div class="flex justify-end pt-4 border-t mt-4">
        <button id="confirmBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
          Confirmar
        </button>
        <button id="cancelBtn" class="bg-red-500 text-white px-4 py-2 rounded ml-2 hover:bg-red-600 focus:outline-none">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</div>
    
<div class="container">
        <h2 class="my-4">Editar Usuario</h2>
        <form action="actualizar_usuario.php" method="POST">
            <input type="hidden" name="ID" value="<?= $usuario['ID'] ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre_completo" value="<?= $usuario['nombre_completo'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= $usuario['correo'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña (dejar en blanco si no deseas cambiarla)</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena">
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>
</html>
