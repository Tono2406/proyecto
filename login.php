<?php
include 'db_config.php';  // Incluir archivo de configuración de la base de datos

header('Content-Type: application/json');

// Obtener datos del POST
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$contrasena = isset($_POST['contrasena']) ? $conn->real_escape_string($_POST['contrasena']) : '';

// Verificar que los datos no estén vacíos
if (!empty($email) && !empty($contrasena)) {
    // Buscar el usuario por email
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row['contrasena'])) {
            $userName = $row['nombre']; // Supongamos que el nombre está en el campo 'nombre'

            // Preparar y enviar la respuesta JSON
            echo json_encode(array(
                "message" => "Login exitoso",
                "userName" => $userName
            ));
        } else {
            // Contraseña incorrecta
            echo json_encode(array("message" => "Contraseña incorrecta"));
        }
    } else {
        // Email no registrado
        echo json_encode(array("message" => "Email no registrado"));
    }
} else {
    // Campos vacíos
    echo json_encode(array("message" => "Todos los campos son requeridos"));
}

$conn->close();
?>
