<?php
include 'conexion_be.php';

// Establecer cabecera para devolver JSON
header('Content-Type: application/json');

// Decodificar los datos recibidos
$data = json_decode(file_get_contents('php://input'), true);

// Validar que los datos existan
if (!isset($data['id'], $data['title'], $data['date'], $data['time'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

$id = $data['id'];
$title = $data['title'];
$date = $data['date'];
$time = $data['time'];

// Verificar que los campos no estén vacíos
if (empty($id) || empty($title) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    exit();
}

// Validar formato de fecha (Y-m-d) y hora (H:i:s)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
    echo json_encode(['success' => false, 'message' => 'Formato de fecha u hora incorrecto']);
    exit();
}

// Preparar la consulta SQL de manera segura (evitar inyección SQL)
$query = "UPDATE reservations SET name = ?, datetime = ? WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);

if ($stmt) {
    $datetime = "$date $time";  // Combinar la fecha y hora
    mysqli_stmt_bind_param($stmt, 'ssi', $title, $datetime, $id);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Reserva actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la actualización de la reserva']);
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . mysqli_error($conexion)]);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
