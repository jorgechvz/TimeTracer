<?php
// Obtener datos de la sesion
session_start();
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];


// Validacion de campos para crear un nuevo usuario 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nacimiento = $_POST['nacimiento'];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $role = $_POST['role'];

    // Array para almacenar los errores de validación
    $errors = [];

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

    if (empty($role)) {
        $errors['role'] = 'El rol es requerido.';
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
    $result = $username_check->get_result();

    if ($result->num_rows > 0) {
        // El nombre de usuario ya existe
        $errors['usuario'] = 'El nombre de usuario ya está en uso.';
    }

    // Verifica si el correo electrónico ya existe
    $email_check = $connection->prepare("SELECT * FROM usuarios WHERE email = ?");
    $email_check->bind_param('s', $email);
    $email_check->execute();
    $result = $email_check->get_result();

    if ($result->num_rows > 0) {
        // El correo electrónico ya existe
        $errors['email'] = 'El correo electrónico ya está en uso.';
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
                $_SESSION['message'] = 'Usuario creado con éxito';
                header('Location: tabla.php');
                exit;
            } else {
                // Algo ha ido mal
                echo 'Error: ' . $stmt->error;
            }

            // Cierra la conexión
            $stmt->close();
            $connection->close();
        }
    }
}
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Nuevo Usuario</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php include("common/navbar.php"); ?>
    </header>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("common/sidebar.php"); ?>
        </div>
        <div id="layoutSidenav_content">
            <main class="m-5">
                <div class="container-fluid">
                    <div id="alert" class="alert" role="alert" style="display: none;"></div>
                    <h1 class="mt-4">Crear Nuevo Usuario </h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="principal.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="tabla.php">Usuarios</a></li>
                        <li class="breadcrumb-item active">Nuevo Usuario</li>
                    </ol>
                    <form method="post" id="your-form-id" class="d-flex flex-column">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="usuario" name="usuario">
                                <?php if (isset($errors['usuario'])) : ?>
                                    <p class="error"><?php echo $errors['usuario']; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <?php if (isset($errors['password'])) : ?>
                                    <p class="error"><?php echo $errors['password']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="name" name="name">
                                <?php if (isset($errors['name'])) : ?>
                                    <p class="error"><?php echo $errors['name']; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Correo Electrónico:</label>
                                <input type="text" class="form-control" id="email" name="email">
                                <?php if (isset($errors['email'])) : ?>
                                    <p class="error"><?php echo $errors['email']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="my-4">Información opcional</p>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>
                            <div class="col-md-6 my-4">
                                <label for="nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" id="nacimiento" name="nacimiento">
                            </div>
                            <div class="col-md-6 my-4">
                                <label for="pais" class="form-label">País:</label>
                                <input type="text" class="form-control" id="pais" name="pais">
                            </div>
                        </div>
                        <div>
                            <label for="role" class="form-label">Rol:</label>
                            <select class="form-select" id="role" name="role">
                                <option value="" disabled selected>-- Escoge un Rol --</option>
                                <option value="1">Administrador</option>
                                <option value="2">Usuario</option>
                            </select>
                        </div>
                        <?php if (isset($errors['role'])) : ?>
                            <p class="role"><?php echo $errors['role']; ?></p>
                        <?php endif; ?>
                        <div class="mt-4 align-self-end">
                            <a class="btn btn-outline-primary" href="tabla.php">Regresar</a>
                            <input class="btn btn-primary" type="submit" value="Crear Usuario">
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>