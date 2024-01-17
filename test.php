<?php

$servername = "localhost";
$dbname = "sistema";       // replace with dbname
$password = "";     // replace with password
$username = "root";     // replace with username

$distritos = array("Selva Alegre", "Cercado", "Cayma", "Cerro Colorado", "Yanahuara", "Mariano Melgar", "Miraflores", "Paucarpata", "Socabaya", "Hunter", "Jacobo Hunter", "Alto Selva Alegre", "José Luis Bustamante y Rivero", "Tiabaya", "Uchumayo", "Yura", "Characato", "La Joya", "Mollebaya", "Polobaya", "Quequeña", "Sabandía", "Sachaca", "Tingo", "Yarabamba");

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error)
    die("Connection failed: " . $connection->connect_error);

// Obtén todos los registros de la tabla
$sql = "SELECT * FROM sensordata";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    // Actualiza cada registro con un distrito aleatorio
    while($row = $result->fetch_assoc()) {
        $distrito_aleatorio = $distritos[array_rand($distritos)];
        $sql = "UPDATE sensordata SET distrito = '$distrito_aleatorio' WHERE id = " . $row['id'];
        if ($connection->query($sql) !== True)
            die("Error updating record: " . $connection->error);
    }
    echo "Records updated successfully!";
} else {
    echo "No records found!";
}

$connection->close();