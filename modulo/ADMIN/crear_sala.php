<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../index.php");
    exit;
}

// Modo más robusto: validar referencias y mostrar errores si ocurren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $modo = isset($_POST['id_modo_juegos']) ? (int)$_POST['id_modo_juegos'] : 0;
  $nivel = isset($_POST['id_niveles']) ? (int)$_POST['id_niveles'] : 0;
  $mapa = isset($_POST['id_mapa']) ? (int)$_POST['id_mapa'] : 0;
  $max = isset($_POST['max_jugadores']) ? (int)$_POST['max_jugadores'] : 2;

  $errors = [];
  if ($modo <= 0) $errors[] = 'Modo inválido.';
  if ($nivel <= 0) $errors[] = 'Nivel inválido.';
  if ($mapa <= 0) $errors[] = 'Mapa inválido.';
  if ($max <= 0) $errors[] = 'Máximo de jugadores inválido.';

  // Comprobar que los IDs existan en tablas relacionadas
  try {
    $check = $pdo->prepare('SELECT COUNT(*) FROM modos_juegos WHERE id_modo_juegos = ?');
    $check->execute([$modo]);
    if ($check->fetchColumn() == 0) $errors[] = 'El modo de juego no existe.';

    $check = $pdo->prepare('SELECT COUNT(*) FROM niveles WHERE id_niveles = ?');
    $check->execute([$nivel]);
    if ($check->fetchColumn() == 0) $errors[] = 'El nivel no existe.';

    $check = $pdo->prepare('SELECT COUNT(*) FROM mapa WHERE id_mapa = ?');
    $check->execute([$mapa]);
    if ($check->fetchColumn() == 0) $errors[] = 'El mapa no existe.';
  } catch (Exception $e) {
    $errors[] = 'Error de validación: ' . $e->getMessage();
  }

  if (empty($errors)) {
    try {
      $sql = "INSERT INTO sala (id_modo_juegos, id_niveles, id_mapa, id_estado, jugadores_actuales, max_jugadores, fecha_creacion)
          VALUES (:modo, :nivel, :mapa, 1, 0, :max, NOW())";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':modo' => $modo,
        ':nivel' => $nivel,
        ':mapa' => $mapa,
        ':max' => $max
      ]);
      header("Location: salas.php");
      exit;
    } catch (PDOException $e) {
      $errors[] = 'Error al crear sala: ' . $e->getMessage();
    }
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Sala</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:url('../../IMG/fondo.jpg') center/cover no-repeat; min-height:100vh; color:white; padding:30px;">
<div class="container">
  <h2 class="mb-4">Crear Nueva Sala (ADMIN)</h2>
  <form method="post" class="bg-dark p-4 rounded" style="max-width:500px;">
    <div class="mb-3">
      <label class="form-label">Modo de juego (id_modo_juegos):</label>
      <input type="number" name="id_modo_juegos" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Nivel (id_niveles):</label>
      <input type="number" name="id_niveles" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mapa (id_mapa):</label>
      <input type="number" name="id_mapa" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Máximo de jugadores:</label>
      <input type="number" name="max_jugadores" class="form-control" value="5" required>
    </div>
    <button type="submit" class="btn btn-warning w-100 fw-bold">Crear Sala</button>
    <a href="salas.php" class="btn btn-secondary w-100 mt-2">Volver</a>
  </form>
</div>
</body>
</html>
