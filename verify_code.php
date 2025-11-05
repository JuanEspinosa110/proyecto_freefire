
<?php
require_once("db/conection.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (isset($_POST['enviar'])) {
    $codigo = $_POST['codigo'];

    if (empty($codigo)) {
        echo "<script>alert('Campo vacío');</script>";
        exit();
    }

    if (isset($_SESSION['code']) && $codigo == $_SESSION['code']) {
        header("Location: changepass.php");
        exit();
    } else {
        echo "<script>alert('Código incorrecto');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Código de Verificación | Free Fire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <h2 class="h5 mt-2">Código de Verificación</h2>
    </div>

    <form action="" method="POST">
      <div class="mb-3">
        <label for="codigo" class="form-label">Ingrese el código:</label>
        <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Código recibido" required>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" name="enviar" class="btn btn-warning fw-bold">Continuar</button>
        <a href="index.php" class="btn btn-secondary">Volver</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

