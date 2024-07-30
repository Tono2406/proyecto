<?php
include 'db_config.php';

function createTablesIfNotExists($conn) {
    $createRecetasTable = "CREATE TABLE IF NOT EXISTS Recetas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        descripcion TEXT,
        region VARCHAR(255),
        tiempo_preparacion INT,
        dificultad VARCHAR(50)
    )";

    $createIngredientesTable = "CREATE TABLE IF NOT EXISTS Ingredientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL UNIQUE
    )";

    $createRecetasIngredientesTable = "CREATE TABLE IF NOT EXISTS Recetas_Ingredientes (
        id_receta INT,
        id_ingrediente INT,
        cantidad DECIMAL(10, 2),
        unidad VARCHAR(50),
        PRIMARY KEY (id_receta, id_ingrediente),
        FOREIGN KEY (id_receta) REFERENCES Recetas(id) ON DELETE CASCADE,
        FOREIGN KEY (id_ingrediente) REFERENCES Ingredientes(id) ON DELETE CASCADE
    )";

    if ($conn->query($createRecetasTable) !== TRUE) {
        die(json_encode(array('status' => 'error', 'message' => 'Error al crear la tabla Recetas: ' . $conn->error)));
    }

    if ($conn->query($createIngredientesTable) !== TRUE) {
        die(json_encode(array('status' => 'error', 'message' => 'Error al crear la tabla Ingredientes: ' . $conn->error)));
    }

    if ($conn->query($createRecetasIngredientesTable) !== TRUE) {
        die(json_encode(array('status' => 'error', 'message' => 'Error al crear la tabla Recetas_Ingredientes: ' . $conn->error)));
    }
}

createTablesIfNotExists($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $region = $conn->real_escape_string($_POST['region']);
    $time = (int)$_POST['time'];
    $difficulty = $conn->real_escape_string($_POST['difficulty']);
    $ingredients = $_POST['ingredients'];

    $sql = "INSERT INTO Recetas (nombre, descripcion, region, tiempo_preparacion, dificultad) VALUES ('$name', '$description', '$region', $time, '$difficulty')";
    
    if ($conn->query($sql) === TRUE) {
        $receta_id = $conn->insert_id;
        $ingredient_list = json_decode($ingredients, true);
        
        foreach ($ingredient_list as $ingredient) {
            $ingredient_name = $conn->real_escape_string($ingredient['name']);
            $quantity = $conn->real_escape_string($ingredient['quantity']);
            $unit = $conn->real_escape_string($ingredient['unit']);
            
            // Check if ingredient already exists
            $sql_check_ingredient = "SELECT id FROM Ingredientes WHERE nombre = '$ingredient_name'";
            $result = $conn->query($sql_check_ingredient);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $ingredient_id = $row['id'];
            } else {
                $sql_ingredient = "INSERT INTO Ingredientes (nombre) VALUES ('$ingredient_name')";
                if ($conn->query($sql_ingredient) === TRUE) {
                    $ingredient_id = $conn->insert_id;
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Error al insertar ingrediente: ' . $conn->error));
                    exit;
                }
            }
            
            $sql_receta_ingrediente = "INSERT INTO Recetas_Ingredientes (id_receta, id_ingrediente, cantidad, unidad) VALUES ($receta_id, $ingredient_id, '$quantity', '$unit')";
            if ($conn->query($sql_receta_ingrediente) !== TRUE) {
                echo json_encode(array('status' => 'error', 'message' => 'Error al insertar en Recetas_Ingredientes: ' . $conn->error));
                exit;
            }
        }

        echo json_encode(array('status' => 'success', 'message' => 'Receta guardada exitosamente'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error al guardar la receta: ' . $conn->error));
    }
}

$conn->close();
?>
