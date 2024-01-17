<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!--<meta http-equiv="refresh" content="60">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #495057;
        }

        h1 {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        #charttemphumi {
            height: 450px;
            width: 100%;
            margin: 20px 0;
        }
    </style>
    <title>Mi primer proyecto IoT</title>
</head>

<body>

    <?php
    include("credentials.php");
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error)
        die("Connection failed: " . $conn->connect_error);
    $sql = "SELECT * FROM sensordata ORDER BY datetime DESC LIMIT 20;";
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
        }
    }
    ?>
    <script>
        window.addEventListener('load', function () {
            var chart = new CanvasJS.Chart("charttemphumi", {
                animationEnabled: true,
                theme: "light2", /* Cambiado a un tema claro */
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
    </script>
    <div id="charttemphumi"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>