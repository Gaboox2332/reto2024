<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Verificar si se ha recibido el ID del horario a eliminar
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Validar que el ID es un número positivo
    if ($id > 0) {
        // Preparar la consulta para eliminar el horario
        $query = "DELETE FROM available_times WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
        }

        // Vincular el parámetro y ejecutar la consulta
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Redirigir a la página principal de administración de horarios con mensaje de éxito
            header('Location: ../horarios.php?msg=success&action=delete');
        } else {
            // Redirigir a la página principal de administración de horarios con mensaje de error
            header('Location: ../horarios.php?msg=error&action=delete');
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
    } else {
        // Redirigir a la página principal de administración de horarios con mensaje de error
        header('Location: ../index.php?msg=invalid_id&action=delete');
    }

    mysqli_close($conexion);
} else {
    // Redirigir a la página principal de administración de horarios con mensaje de error
    header('Location: ../index.php?msg=no_id&action=delete');
}
exit;
