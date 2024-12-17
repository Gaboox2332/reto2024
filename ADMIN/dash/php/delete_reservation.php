<?php
session_start();
include 'conexion_be2.php'; // Asegúrate de incluir tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id']);
    $email = $data['email'];

    // Eliminar la reserva
    $delete_query = "DELETE FROM reservations WHERE id = ?";
    $stmt = $conexion2->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Enviar el correo de notificación
        $to = $email;
        $subject = "Reserva Cancelada";
        $message = "Estimado usuario,\n\nSu reserva ha sido cancelada por fuerzas mayores o debido a la indisponibilidad en los horarios.\n\nGracias por su comprensión.";
        $headers = "From: noreply@tu-dominio.com";

        mail($to, $subject, $message, $headers);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la reserva.']);
    }

    $stmt->close();
    $conexion2->close();
}
?>
