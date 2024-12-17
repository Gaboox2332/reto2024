<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['ID'];
    $hora_inicio = $_POST['hora_inicio'];

    // Validar los datos (opcional, pero recomendado)
    if (empty($id) || empty($hora_inicio)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Preparar la consulta SQL para actualizar el horario
    $query = "UPDATE available_times SET time = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
    }

    // Vincular los parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'si', $hora_inicio, $id);
    $result = mysqli_stmt_execute($stmt);

    // Verificar si la ejecución fue exitosa
    if ($result) {
        // Redirigir a la página de administración de horarios con un mensaje de éxito
        header('Location: ../horarios.php?mensaje=Horario actualizado exitosamente');
    } else {
        echo 'Error al actualizar el horario: ' . mysqli_error($conexion);
    }

    // Cerrar la declaración y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} else {
    // Si no se ha enviado el formulario, redirigir de vuelta
    header('Location: ../horarios.php');
}
?>
