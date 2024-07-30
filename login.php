<?php
include 'db_config.php';  // Incluir archivo de configuración de la base de datos

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
            echo "Login exitoso";
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Email no registrado";
    }
} else {
    echo "Todos los campos son requeridos";
}

$conn->close();
?>
