<?php
session_start();
include 'conexion_be.php';

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$contrasena = hash('sha512', $contrasena);

// Consulta que obtiene al usuario con su correo y contraseña
$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena'");

if (mysqli_num_rows($validar_login) > 0) {
    $user_data = mysqli_fetch_assoc($validar_login);  // Obtener los datos del usuario
    
    // Asignar el correo del usuario a la sesión
    $_SESSION['role'] = $rol_usuario;
    $_SESSION['usuario'] = $correo;
    
    // Asignar el rol del usuario a la sesión
    $_SESSION['role'] = $user_data['role'];  // Asume que en la tabla 'usuarios' hay una columna 'role'

    $_SESSION['status'] = $user_data['status'];
    
    // Redirigir al dashboard de administrador
    header("location: ../../ADMIN/dash/index.php");
    exit;
} else {
    echo '
    <script> 
    alert("Usuario no existe, favor verificar los datos.");
    window.location = "../index.php";
    </script> 
    ';
    exit;
}
?>
