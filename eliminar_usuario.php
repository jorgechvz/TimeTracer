<?php
include("credentials.php");
$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM usuarios WHERE id = $id";
    if ($connection->query($sql) === TRUE) {
        echo "Usuario eliminado con éxito";
    } else {
        echo "Error eliminando al usuario: " . $connection->error;
    }
} else {
    echo 'No se proporcionó un ID de usuario';
}

$connection->close();
?>