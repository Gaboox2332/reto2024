<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Reto2024\ITEE\vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $name = $_POST['name']; // Nombre del usuario
    $email = $_POST['email']; // Correo del usuario
    $room = $_POST['room']; // Sala reservada
    $datetime = $_POST['datetime']; // Fecha y hora de la reserva
    $grade = $_POST['course'];

    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'reservations_db');

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Evitar inyección SQL
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $room = $conn->real_escape_string($room);
    $datetime = $conn->real_escape_string($datetime);

    // Insertar reserva en la base de datos
    $sql = "INSERT INTO reservations (name, email, room, datetime, grade, status) VALUES ('$name', '$email', '$room', '$datetime', '$grade', '1')";

    if ($conn->query($sql) === TRUE) {
        // Enviar correo de confirmación
        $subject = "Confirmacion de Reserva";
        $body = "
            <h2>Reserva Confirmada</h2>
            <p>Estimado/a $name,</p>
            <p>Su reserva ha sido confirmada con éxito para la sala <strong>$room</strong> el <strong>$datetime</strong>.</p>
            <p>Si tiene alguna pregunta, no dude en contactar a la oficina de Coordinación Técnica.</p>
            <p>Gracias por utilizar nuestro sistema de reservas.</p>
            <p>Saludos,<br>Equipo de Reservas</p>
        ";

        // Función para enviar el correo
        enviarCorreo($email, $subject, $body);

        // Redirigir a la página de éxito
        header("Location: success-page.php");
        exit();
    } else {
        header("Location: ../index.php?error=Error al guardar la reserva: " . $conn->error);
        exit();
    }

    $conn->close();
}

function enviarCorreo($email_to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'iteereservations@gmail.com';  
        $mail->Password = 'hgox jfgc ztcl xlhi';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('iteereservations@gmail.com', 'ITEE-RESERVATIONS');
        $mail->addAddress($email_to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
?>
