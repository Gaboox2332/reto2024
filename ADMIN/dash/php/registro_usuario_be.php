<?php 

include 'conexion_be.php';

$nombre_completo = $_POST['nombre_completo'];
$correo = $_POST['correo'];
$nombre_usuario = $_POST['nombre_usuario'];
$contrasena = $_POST['contrasena'];
//encriptar contrasena
$contrasena = hash('sha512', $contrasena);
$role = $_POST['role'];

$query = "INSERT INTO usuarios(nombre_completo, correo, nombre_usuario, contrasena, role, status) 
         VALUES('$nombre_completo', '$correo', '$nombre_usuario', '$contrasena', '$role', 1)";


//verificar que el correo no se repita en la bd

$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo= '$correo'");

if(mysqli_num_rows($verificar_correo) > 0){
    echo'
    <script>
    alert("Este correo ya esta registrado intenta con otro correo");
    window.location = "../index.php";
    </script>
    ';
    exit();
}

//verificar que el nombre de usuario no se repita en la bd

$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nombre_usuario= '$nombre_usuario'");

if(mysqli_num_rows($verificar_usuario) > 0){
    echo'
    <script>
    alert("Este usuario ya esta registrado intenta con otro nombre de usuario");
    window.location = "../index.php";
    </script>
    ';
    exit();
}


$ejecutar = mysqli_query($conexion, $query);


if($ejecutar){
    echo '
    <script>
    alert("Usuario Registrado Exitosamente");
    window.location = "../index.php"
    </script>
    ';
}else{echo '
    <script>
    alert("Intentalo nuevamente usuario no registrado");
    window.location = "../index.php"
    </script>
    ';
}


mysqli_close($conexion);
?>