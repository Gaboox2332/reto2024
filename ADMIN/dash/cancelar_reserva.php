<?php
session_start();
include 'php/conexion_be2.php'; // Asegúrate de incluir tu conexión a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Asegúrate de que el autoload de Composer esté incluido

if (!isset($_SESSION['usuario'])) {
    echo '<script>
    alert("Debes iniciar sesión para acceder a esta acción.");
    window.location = "../index.php";
    </script>';
    exit();
}

// Verificar si se ha enviado el ID de la reserva
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Primero, obtén los detalles de la reserva que se va a cancelar
    $query = "SELECT * FROM reservations WHERE id = $id LIMIT 1";
    $result = mysqli_query($conexion2, $query);
    $reserva = mysqli_fetch_assoc($result);

    if ($reserva) {
        // Almacena la información necesaria
        $nombre = $reserva['name'];
        $email = $reserva['email'];

        // Eliminar la reserva de la base de datos
        $delete_query = "DELETE FROM reservations WHERE id = $id";
        if (mysqli_query($conexion2, $delete_query)) {
            // Si la reserva se elimina, envía el correo electrónico
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'iteereservations@gmail.com'; // Tu correo SMTP
                $mail->Password = 'hgox jfgc ztcl xlhi'; // Tu contraseña SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Remitente y destinatario
                $mail->setFrom('iteereservations@gmail.com', 'ITEE-RESERVATIONS');
                $mail->addAddress($email, $nombre);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Cancelacion de Reserva';
                $mail->Body = "Hola $nombre,<br><br>Tu reserva ha sido cancelada por motivos de fuerza mayor o coordinación técnica.<br><br>Gracias por tu comprensión.";

                // Enviar el correo
                $mail->send();
                echo '<script>alert("Reserva cancelada y notificación enviada."); window.location = "index.php";</script>';
            } catch (Exception $e) {
                echo '<script>alert("La reserva fue cancelada, pero no se pudo enviar el correo: ' . $mail->ErrorInfo . '"); window.location = "index.php";</script>';
            }
        } else {
            echo '<script>alert("Error al cancelar la reserva."); window.location = "index.php";</script>';
        }
    } else {
        echo '<script>alert("Reserva no encontrada."); window.location = "index.php";</script>';
    }
} else {
    echo '<script>alert("ID de reserva no proporcionado."); window.location = "index.php";</script>';
}
?>
