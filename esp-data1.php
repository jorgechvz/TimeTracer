<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table-container {
            max-width: 1100px;
            margin: 20px auto;
        }

        h1 {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            background-color: #ffffff;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #343a40;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <title>Mi primer proyecto IoT</title>
</head>

<body>
    <div class="table-container">
        <?php
        include("credentials.php");
        $connection = new mysqli($servername, $username, $password, $dbname);
        if ($connection->connect_error)
            die("Connection failed: " . $conn->connect_error);
        $sql = "SELECT * FROM sensordata;";
        echo '<table id="dataTable" class="table table-bordered">
        <thead class="thead-dark">
            <tr> 
                <th>Temperatura (C°)</th> 
                <th>Humedad (%)</th>
                <th>Tiempo</th> 
            </tr>
        </thead>
        <tbody>';
        if ($result = $connection->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $row_temperature = $row["temperature"];
                $row_humidity = $row["humidity"];
                $row_reading_time = $row["datetime"];
                echo '<tr> 
                    <td>' . $row_temperature . '</td> 
                    <td>' . $row_humidity . '</td> 
                    <td>' . $row_reading_time . '</td> 
                </tr>';
            }
        }
        echo '</tbody></table>';
        ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
</body>

</html>