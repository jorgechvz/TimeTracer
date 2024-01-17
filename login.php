<?php

require "conexion.php";

session_start();

if (isset($_POST['form_submitted'])) {

	$usuario = $_POST['usuario'];
	$password = $_POST['password'];

	$sql = "SELECT id, contrasena, nombre, tipo_usuario FROM usuarios WHERE usuario='$usuario'";
	$resultado = $mysqli->query($sql);
	$num = $resultado->num_rows;

	if ($num > 0) {
		$row = $resultado->fetch_assoc();
		$password_bd = $row['contrasena'];

		if (password_verify($password, $password_bd)) {

			$_SESSION['id'] = $row['id'];
			$_SESSION['nombre'] = $row['nombre'];
			$_SESSION['tipo_usuario'] = $row['tipo_usuario'];

			header("Location: principal.php");
		} else {
			$errorPassword = "Contraseña incorrecta";
		}
	} else {
		$errorUsuario = "El usuario no existe";
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
	<title>Iniciar Sesision | Time Tracer</title>
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
	<section class="pt-5 pb-5 mt-0 align-items-center d-flex bg-dark" style="min-height: 120vh; background-size: cover; background-image: url(https://images.unsplash.com/photo-1477346611705-65d1883cee1e?ixlib=rb-0.3.5&amp;q=80&amp;fm=jpg&amp;crop=entropy&amp;cs=tinysrgb&amp;w=1920&amp;fit=max&amp;ixid=eyJhcHBfaWQiOjMyMDc0fQ&amp;s=c0d43804e2c7c93143fe8ff65398c8e9);">
		<div class="container-fluid">
			<div class="row justify-content-center align-items-center d-flex-row text-center h-100">
				<div class="col-12 col-md-4 col-lg-5 h-50">
					<div class="card shadow ">
						<?php
						if (isset($_SESSION['createMessage'])) {
							echo '<div id="created-alert" class="mt-4 alert d-flex align-items-center alert-success alert-dismissible fade show py-2" role="alert"><i class="bi bi-check2 pr-3 size-icon-success"></i><p class="text-left my-auto">' . $_SESSION['createMessage'] . '</p></div>';
							// Borra el mensaje de la sesión
							unset($_SESSION['createMessage']);
						}
						?>
						<div class="card-body">
							<div class="mt-3 mb-5 ">
								<h4 class="card-title text-center">Bienvenido</h4>
								<p class="text-center">Ingresa tus credenciales para iniciar sesión</p>
							</div>
							<?php
							if ($_SERVER['REQUEST_METHOD'] === 'POST') {
								if (isset($errorUsuario)) {
									echo '<div id="alertUsuario" class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle pr-2"></i>' . $errorUsuario . '</div>';
									unset($errorUsuario);
								}
								if (isset($errorPassword)) {
									echo '<div id="alertPassword" class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle pr-2"></i>' . $errorPassword . '</div>';
									unset($errorPassword);
								}
							}
							?>
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"> <i class="fa fa-user"></i> </span>
									</div>
									<input name="usuario" id="usuario" class="form-control" placeholder="Usuario" type="text">
								</div>
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
									</div>
									<input name="password" id="password" class="form-control" placeholder="Contraseña" type="password">
								</div>
								<div class="form-group">
									<input type="hidden" name="form_submitted" value="1" />
									<input type="submit" class="btn btn-primary btn-block" value="Iniciar Sesión" />
								</div>
								<p class="text-center">¿No tienes Cuenta?
									<a href="register.php">Registrate</a>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Crear aleatoriamente distritos  -->
	<!-- <form action="test.php" method="post">
		<input type="submit" value="Ejecutar PHP">
	</form> -->
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
				$('#created-alert').alert('close');
			}, 5000);
			// ...
		});
		// Espera 5 segundos (5000 milisegundos) y luego oculta los elementos de alerta
		setTimeout(function() {
			var alertUsuario = document.getElementById('alertUsuario');
			var alertPassword = document.getElementById('alertPassword');
			if (alertUsuario) alertUsuario.style.display = 'none';
			if (alertPassword) alertPassword.style.display = 'none';
		}, 7000);
	</script>
</body>

</html>