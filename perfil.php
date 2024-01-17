<?php
// Obtener datos de la sesion
session_start();
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$id = $_SESSION['id'];

include("credentials.php");
$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $connection->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo 'No hay datos disponibles';
        exit;
    }
} else {
    echo 'No se proporcionó un ID de usuario';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_usuario = $_POST['username'];
    $new_password = $_POST['password'];
    $new_nombre = $_POST['name'];
    $new_email = $_POST['email'];
    $new_direccion = $_POST['direccion'];
    $new_nacimiento = $_POST['nacimiento'];
    $new_pais = $_POST['pais'];

    $error = null;
    $successMessage = null;

    // Verifica si ya existe un usuario con el mismo nombre de usuario
    $check_sql = "SELECT * FROM usuarios WHERE usuario = '$new_usuario' AND id != $id";
    $check_result = $connection->query($check_sql);
    if ($check_result->num_rows > 0) {
        $error = "Ya existe un usuario con ese nombre de usuario.";
    }

    // Verifica si el correo electrónico ya existe
    $check_sql = "SELECT * FROM usuarios WHERE email = '$new_email' AND id != $id";
    $check_result = $connection->query($check_sql);
    if ($check_result->num_rows > 0) {
        $error = "Ya existe un usuario con ese correo electrónico.";
    }

    // Si el campo de contraseña no está vacío, hashea la nueva contraseña
    if (!isset($error)) {
        if (!empty($new_password)) {
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET usuario = '$new_usuario', nombre = '$new_nombre', contrasena='$new_password' ,email = '$new_email', direccion = '$new_direccion', nacimiento = '$new_nacimiento', pais = '$new_pais' WHERE id = $id";
        } else {
            // Si el campo de contraseña está vacío, no actualices la contraseña
            $sql = "UPDATE usuarios SET usuario = '$new_usuario', nombre = '$new_nombre', email = '$new_email', direccion = '$new_direccion', nacimiento = '$new_nacimiento', pais = '$new_pais' WHERE id = $id";
        }

        if ($connection->query($sql) === TRUE) {
            $successMessage = "Informacion de perfil actualizada con éxito";
        } else {
            $error =  "Error actualizando la información del perfil: " . $connection->error;
        }
    }
}

// Si el formulario se envió a través de AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Devuelve una respuesta en formato JSON con el nuevo rol
    header('Content-Type: application/json');
    echo json_encode(['newRole' => $newRole]);
    exit;
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Detalle de Usuario de <?php echo $nombre ?></title>
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
            <main class="m-5 shadow-lg p-3">
                <?php
                if (isset($error)) {
                    echo '<div id="alert" class="alert alert-danger" role="alert">' . $error . '</div>';
                    $error = null;
                } else if (isset($successMessage)) {
                    echo '<div id="alert" class="alert alert-success" role="alert">' . $successMessage . '</div>';
                    $successMessage = null;
                }
                ?>
                <h1 class="mt-4">Mi Perfil</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="principal.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mi Perfil</li>
                </ol>
                <form method="post" id="your-form-id" class="d-flex flex-column">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username:</label><br>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['usuario']; ?>"><br>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña:</label><br>
                            <input type="password" class="form-control" id="password" name="password" placeholder="************"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre:</label><br>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['nombre']; ?>"><br>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico:</label><br>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>"><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">Dirección:</label><br>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $row['direccion']; ?>"><br>
                        </div>
                        <div class="col-md-3">
                            <label for="pais" class="form-label">País:</label><br>
                            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo $row['pais']; ?>"><br>
                        </div>
                        <div class="col-md-3">
                            <label for="nacimiento" class="form-label">Fecha de Nacimiento:</label><br>
                            <input type="date" class="form-control" id="nacimiento" name="nacimiento" value="<?php echo $row['nacimiento']; ?>"><br>
                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="mt-4 d-flex gap-2 align-self-end">
                        <input class="btn btn-primary w-100" type="submit" value="Actualizar Información">
                        <a class="btn btn-outline-primary" href="tabla.php">Regresar</a>
                    </div>
                </form>
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
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#alert').hide();
            }, 3000);
            $('#your-form-id').submit(function(e) {

                $.ajax({
                    url: $(this).attr("action"),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#role').val(response.newRole);

                        $('#alert').addClass('alert-success').text('Información de Perfil Actualizada con Exito.').show();
                    },
                });
                // Oculta la alerta después de 3 segundos
                setTimeout(function() {
                    $('#alert').hide();
                }, 3000);
            });
        });
    </script>
</body>