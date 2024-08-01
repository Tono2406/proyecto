<?php
$servername = "b7pblae3gsp0y5tp2pax-mysql.services.clever-cloud.com";
$username = "uqetcp6gs8wobi7j";
$password = "xoTTANISQdIWIgN4MrtC";
$dbname = "b7pblae3gsp0y5tp2pax";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
