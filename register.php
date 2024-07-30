<?php
include 'db_config.php';  // Incluir archivo de configuración de la base de datos

// Obtener datos del POST
$nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$contrasena = isset($_POST['contrasena']) ? $conn->real_escape_string($_POST['contrasena']) : '';

// Verificar que los datos no estén vacíos
if (!empty($nombre) && !empty($email) && !empty($contrasena)) {
    // Verificar si el nombre o el email ya existen en la base de datos
    $checkSql = "SELECT * FROM usuarios WHERE nombre='$nombre' OR email='$email'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Si hay resultados, significa que ya existe un usuario con ese nombre o email
        echo "Error: El nombre o el email ya están registrados.";
    } else {
        // Cifrar la contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

        // Insertar datos en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES ('$nombre', '$email', '$contrasena_hash')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Todos los campos son requeridos";
}

$conn->close();
?>
