<?php
include 'conexion_be.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT id, nombre_completo, correo FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        echo json_encode($usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
}
?>
