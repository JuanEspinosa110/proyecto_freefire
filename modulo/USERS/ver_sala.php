<?php
require '../../DB/conection.php';
session_start();

$id_sala = $_GET['id_sala'] ?? null;
if (!$id_sala) {
    die("Sala no encontrada");
}

$db = new Database();
$pdo = $db->conectar();

// Información de la sala
$sql = $pdo->prepare("
    SELECT s.id_sala, m.nombre AS modo, n.nombre AS nivel, mp.nombre AS mapa, s.jugadores_actuales, s.max_jugadores
    FROM sala s
    JOIN modos_juegos m ON s.id_modo_juegos = m.id_modo_juegos
    JOIN niveles n ON s.id_niveles = n.id_niveles
    JOIN mapa mp ON s.id_mapa = mp.id_mapa
    WHERE s.id_sala = ?
");
$sql->execute([$id_sala]);
$sala = $sql->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    die("La sala no existe");
}

// Jugadores en la sala
$stmtJugadores = $pdo->prepare("
    SELECT u.id_user, u.username 
    FROM usuario_sala us
    JOIN usuario u ON us.id_user = u.id_user
    WHERE us.id_sala = ?
");
$stmtJugadores->execute([$id_sala]);
$jugadores = $stmtJugadores->fetchAll(PDO::FETCH_ASSOC);

// Si el usuario presiona “Empezar partida”
if (isset($_POST['empezar'])) {
    // Guardamos la sala en sesión (para que partida.php sepa cuál es)
    $_SESSION['id_sala_actual'] = $id_sala;

    // Redirigir a partida.php con el id_sala
    header("Location: partida.php?id_sala=" . $id_sala);
    exit();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ver Sala</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url("../../IMG/fondo.jpg") no-repeat center center fixed;
      background-size: cover;
      color: white;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      padding-top: 40px;
    }
    .card {
      background: rgba(255, 255, 255, 0.46);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(4px);
      padding: 15px;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <h2 class="mb-4">Sala: <?= htmlspecialchars($sala['modo']) ?> - <?= htmlspecialchars($sala['mapa']) ?></h2>

    <div class="card p-4 mb-4">
      <p><strong>Nivel:</strong> <?= htmlspecialchars($sala['nivel']) ?></p>
      <p><strong>Jugadores:</strong> <?= $sala['jugadores_actuales'] ?>/<?= $sala['max_jugadores'] ?></p>
    </div>

    <div class="card p-4 mb-4">
      <h4>Jugadores en la sala</h4>
      <?php if (empty($jugadores)): ?>
        <p class="text-muted">Aún no hay jugadores en esta sala.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($jugadores as $j): ?>
            <li class="list-group-item bg-transparent text-white border-secondary">
              <?= htmlspecialchars($j['username']) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-center gap-2">
      <a href="unirse_sala.php?id_sala=<?= $sala['id_sala'] ?>" class="btn btn-success">Unirse a la Sala</a>
      <a href="salas.php" class="btn btn-secondary">Volver</a>
      <form method="POST" style="display:inline;">
        <button type="submit" name="empezar" class="btn btn-danger">Empezar partida</button>
      </form>
    </div>
  </div>
</body>
</html>
