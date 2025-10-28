<?php
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

$query = $pdo->query("
    SELECT 
        s.id_sala,
        m.nombre AS modo,
        n.nombre AS nivel,
        mp.nombre AS mapa,
        e.nombre AS estado,
        s.jugadores_actuales,
        s.max_jugadores
    FROM sala s
    JOIN modos_juegos m ON s.id_modo_juegos = m.id_modo_juegos
    JOIN niveles n ON s.id_niveles = n.id_niveles
    JOIN mapa mp ON s.id_mapa = mp.id_mapa
    JOIN estado e ON s.id_estado = e.id_estado
    ORDER BY s.fecha_creacion DESC
");
$salas = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Salas - Free Fire</title>
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
      background: rgba(255, 255, 255, 0.49);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(4px);
      padding: 15px;
    }
    .btn {
      border-radius: 8px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Salas Disponibles</h2>
      <div>
        <a href="index.php" class="btn btn-secondary">Volver al Lobby</a>
      </div>
    </div>

    <div class="row g-4">
      <?php if (empty($salas)): ?>
        <div class="col-12 text-center">
          <div class="alert alert-warning bg-dark text-white border-0">No hay salas creadas actualmente.</div>
        </div>
      <?php else: ?>
        <?php foreach ($salas as $sala): ?>
          <div class="col-md-4">
            <div class="card h-100 p-3">
              <h5><?= htmlspecialchars($sala['modo']) ?> (<?= htmlspecialchars($sala['nivel']) ?>)</h5>
              <p>Mapa: <?= htmlspecialchars($sala['mapa']) ?></p>
              <p>Estado: <?= htmlspecialchars($sala['estado']) ?></p>
              <p>Jugadores: <?= $sala['jugadores_actuales'] ?>/<?= $sala['max_jugadores'] ?></p>
              <div class="mt-auto">
                <a href="ver_sala.php?id_sala=<?= $sala['id_sala'] ?>" class="btn btn-primary w-100">Entrar a Sala</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
