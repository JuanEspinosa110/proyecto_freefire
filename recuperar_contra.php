<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

require_once("db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (isset($_POST['enviar'])) {
    $elEmail = $_POST['input_correo'];

    if (empty($elEmail)) {
        echo "<script>alert('El campo email está vacío');</script>";
        die();
    }

    // Buscar si existe el correo en la tabla usuario
    $Cemail = $con->prepare("SELECT correo FROM usuario WHERE correo = :correo");
    $Cemail->bindParam(":correo", $elEmail);
    $Cemail->execute();
    $Cenviar = $Cemail->fetchColumn();

    // Traer todos los datos del usuario
    $usuario = $con->prepare("SELECT * FROM usuario WHERE correo = :correo");
    $usuario->bindParam(":correo", $elEmail); // ✅ corregido
    $usuario->execute();
    $usuario = $usuario->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Generar un código aleatorio
        $numero_aleatorio = rand(1000, 9999);

        $_SESSION['usuario'] = $usuario['documento']; // documento del usuario
        $_SESSION['code'] = $numero_aleatorio;

        if ($Cenviar) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'didierreyes003@gmail.com'; // tu correo
                $mail->Password   = 'ahchqdcfxwedqfju';         // clave de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('didierreyes003@gmail.com', 'Reyes');
                $mail->addAddress($Cenviar);

                $mail->isHTML(true);
                $mail->Subject = 'CONTRA NUEVA - Reestablecer contraseña';
                $mail->Body    = "Su código para restablecer la contraseña es el siguiente: <b>" . $_SESSION['code'] . "</b>";
                $mail->AltBody = "Su código es: " . $_SESSION['code'];

                $mail->send();

                header("Location: verify_code.php");
                exit();
            } catch (Exception $e) {
                echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "<script>alert('Correo no encontrado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña | Free Fire</title>
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="css/style.css">
  
  <style>
    body {
      background: url("img/fondo2.jpg") no-repeat center center fixed;
      background-size: cover;
    }
  </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">


  <div class="login-card p-4">
    <div class="text-center mb-3">
      <img src="img/logo.png" alt="Logo" width="100">
      <h2 class="h5 mt-2">Recuperar Contraseña</h2>
    </div>

    <form action="" method="POST">

      <div class="mb-3">
        <label for="input_email" class="form-label">Correo</label>
        <input type="email" name="input_correo" id="input_correo" class="form-control" placeholder="Ingrese su correo" required>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" name="enviar" class="btn btn-warning fw-bold">Enviar Código</button>
        <a href="index.php" class="btn btn-secondary">Volver</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
