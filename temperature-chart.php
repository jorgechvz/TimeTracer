<style>
    #temperature-chart-container {
        width: 100%;
        height: 300px;
        padding: 5px;
    }
</style>
<?php
include("credentials.php");
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error)
    die("Connection failed: " . $conn->connect_error);
$sql = "SELECT temperature, datetime FROM sensordata ORDER BY datetime DESC LIMIT 20;";
$temperature = array();
if ($result = $connection->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $jsonarray = array();
        $jsonarray["y"] = $row["temperature"];
        $jsonarray["label"] = $row["datetime"];
        array_push($temperature, $jsonarray);
    }
}
?>
<script>
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
</script>

<div id="temperature-chart-container"></div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>