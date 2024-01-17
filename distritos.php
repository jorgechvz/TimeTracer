<?php

// Obtener datos de la sesion
session_start();
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

$servername = "localhost";
$dbname = "sistema";
$password = "";
$username = "root";

$distritos = array("Selva Alegre", "Cercado", "Cayma", "Cerro Colorado", "Yanahuara", "Mariano Melgar", "Miraflores", "Paucarpata", "Socabaya", "Hunter", "Jacobo Hunter", "Alto Selva Alegre", "José Luis Bustamante y Rivero", "Tiabaya", "Uchumayo", "Yura", "Characato", "La Joya", "Mollebaya", "Polobaya", "Quequeña", "Sabandía", "Sachaca", "Tingo", "Yarabamba");

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error)
    die("Connection failed: " . $connection->connect_error);

$distrito_seleccionado = $_POST['distrito'] ?? null;
$datos = [];

if ($distrito_seleccionado) {
    $sql = "SELECT * FROM sensordata WHERE distrito = '$distrito_seleccionado';";
    $temperature = array();
    $humidity = array();
    if ($result = $connection->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $jsonarray = array();
            $jsonarray2 = array();
            $dateFormated = date("H:i", strtotime($row["datetime"]));
            $jsonarray["y"] = $row["temperature"];
            $jsonarray["label"] = $row["datetime"];
            $jsonarray2["y"] = $row["humidity"];
            $jsonarray2["label"] = $dateFormated;
            array_push($temperature, $jsonarray);
            array_push($humidity, $jsonarray2);
            array_push($datos, $row);
        }
    }
}



$connection->close();
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
                <div class="mx-5">
                    <div class="mx-4">
                        <h1 class="mt-3 mb-5">Datos de Temperatura y Humedad por Distrito</h1>
                        <div class="shadow-lg p-4 mb-4 rounded-lg">
                            <form action="distritos.php" method="post">
                                <div class="row">
                                    <div class="mb-2 col-md-12">
                                        <h3>Selecciona el Distrito de Arequipa que deseas ver</h3>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="distrito" id="distrito" class="form-select">
                                            <?php foreach ($distritos as $distrito) : ?>
                                                <option value="<?php echo $distrito; ?>" <?php if (isset($distrito_seleccionado) && $distrito_seleccionado == $distrito) echo 'selected'; ?>>
                                                    <?php echo $distrito; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="submit" value="Mostrar datos" class="btn btn-primary btn-block">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="map"></div>
                    <script>
                        window.addEventListener('load', function() {
                            var chart = new CanvasJS.Chart("charttemphumi", {
                                animationEnabled: true,
                                theme: "light2",
                                /* Cambiado a un tema claro */
                                zoomEnabled: true,
                                title: {
                                    text: "",
                                    fontSize: 20,
                                    fontWeight: "normal"
                                },
                                subtitles: [{
                                    text: "",
                                    fontSize: 14,
                                    fontWeight: "normal"
                                }],
                                axisX: {
                                    title: "Tiempo",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                axisY: {
                                    title: "Temperatura (°C)",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                axisY2: {
                                    title: "Humedad (%)",
                                    titleFontColor: "#C0504E",
                                    lineColor: "#C0504E",
                                    labelFontColor: "#C0504E",
                                    tickColor: "#C0504E",
                                    includeZero: false
                                },
                                legend: {
                                    cursor: "pointer",
                                    dockInsidePlotArea: true,
                                    itemFontSize: 14
                                },
                                data: [{
                                        type: "spline",
                                        markerSize: 5,
                                        name: "Temperatura",
                                        showInLegend: true,
                                        toolTipContent: "Temperatura: {y} °C <br>Time: {label}",
                                        dataPoints: <?php echo json_encode($temperature, JSON_NUMERIC_CHECK); ?>
                                    },
                                    {
                                        type: "spline",
                                        axisYType: "secondary",
                                        name: "Humedad",
                                        showInLegend: true,
                                        toolTipContent: "Humedad: {y}% <br>Time: {label}",
                                        dataPoints: <?php echo json_encode($humidity, JSON_NUMERIC_CHECK); ?>
                                    }
                                ]
                            });
                            chart.render();
                        })
                        window.addEventListener("load", function() {
                            var chart = new CanvasJS.Chart("temperature-chart-container", {
                                animationEnabled: true,
                                theme: "ligth2",
                                zoomEnabled: false,
                                title: {
                                    text: "",
                                    fontSize: 20,
                                    fontColor: "normal",
                                },
                                subtitles: [{
                                    text: "",
                                    fontSize: 16,
                                    fontColor: "normal",
                                }],
                                axisX: {
                                    title: "Tiempo",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "EB4825",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                axisY: {
                                    title: "Temperatura (ºC)",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                legend: {
                                    cursor: "pointer",
                                    itemFontSize: 14
                                },
                                data: [{
                                    type: "area",
                                    color: "#EC4623",
                                    name: "Temperatura",
                                    showInLegend: true,
                                    markerSize: 5,
                                    showInLegend: true,
                                    toolTipContent: "Temperatura: {y} °C <br>Time: {label}",
                                    dataPoints: <?php echo json_encode($temperature, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                            chart.render();
                        })
                        window.addEventListener("load", function() {
                            var chart = new CanvasJS.Chart("humedadchart-container", {
                                animationEnabled: true,
                                theme: "ligth2",
                                zoomEnabled: false,
                                title: {
                                    text: "",
                                    fontSize: 20,
                                    fontColor: "normal",
                                },
                                subtitles: [{
                                    text: "",
                                    fontSize: 16,
                                    fontColor: "normal",
                                }],
                                axisX: {
                                    title: "Tiempo",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                axisY: {
                                    title: "Humedad (%)",
                                    titleFontColor: "#4F81BC",
                                    lineColor: "#4F81BC",
                                    labelFontColor: "#4F81BC",
                                    tickColor: "#4F81BC"
                                },
                                legend: {
                                    cursor: "pointer",
                                    itemFontSize: 14
                                },
                                data: [{
                                    type: "line",
                                    name: "Humedad",
                                    markerSize: 5,
                                    showInLegend: true,
                                    toolTipContent: "Temperatura: {y} °C <br>Time: {label}",
                                    dataPoints: <?php echo json_encode($humidity, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                            chart.render();
                        })
                    </script>
                    <section class="charts mx-3">
                        <div class="mx-3 container-chart">
                            <div class="d-block d-md-flex card-chart">
                                <div class="card card-chart mb-4 mx-2 p-2 flex-fill shadow-lg">
                                    <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Temperatura</div>
                                    <div id="temperature-chart-container"></div>
                                </div>
                                <div class="card card-chart mb-4 mx-2 p-2 flex-fill shadow-lg">
                                    <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Humedad</div>
                                    <div id="humedadchart-container"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-block card card-chart mb-4 mx-2 p-2 shadow-lg">
                            <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Temperatura y Humedad</div>
                            <div id="charttemphumi"></div>
                        </div>


                        <div class="card mb-4 mx-2 p-4 shadow-lg">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>Datos</div>
                            <div class="card-body p-5 mx-5">
                                <div class="table-responsive">
                                    <div class="table-container">
                                        <table id="dataTable" class="table table-bordered centered-table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Temperatura (C°)</th>
                                                    <th>Humedad (%)</th>
                                                    <th>Tiempo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($datos as $row) {
                                                    $row_temperature = $row["temperature"];
                                                    $row_humidity = $row["humidity"];
                                                    $row_reading_time = $row["datetime"];
                                                    echo '<tr> 
                                                    <td>' . $row_temperature . '</td> 
                                                    <td>' . $row_humidity . '</td> 
                                                    <td>' . $row_reading_time . '</td> 
                                                </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>