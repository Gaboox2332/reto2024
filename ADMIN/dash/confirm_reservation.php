<?php
session_start();
include 'php/conexion_be2.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No estás autenticado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Actualizar la reserva a confirmada
    $query = "UPDATE reservations SET is_confirmed = 1 WHERE id = ?";
    $stmt = $conexion2->prepare($query);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al confirmar la reserva.']);
    }
    
    $stmt->close();
}
?>
