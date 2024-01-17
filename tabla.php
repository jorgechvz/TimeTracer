<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['id'])) {
	header("Location: index.php");
}

$id = $_SESSION['id'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario == 1) {
	$where = "";
} else if ($tipo_usuario == 2) {
	$where = "WHERE id=$id";
}

$sql = "SELECT * FROM usuarios WHERE id != $id";
$resultado = $mysqli->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Tabla de Usuarios</title>
	<link href="css/styles.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">
	<header>
		<?php include("common/navbar.php"); ?>
	</header>
	<div id="layoutSidenav">
		<div id="layoutSidenav_nav">
			<?php include("common/sidebar.php"); ?>
		</div>
		<div id="layoutSidenav_content">
			<main class="m-4 shadow-lg px-4">
				<?php
				if (isset($_SESSION['message'])) {
					echo '<div id="created-alert" class="mt-4 alert d-flex align-items-center d-flex align-items-center alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check2"></i>' . $_SESSION['message'] . '</div>';
					// Borra el mensaje de la sesión
					unset($_SESSION['message']);
				}
				?>
				<div id="alert-container" class="mt-4"></div>
				<div class="d-flex justify-content-between align-items-center flex-wrap">
					<h1 class="mt-4">Tabla de Usuarios</h1>
					<a href="crear_usuario.php" class="btn btn-primary">Crear usuario</a>
				</div>
				<ol class="breadcrumb mb-4">
					<li class="breadcrumb-item"><a href="principal.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Usuarios</li>
				</ol>
				<div class="card mb-5">
					<div class="card mb-4">
						<div class="card-header"><i class="fas fa-table mr-1"></i></div>
						<div class="p-2">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Usuario</th>
											<th>Nombre</th>
											<th>Email</th>
											<th>Tipo Usuario</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($resultado->num_rows > 0) {
											while ($row = $resultado->fetch_assoc()) { ?>

												<tr>
													<td>
														<?php echo $row['usuario']; ?>
													</td>
													<td>
														<?php echo $row['nombre']; ?>
													</td>
													<td>
														<?php echo $row['email']; ?>
													</td>
													<td>
														<?php
														if ($row['tipo_usuario'] == 1) {
															echo 'Administrador';
														} else {
															echo 'Usuario';
														} ?>
													</td>
													<td>
														<!-- Botón para ver la información del usuario -->
														<a href="ver_usuario.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Ver</a>
														<!-- Botón para abrir el modal de eliminación -->
														<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
														<!-- Modal para confirmación de eliminacion -->
														<div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-body d-flex flex-column align-items-center justify-content-center">
																		<i class="bi bi-exclamation-circle-fill delete-confirmed"></i>
																		<h4>¿Estas seguro de eliminar este usuario?</h4>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
																		<!-- Botón para eliminar al usuario -->
																		<button type="button" class="btn btn-danger delete-btn" data-bs-dismiss="modal" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</td>
												</tr>
										<?php }
										} else {
											echo "No hay usuarios registrados</center>";
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</main>
			<footer class="py-4 bg-light mt-2">
				<div class="container-fluid">
					<div class="d-flex align-items-center justify-content-between small">
						<div class="text-muted"></div>
						<div>

						</div>
					</div>
				</div>
			</footer>
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
			// Elimina la alerta después de 5 segundos
			setTimeout(function() {
				$('#created-alert').alert('close');
			}, 5000);
			// ...
		});
		$(document).ready(function() {
			// Elimina la alerta después de 5 segundos
			$('.delete-btn').click(function() {
				let userId = $(this).data('id');
				let button = $(this);

				$.ajax({
					url: 'eliminar_usuario.php',
					type: 'GET',
					data: {
						id: userId
					},
					success: function(response) {
						button.closest('tr').remove(); // Elimina la fila de la tabla
						$('.modal').modal('hide'); // Cierra el modal

						// Crea la alerta de Bootstrap
						let alert = $('<div class="alert d-flex align-items-center d-flex align-items-center justify-content-between alert-success alert-dismissible fade show" role="alert">' +
							'<div><i class="bi bi-check2"></i> Usuario eliminado con éxito</div>' +
							'<button type="button" class="btn btn-outline-primary" data-bs-dismiss="alert" aria-label="Close">X</button>' +
							'</div>');

						// Agrega la alerta al contenedor
						$('#alert-container').append(alert);

						// Elimina la alerta después de 5 segundos
						setTimeout(function() {
							alert.alert('close');
						}, 5000);
					},
					error: function() {
						$('.modal').modal('hide'); // Cierra el modal
						// Crea la alerta de Bootstrap
						let alert = $('<div class="alert alert-danger d-flex align-items-center justify-content-between alert-dismissible fade show" role="alert">' +
							'Hubo un error al eliminar al usuario' +
							'<button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
							'</div>');

						// Agrega la alerta al contenedor
						$('#alert-container').append(alert);

						// Elimina la alerta después de 5 segundos
						setTimeout(function() {
							alert.alert('close');
						}, 2000);
					}
				});
			});
		});
	</script>
</body>

</html>