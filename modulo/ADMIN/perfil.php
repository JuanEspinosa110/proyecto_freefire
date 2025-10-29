<?php
session_start();
require_once("../../DB/conection.php");

$db = new Database();
$con = $db->conectar();

// Verificar sesión activa
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit();
}

$id_usuario = $_SESSION['id_user'];

// Obtener datos completos del usuario
$sql = $con->prepare("
    SELECT 
        u.username,
        u.nombre,
        u.correo,
        u.puntos,
        n.nombre AS nivel,
        t.tipo AS tipo_usuario,
        e.nombre AS estado,
        p.nombre AS personaje,
        p.skin
    FROM usuario u
    LEFT JOIN niveles n ON u.id_niveles = n.id_niveles
    LEFT JOIN tip_user t ON u.id_tip_user = t.id_tip_user
    LEFT JOIN estado e ON u.id_estado = e.id_estado
    LEFT JOIN personajes p ON u.Id_personajes = p.Id_personajes
    WHERE u.id_user = ?
");
$sql->execute([$id_usuario]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: url("../../IMG/fondo.jpg") no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      padding-top: 60px;
    }
    .perfil-card {
      background: rgba(0,0,0,0.6);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 10px;
      padding: 20px;
      backdrop-filter: blur(4px);
    }
    .perfil-img {
      width: 160px;
      height: 160px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ffc107;
    }
    .btn-volver {
      position: fixed;
      top: 20px;
      right: 20px;
      background: linear-gradient(45deg, #0d6efd, #6610f2);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 16px;
      font-weight: bold;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-volver:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(102,16,242,0.6);
    }
  </style>
</head>
<body>
<a href="index.php" class="btn-volver">Volver al Lobby</a>

<div class="container text-center">
  <div class="perfil-card mx-auto col-md-6 mt-5">
    <img src="../../<?= htmlspecialchars($usuario['skin'] ?? 'IMG/default.png') ?>" class="perfil-img mb-3" alt="Personaje">
    <h3><?= htmlspecialchars($usuario['username']) ?></h3>
    <p class="text-warning"><?= ucfirst($usuario['tipo_usuario']) ?></p>
    <hr>
    <p><strong>Nombre completo:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
    <p><strong>Nivel:</strong> <?= htmlspecialchars($usuario['nivel']) ?></p>
    <p><strong>Puntos:</strong> <?= htmlspecialchars($usuario['puntos']) ?></p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($usuario['estado']) ?></p>
    <p><strong>Personaje actual:</strong> <?= htmlspecialchars($usuario['personaje']) ?></p>
    <hr>
    <a href="../../logout.php" class="btn btn-danger">Cerrar Sesión</a>
  </div>
</div>
</body>
</html>
