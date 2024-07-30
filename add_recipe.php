<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $region = $_POST['region'];
    $time = $_POST['time'];
    $difficulty = $_POST['difficulty'];
    $ingredients = $_POST['ingredients'];

    $sql = "INSERT INTO Recetas (nombre, descripcion, region, tiempo_preparacion, dificultad) VALUES ('$name', '$description', '$region', $time, '$difficulty')";
    
    if ($conn->query($sql) === TRUE) {
        $receta_id = $conn->insert_id;
        $ingredient_list = json_decode($ingredients, true);
        
        foreach ($ingredient_list as $ingredient) {
            $ingredient_name = $ingredient['name'];
            $quantity = $ingredient['quantity'];
            $unit = $ingredient['unit'];
            
            $sql_ingredient = "INSERT INTO Ingredientes (nombre) VALUES ('$ingredient_name')";
            $conn->query($sql_ingredient);
            $ingredient_id = $conn->insert_id;
            
            $sql_receta_ingrediente = "INSERT INTO Recetas_Ingredientes (id_receta, id_ingrediente, cantidad, unidad) VALUES ($receta_id, $ingredient_id, '$quantity', '$unit')";
            $conn->query($sql_receta_ingrediente);
        }

        echo json_encode(array('status' => 'success', 'message' => 'Receta guardada exitosamente'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error al guardar la receta'));
    }
}

$conn->close();
?>
