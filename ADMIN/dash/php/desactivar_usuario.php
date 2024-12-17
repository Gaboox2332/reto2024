<?php
session_start();
include 'conexion_be.php';

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); 
    window.location = "../index.php";</script>';
    exit();
}

// Verificar si se ha pasado el ID del usuario
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Cambiar el estado del usuario a inactivo (status = 0)
    $query = "UPDATE usuarios SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo '<script>alert("El estado del usuario ha sido actualizado."); window.location = "../modificar.php";</script>';
    } else {
        echo '<script>alert("Error al actualizar el estado del usuario."); window.location = "../modificar.php";</script>';
    }
} else {
    echo '<script>alert("ID de usuario no proporcionado."); window.location = "../modificar.php";</script>';
}
?>
