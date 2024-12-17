<?php
session_start();
include 'conexion_be.php';

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); window.location = "../index.php";</script>';
    exit();
}

// Recibir y escapar las variables
$id = isset($_POST['ID']) ? mysqli_real_escape_string($conexion, $_POST['ID']) : '';
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$contrasena = mysqli_real_escape_string($conexion, $_POST['contrasena']);

// Depurar el valor del ID
// echo "ID: " . $id;
// exit();

// Validar que el id sea un número entero
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    echo '<script>alert("ID no válido."); window.location = "../modificar.php";</script>';
    exit();
}

// Si la contraseña está vacía, no la actualizamos
if (!empty($contrasena)) {
    $contrasena = hash('sha512', $contrasena);
    $actualizar = "UPDATE usuarios SET nombre_completo='$nombre', correo='$correo', contrasena='$contrasena' WHERE id = $id";
} else {
    $actualizar = "UPDATE usuarios SET nombre_completo='$nombre', correo='$correo' WHERE id = $id";
}

// Depurar la consulta SQL
// echo $actualizar;
// exit();

if (mysqli_query($conexion, $actualizar)) {
    echo '<script>alert("Usuario actualizado correctamente."); window.location = "../modificar.php";</script>';
} else {
    echo '<script>alert("Error al actualizar el usuario: ' . mysqli_error($conexion) . '"); window.location = "../modificar.php";</script>';
}
?>
