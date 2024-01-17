<?php
// Obtener datos de la sesion
session_start();
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];


include("credentials.php");
$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
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
    $new_role = $_POST['role'];
    $sql = "UPDATE usuarios SET tipo_usuario = $new_role WHERE id = $id";
    if ($connection->query($sql) === TRUE) {
        echo "Rol actualizado con éxito";
    } else {
        echo "Error actualizando el rol: " . $connection->error;
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
                <div id="alert" class="alert" role="alert" style="display: none;"></div>
                <h1 class="mt-4">Información de <?php echo $row['nombre'] ?> </h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="principal.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="tabla.php">Usuarios</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
                <form method="post" id="your-form-id" class="d-flex flex-column">
                    <label for="username" class="form-label">Username:</label><br>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['usuario']; ?>" readonly><br>
                    <label for="name" class="form-label">Nombre:</label><br>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['nombre']; ?>" readonly><br>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="email" class="form-label">Correo Electrónico:</label><br>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" readonly><br>
                        </div>
                        <div class="col-md-4">
                            <label for="nacimiento" class="form-label">Fecha de Nacimiento:</label><br>
                            <input type="date" class="form-control" id="nacimiento" name="nacimiento" value="<?php echo $row['nacimiento']; ?>" readonly><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="direccion" class="form-label">Dirección:</label><br>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $row['direccion']; ?>" readonly><br>
                        </div>
                        <div class="col-md-4">
                            <label for="pais" class="form-label">País:</label><br>
                            <input type="text" class="form-control" id="pais" name="pais" value="<?php echo $row['pais']; ?>" readonly><br>
                        </div>
                    </div>
                    <div class="row">
                        <label for="role" class="form-label">Rol:</label>
                        <div class="col-md-10">
                            <select class="form-select" id="role" name="role">
                                <option value="1" <?php if ($row['tipo_usuario'] == 1) echo 'selected'; ?>>Administrador</option>
                                <option value="2" <?php if ($row['tipo_usuario'] == 2) echo 'selected'; ?>>Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input class="btn btn-primary w-100" type="submit" value="Actualizar Rol">
                        </div>
                    </div>
                    <div class="mt-4 align-self-end">
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
            $('#your-form-id').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#role').val(response.newRole);

                        $('#alert').addClass('alert-success').text('El rol se actualizó con éxito.').show();
                    },
                    error: function() {
                        $('#alert').addClass('alert-success').text('El rol se actualizó con éxito.').show();
                    }
                });
                // Oculta la alerta después de 3 segundos
                setTimeout(function() {
                    $('#alert').hide();
                }, 3000);
            });
        });
    </script>
</body>