<?php
session_start();
include 'php/conexion_be.php';

// Verificar si el usuario tiene rol de administrador
if ($_SESSION['role'] != 1) {
    echo '<script>alert("No tienes acceso a esta función."); window.location = "../index.php";</script>';
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Usuario</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="./css/styles.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="./index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="./assets/img/P1w.png" width="300" alt="">
                                </a>
                                <p class="text-center">ITEE-RESERVATIONS</p>
                                <form action="php/registro_usuario_be.php" method="POST">
                                    <div class="mb-3">
                                        <label for="exampleInputtext1" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" name="nombre_completo" aria-describedby="textHelp">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" name="correo" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Usuario</label>
                                        <input type="text" class="form-control" name="nombre_usuario">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" name="contrasena">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Tipo de Usuario </label><br>
                                        <div class="d-flex align-items-center justify-content-center w-100">
                                            <label>Administrador</label>&nbsp;&nbsp;<input type="radio" class="radio" value="1" name="role">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>Consultor</label>&nbsp;&nbsp;<input type="radio" class="radio" value="2" name="role">
                                        </div>
                                        
                                    </div>
                                    <button href="index.php" class="btn btn-primary w-100 py-1 fs-4 mb-4 rounded-4">Registrar</button>
                                </form>
                                <button class="btn btn-danger w-100 py-1 fs-4 mb-4 rounded-4 text-center">
                                <a href="index.php" class="btn btn-danger w-100">Cancelar</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>