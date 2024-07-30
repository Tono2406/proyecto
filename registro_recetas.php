<?php
include 'db_config.php';  // Incluir archivo de configuración de la base de datos

if (isset($_GET['region'])) {
    $region = $_GET['region'];
    $sql = "SELECT nombre, descripcion FROM Recetas WHERE region = '$region'";
    $result = $conn->query($sql);

    $recipes = array();
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }

    echo json_encode($recipes);
} else {
    echo json_encode(array("error" => "No region specified"));
}

$conn->close();
?>
