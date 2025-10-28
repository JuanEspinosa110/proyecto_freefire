<?php
session_start();
require_once("../../DB/conection.php");

$db = new Database();
$con = $db->conectar();

// Verificar sesión
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

$id_usuario = $_SESSION['id_user'];

// Traer datos del usuario y su personaje
$sql = $con->prepare("
    SELECT u.username, u.puntos, u.id_niveles, p.skin
    FROM usuario u
    LEFT JOIN personajes p ON u.Id_personajes = p.Id_personajes
    WHERE u.id_user = ?
");
$sql->execute([$id_usuario]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Free Fire Lobby - Usuario</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tipografía gamer -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">
  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="../../CSS/lobby.css">
</head>

<body class="fondo-lobby">

  <!-- 🎬 Video de fondo -->
  <video autoplay muted loop id="bg-video">
    <source src="../../videos/Pelancaran Free Fire Max_ 28 September _ Garena Free Fire Malaysia.mp4" type="video/mp4">
  </video>
  <div class="overlay"></div>

  <!-- 🔝 Barra superior -->
  <nav class="navbar fixed-top d-flex justify-content-between align-items-center px-4">
    <div class="player-info text-light">
      <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($usuario['username']); ?></h5>
      <small>
        Nivel: <?php echo htmlspecialchars($usuario['id_niveles']); ?> |
        Puntos: <?php echo htmlspecialchars($usuario['puntos']); ?>
      </small>
    </div>

    <!-- Botón de Cerrar Sesión (arriba a la derecha) -->
    <button class="logout-icon" onclick="window.location.href='../../logout.php'">
      <i class="bi bi-box-arrow-right">CERRAR SESIÓN</i>
    </button>
  </nav>

  <!-- 🎮 Menú lateral -->
  <div class="menu-lateral">
    <!-- Aquí quitamos USUARIOS y colocamos PERFIL -->
    <button class="btn-menu" onclick="window.location.href='perfil.php'">
      <i class="bi bi-person-circle"></i> PERFIL
    </button>

    <button class="btn-menu" onclick="window.location.href='personajes.php'">
      <i class="bi bi-crosshair"></i> PERSONAJES
    </button>

    <button class="btn-menu" onclick="window.location.href='salas.php'">
      <i class="bi bi-controller"></i> SALAS
    </button>

    <button class="btn-menu" onclick="window.location.href='armas.php'">
      <i class="bi bi-crosshair"></i> ARMAS
    </button>

    <button class="btn-menu" onclick="window.location.href='mapas.php'">
      <i class="bi bi-map-fill"></i> MAPAS
    </button>
  </div>

  <!-- 🧍‍♂️ Personaje -->
  <?php
    $query = $con->prepare("
      SELECT p.skin 
      FROM usuario u
      JOIN personajes p ON u.Id_personajes = p.Id_personajes
      WHERE u.id_user = ?
    ");
    $query->execute([$id_usuario]);
    $personaje_actual = $query->fetch(PDO::FETCH_ASSOC);
  ?>

  <!-- 🧍‍♂️ Contenedor del personaje -->
  <div class="character-container">
    <img 
      src="../../<?= htmlspecialchars($personaje_actual['skin'] ?? 'IMG/default.png') ?>" 
      alt="Personaje" 
      class="personaje">
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
