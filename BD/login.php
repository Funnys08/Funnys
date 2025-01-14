<?php
// Datos de conexión a la base de datos
$host = "localhost";
$dbname = "Accounts_Funny";  // Nombre de la base de datos
$username = "root";  // Usuario de la base de datos
$password = ""; 

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Validar si los campos fueron completados todos
    if (!empty($user) && !empty($email) && !empty($pass)) {
        // Verificar si el usuario y el email existen
        $sql = "SELECT * FROM usuarios WHERE username = :username AND email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Si se encontró un usuario con el nombre de usuario y el correo electrónico
        if ($stmt->rowCount() > 0) {
            // Obtener los datos del usuario
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña
            if (password_verify($pass, $user_data['password'])) {
                // Redirigir si todo coincide (ruta relativa hacia la carpeta de éxito)
                header('Location: ../#.html');
                exit();
            } else {
                // Redirigir a la página de restablecimiento de contraseña
                echo' <html><head>
                    <link rel="icon" type="image/x-icon" href="Imagenes/Favicon.ico">
                    </head>
                  <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
                  <p>La contraseña esta incorecta.</p>
                    <p style="color: red; text-decoration: underline;">Cambiar Contraseña.</p>
                    <script>
                setTimeout(function() {
                window.location.href = "../password.html";
                }, 3000);  // Redirige después de 2000 ms (2 segundos)
                    </script>
                </body></html>';
            }
        } else {
            // Redirigir a la página de creación de cuenta
                echo' <html><head>
                        <link rel="icon" href="Imagenes/Favicon.png" type="image/jpeg">
                    </head>
                  <body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: white; text-align: center; font-size: 18px; padding: 20px;">
                  <p>La cuenta no existe.</p>
                    <p style="color: Blue; text-decoration: underline;">Crear cuanta.</p>
                    <script>
                setTimeout(function() {
                window.location.href = "../Account.html";
                }, 3000);  // Redirige después de 2000 ms (2 segundos)
                    </script>
                </body></html>';
        }
    } else {
        // Redirigir a la página de inicio de sesión si faltan datos
        header('Location: ../login.php'); // Vuelve a cargar login.php
        exit();
    }
}
?>
