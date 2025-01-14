<?php  
// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "Accounts_Funny"; //nombre del server
$username = "root";  // Cambia según tu configuración
$password = "";  // Cambia según tu configuración

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Validar si los campos no están vacíos
    if (!empty($user) && !empty($email) && !empty($pass)) {
        // Verificar si el nombre de usuario ya está registrado
        $checkUserSql = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $conn->prepare($checkUserSql);
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si el nombre de usuario ya existe, mensaje de error
            echo' <html><head>
            <link rel="icon" href="Imagenes/Favicon.png" type="image/jpeg">
        </head>
      <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
      <p>El Nombre ya esta registrado.</p>
        <p style="color: Blue; text-decoration: underline;">Utiliza otro por favor.</p>
        <script>
    setTimeout(function() {
    window.location.href = "../Account.html";
    }, 3000);  // Redirige después de 2000 ms (2 segundos)
        </script>
    </body></html>';
    } else {
            // Verificar si el correo electrónico ya está registrado
            $checkEmailSql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($checkEmailSql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo' <html><head>
                <link rel="icon" href="Imagenes/Favicon.png" type="image/jpeg">
            </head>
          <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
          <p>El correo electronico ya esta registrado.</p>
            <p style="color: Blue; text-decoration: underline;">Utiliza otro por favor.</p>
            <script>
        setTimeout(function() {
        window.location.href = "../Account.html";
        }, 3000);  // Redirige después de 2000 ms (2 segundos)
            </script>
        </body></html>';
        } else {
                // Cifrar la contraseña
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

                // Insertar datos en la tabla de usuarios
                $sql = "INSERT INTO usuarios (username, email, password) VALUES (:username, :email, :password)";
                $stmt = $conn->prepare($sql);

                // Asociar los valores a los marcadores
                $stmt->bindParam(':username', $user);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);

                // Ejecutar la inserción
                if ($stmt->execute()) {
                    // Registro exitoso, redirigir al link
                    echo' <html><head>
                    <link rel="icon" href="Imagenes/Favicon.png" type="image/jpeg">
                </head>
              <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
              <p>Se creo con exito la cuanta.</p>
                <p style="color: Blue; text-decoration: underline;">Que tengas u feliz dia, tarde o noche.</p>
                <script>
            setTimeout(function() {
            window.location.href = "../Login.html";
            }, 3000);  // Redirige después de 2000 ms (2 segundos)
                </script>
            </body></html>';
                } else {
                    echo '<html><head>
                            <link rel="icon" href="funnny(1).jpeg" type="image/jpeg">
                          </head>
                          <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
                          Error al registrar el usuario.</body></html>';
                }
            }
        }
    } else {
        echo '<html><head>
                <link rel="icon" href="funnny(1).jpeg" type="image/jpeg">
              </head>
              <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
              Todos los campos son obligatorios.</body></html>';
    }
}
?>
<!-- .html -->