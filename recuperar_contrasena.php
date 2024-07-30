<?php
include 'db_config.php';  // Incluir archivo de configuración de la base de datos

// Obtener datos del POST
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';

// Verificar que el email no esté vacío
if (!empty($email)) {
    // Buscar el usuario por email
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generar nueva contraseña aleatoria
        $nuevaContrasena = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

        // Actualizar la nueva contraseña en la base de datos
        $updateSql = "UPDATE usuarios SET contrasena='$nuevaContrasenaHash' WHERE email='$email'";
        if ($conn->query($updateSql) === TRUE) {
            // Enviar correo electrónico con la nueva contraseña
            $to = $email;
            $subject = "Recuperación de contraseña";
            $message = "Su nueva contraseña es: $nuevaContrasena";
            $headers = "From: tuemail@tuempresa.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "Nueva contraseña enviada a su correo electrónico";
            } else {
                echo "Error al enviar el correo electrónico";
            }
        } else {
            echo "Error al actualizar la contraseña: " . $conn->error;
        }
    } else {
        echo "Email no registrado";
    }
} else {
    echo "Email es requerido";
}

$conn->close();
?>
