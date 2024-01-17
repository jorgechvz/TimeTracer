<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sistema Web</title>
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
            <main class="mx-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col ml-3 mt-4">
                            <div class="bg-danger text-white mb-4 my-custom-rounded h-75 d-flex justify-content-between align-items-center p-3 shadow-lg">
                                <div class="d-flex p-2 align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/external-line-gradient-kendis-lasman/32/000000/external-temperature-weather-and-disaster-line-gradient-line-gradient-kendis-lasman.png" alt="Temperatura Image" style="width: 50px; height: 50px;">
                                    <h4 class="p-0 pl-3">Temperatura Actual</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <?php
                                    include("credentials.php");
                                    $connection = new mysqli($servername, $username, $password, $dbname);

                                    if ($connection->connect_error) {
                                        die("Connection failed: " . $connection->connect_error);
                                    }

                                    $sql = "SELECT temperature FROM sensordata ORDER BY datetime DESC LIMIT 1";
                                    $result = $connection->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $latest_temperature = $row["temperature"];
                                        echo '<h2 class="p-0 text-white">' . $latest_temperature . ' °C</h2>';
                                    } else {
                                        echo '<span class="small text-white">No hay datos disponibles</span>';
                                    }

                                    $connection->close();
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col m-0 mt-4">
                            <div class="bg-primary text-white mb-4 my-custom-rounded d-flex justify-content-between align-items-center p-3 shadow-lg">
                                <div class="d-flex p-2 align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/clouds/100/water.png" alt="Humedad Image" style="width: 50px; height: 50px;">
                                    <h4 class="p-0 pl-3 text-center">Humedad Actual</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <?php
                                    include("credentials.php");
                                    $connection = new mysqli($servername, $username, $password, $dbname);

                                    if ($connection->connect_error) {
                                        die("Connection failed: " . $connection->connect_error);
                                    }

                                    $sql = "SELECT humidity FROM sensordata ORDER BY datetime DESC LIMIT 1";
                                    $result = $connection->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $latest_humidity = $row["humidity"];
                                        echo '<h2 class="p-0 text-white">' . $latest_humidity . ' %</h2>';
                                    } else {
                                        echo '<span class="normal text-white">No hay datos disponibles</span>';
                                    }

                                    $connection->close();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="charts mx-3">
                    <div class="d-block d-md-flex flex-wrap">
                        <div class="card mb-4 mx-2 p-2 flex-fill shadow-lg">
                            <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Temperatura</div>
                            <div id="temperaturechart-container"></div>
                        </div>
                        <div class="card mb-4 mx-2 p-2 flex-fill shadow-lg">
                            <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Humedad</div>
                            <div id="humedadchart-container"></div>
                        </div>
                    </div>
                    <div class="card mb-4 mx-2 p-2 shadow-lg">
                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Temperatura y Humedad</div>
                        <div id="datachart-container"></div>
                    </div>
                    <div class="card mb-4 mx-2 p-2 shadow-lg">
                        <div class="card-header"><i class="fas fa-table mr-1"></i>Datos</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="datatable-container"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">

                    </div>
                </div>
            </footer>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

        <!-- Otros scripts necesarios -->
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>

        <!-- Tu script personalizado -->
        <script>
            $(document).ready(function() {
                $("#datachart-container").load("esp-chart1.php");
                $("#datatable-container").load("esp-data1.php");
                $('#temperaturechart-container').load("temperature-chart.php");
                $('#humedadchart-container').load("humedad-chart.php");
            });
        </script>

</body>

</html>