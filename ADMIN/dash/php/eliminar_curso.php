<?php
session_start();
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo '<script>
    alert("Debes iniciar sesión para acceder a esta página.");
    window.location = "../index.php";
    </script>';
    exit();
}

if ($_SESSION['status'] != 1) {
    echo '<script>
    alert("Este usuario está inactivo");
    window.location = "../index.php";
    </script>';
    session_destroy();
    exit();
}

// Verificar si se ha recibido el ID del curso a eliminar
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Validar que el ID es un número positivo
    if ($id > 0) {
        // Preparar la consulta para eliminar el curso
        $query = "DELETE FROM cursos WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);

        if ($stmt === false) {
            echo '<script>
            alert("Error en la preparación de la consulta: ' . mysqli_error($conexion) . '");
            window.location = "../cursos.php";
            </script>';
            exit();
        }

        // Vincular el parámetro y ejecutar la consulta
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo '<script>
            alert("Curso eliminado exitosamente.");
            window.location = "../cursos.php";
            </script>';
        } else {
            echo '<script>
            alert("Error al eliminar el curso: ' . mysqli_stmt_error($stmt) . '");
            window.location = "../cursos.php";
            </script>';
        }

        // Cerrar la declaración y la conexión
        mysqli_stmt_close($stmt);
    } else {
        echo '<script>
        alert("ID de curso inválido.");
        window.location = "../cursos.php";
        </script>';
    }

    mysqli_close($conexion);
} else {
    echo '<script>
    alert("No se recibió el ID del curso.");
    window.location = "../cursos.php";
    </script>';
}
exit;
?>
