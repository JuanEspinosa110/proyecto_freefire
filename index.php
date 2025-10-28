<?php
session_start();
require_once("DB/conection.php");
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];

    // Verificar usuario
    $query = $con->prepare("SELECT * FROM usuario WHERE username = :usuario");
    $query->bindParam(':usuario', $usuario);
    $query->execute();
    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data && password_verify($clave, $data['contrasena'])) {
        // Validar si está bloqueado
        if ($data['id_estado'] == 2) {
            echo "<script>alert('Tu cuenta está bloqueada. Espera la activación del administrador.'); window.location='index.php';</script>";
            exit();
        }

        // Guardar sesión
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_tip_user'] = $data['id_tip_user']; // admin o jugador

        // Redirigir según tipo de usuario
        if ($data['id_tip_user'] == 1) {
          header("Location: modulo/ADMIN/index.php");
        } elseif ($data['id_tip_user'] == 2) {
          header("Location: modulo/USERS/index.php");
        
        } else {
            echo "<script>alert('Tipo de usuario desconocido.'); window.location='index.php';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos.'); window.location='index.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inicio Sesión | Free Fire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
  <video autoplay muted loop id="bg-video">
    <source src="video/videovideoplayback30-intro.mp4.mp4" type="video/mp4">
  </video>

  <div class="login-card p-4">
    <div class="text-center mb-3">
      <img src="img/logo.png" alt="Logo" width="100">
      <h1 class="h4 mt-2">Inicio de Sesión</h1>
    </div>

    <form method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Digite Usuario" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="clave" id="password" placeholder="Ingrese Contraseña" required>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-warning fw-bold w-100">Validar</button>
      </div>
    </form>

    <div class="text-center">
      <a href="recuperar_contra.php">Recuperar Contraseña</a>    
      <a href="registrarme.php" class="ms-2">Registrarme?</a>
    </div>
  </div>
</body>
</html>
