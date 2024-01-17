<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $contraseña = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nacimiento = $_POST['nacimiento'];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $role = 2;

    // Valida cada campo
    if (empty($usuario)) {
        $errors['usuario'] = 'El nombre de usuario es requerido.';
    }

    if (empty($contraseña)) {
        $errors['$password'] = 'La contraseña es requerida.';
    }

    if (empty($name)) {
        $errors['nombre'] = 'El nombre es requerido.';
    }

    if (empty($email)) {
        $errors['email'] = 'El correo electrónico es requerido.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El correo electrónico no es válido.';
    }

    // Conexión a la base de datos
    include("credentials.php");
    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Verifica si el nombre de usuario ya existe
    $username_check = $connection->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $username_check->bind_param('s', $usuario);
    $username_check->execute();
    $resultUsername = $username_check->get_result();

    // Verifica si el correo electrónico ya existe
    $email_check = $connection->prepare("SELECT * FROM usuarios WHERE email = ?");
    $email_check->bind_param('s', $email);
    $email_check->execute();
    $result = $email_check->get_result();
    if ($result->num_rows > 0) {
        // El correo electrónico ya existe
        $_SESSION['messageRegister']  = 'El correo electrónico ya está en uso.';
    } else if ($resultUsername->num_rows > 0) {
        $_SESSION['messageRegister'] = 'El nombre de usuario ya está en uso.';
    } else {
        // Si no hay errores de validación, procesa los datos del formulario
        if (empty($errors)) {

            // Prepara la consulta SQL
            $stmt = $connection->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, tipo_usuario, email, direccion, nacimiento, pais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $password_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
            // Vincula los parámetros a la consulta SQL
            $stmt->bind_param('ssssssss', $usuario, $password_hashed, $name, $role, $email, $direccion, $nacimiento, $pais);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                // Redirige al usuario a la página de éxito
                // Almacena el mensaje en la sesión
                $_SESSION['createMessage'] = 'Tu Cuenta ha sido creada con éxito. Inicia sesión para continuar.';
                header('Location: login.php');
                exit;
            } else {
                // Algo ha ido mal
                echo 'Error: ' . $stmt->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Registrar Cuenta | Time Tracer</title>
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header id="header" class="header fixed-top text-white">
        <?php require "common/header.php"; ?>
    </header>
    <section class="pt-5 pb-5 mt-6 align-items-center d-flex bg-dark" style="min-height: 140vh; background-size: cover; background-image: url(https://images.unsplash.com/photo-1477346611705-65d1883cee1e?ixlib=rb-0.3.5&amp;q=80&amp;fm=jpg&amp;crop=entropy&amp;cs=tinysrgb&amp;w=1920&amp;fit=max&amp;ixid=eyJhcHBfaWQiOjMyMDc0fQ&amp;s=c0d43804e2c7c93143fe8ff65398c8e9);">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center d-flex-row text-center h-100">
                <div class="col-12 col-md-4 col-lg-5 h-50">
                    <div class="card shadow ">
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['messageRegister'])) {
                                echo '<div id="register-alert" class="mt-4 alert d-flex align-items-center alert-danger alert-dismissible fade show py-2" role="alert"><p class="text-left my-auto">' . $_SESSION['messageRegister'] . '</p></div>';
                                // Borra el mensaje de la sesión
                                unset($_SESSION['messageRegister']);
                            }
                            ?>
                            <div class="mt-3 mb-5 ">
                                <h4 class="card-title text-center">Bienvenido</h4>
                                <p class="text-center">Ingresa tus datos para crear una nueva cuenta. ¡Es Gratis!</p>
                            </div>
                            <form method="post">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <input name="usuario" id="usuario" class="form-control" placeholder="Usuario" type="text" required>
                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <input name="name" id="name" class="form-control" placeholder="Nombre" type="text" required>
                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    </div>
                                    <input name="password" id="password" class="form-control" placeholder="Contraseña" type="password" required>
                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                    </div>
                                    <input name="email" id="email" class="form-control" placeholder="Correo Electrónico" type="email" required>
                                </div>
                                <div class="row">
                                    <div class="form-group input-group col-8">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="bi bi-geo-alt-fill"></i> </span>
                                        </div>
                                        <input name="direccion" id="direccion" class="form-control" placeholder="Dirección" type="text">
                                    </div>
                                    <div class="form-group input-group col-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="bi bi-flag-fill"></i> </span>
                                        </div>
                                        <input name="pais" id="pais" class="form-control" placeholder="Pais" type="text">
                                    </div>
                                </div>
                                <p for="nacimiento" class="text-left form-label">Fecha de Nacimiento</p>
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="bi bi-calendar-date-fill"></i> </span>
                                    </div>
                                    <input name="nacimiento" id="nacimiento" class="form-control" type="date">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-block" value="Crear Cuenta" />
                                </div>
                                <p class="text-center">¿Ya tienes Cuenta?
                                    <a href="login.php">Iniciar Sesión</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require "common/footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Elimina la alerta después de 5 segundos
            setTimeout(function() {
                $('#register-alert').alert('close');
            }, 5000);
            // ...
        });
    </script>
</body>

</html>