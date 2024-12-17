<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Obtener el ID del horario desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar el horario en la base de datos
$query = "SELECT id, time FROM available_times WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . mysqli_error($conexion));
}

// Vincular el parámetro y ejecutar la consulta
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $time);
mysqli_stmt_fetch($stmt);

// Crear un array para la respuesta JSON
$response = array('id' => $id, 'time' => $time);

// Devolver la respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la declaración y la conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
