<?php
// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "Accounts_Funny";  // Nombre de la base de datos
$username = "root";  // Usuario de la base de datos
$password = "";  // Contraseña de la base de datos

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $user = $_POST['username'];
    $email = $_POST['email'];
    $new_pass = $_POST['new_password'];

    // Validar si los campos fueron completados
    if (!empty($user) && !empty($email) && !empty($new_pass)) {
        // Verificar si el usuario y el email existen
        $sql = "SELECT * FROM usuarios WHERE username = :username AND email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Si se encontró el usuario
        if ($stmt->rowCount() > 0) {
            // Cifrar la nueva contraseña
            $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $update_sql = "UPDATE usuarios SET password = :password WHERE username = :username AND email = :email";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':password', $hashed_password);
            $update_stmt->bindParam(':username', $user);
            $update_stmt->bindParam(':email', $email);

            // Mensaje para mostrar en la página
            //el html es para el favicon osea logo arriba en la pagina (no eliminar)
            echo '<div style="background-color: black; color: white; text-align: center; padding: 50px; font-size: 20px;">';

            if ($update_stmt->execute()) {
                echo "Contraseña actualizada con éxito.";
                header('Location: ../login.html');  // Cambia '#' por la URL del sitio
                exit();
            } else {
                echo '<html><head>
                    <link rel="icon" href="funnny(1).jpeg" type="image/jpeg">
                    </head>
                <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
                 Error al actualizar la contraseña.';
            }
        } else {
            echo '<html><head>
                    <link rel="icon" href="funnny(1).jpeg" type="image/jpeg">
                    </head>
                <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
                 Usuario o correo electrónico no encontrado.';
        }
    } else {
        echo '<html><head>
                <link rel="icon" href="funnny(1).jpeg" type="image/jpeg">
                </head>
            <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
             Por favor, complete todos los campos.';
    }
}
?>
