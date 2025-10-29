<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../index.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_sala = isset($_GET['id_sala']) ? (int)$_GET['id_sala'] : 0;
if ($id_sala <= 0) die("Sala no encontrada.");

// ----------------------
// 1️⃣ Sacar al usuario de otras salas activas
// ----------------------
$stmt = $pdo->prepare("UPDATE usuario_sala SET eliminado = 1 WHERE id_user = ?");
$stmt->execute([$id_user]);

// ----------------------
// 2️⃣ Insertar al usuario en esta sala si no está
// ----------------------
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario_sala WHERE id_user = ? AND id_sala = ? AND eliminado = 0");
$stmt->execute([$id_user, $id_sala]);
$existe = $stmt->fetchColumn();

if (!$existe) {
    $insert = $pdo->prepare("INSERT INTO usuario_sala (id_user, id_sala, tiempo_entrada, eliminado) VALUES (?, ?, NOW(), 0)");
    $insert->execute([$id_user, $id_sala]);
}

// ----------------------
// 3️⃣ Actualizar cantidad de jugadores activos en la sala
// ----------------------
$update = $pdo->prepare("
    UPDATE sala 
    SET jugadores_actuales = (
        SELECT COUNT(*) 
        FROM usuario_sala 
        WHERE id_sala = ? AND eliminado = 0
    )
    WHERE id_sala = ?
");
$update->execute([$id_sala, $id_sala]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sala de Espera</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: url("../../IMG/fondo.jpg") center/cover no-repeat fixed;
  color:#fff;
  font-family:'Poppins',sans-serif;
}
.overlay { position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:0; }
.container { position:relative; z-index:1; margin-top:60px; text-align:center; }
.card-player {
  background:rgba(0,0,0,0.75);
  border-radius:10px;
  padding:10px;
  margin:10px;
  display:inline-block;
  width:160px;
}
.card-player img {
  width:80px; height:80px;
  border-radius:50%;
  border:2px solid #ffcc00;
}
.btn-salir {
  background-color:#ff4444;
  border:none;
  padding:8px 15px;
  color:#fff;
  border-radius:8px;
}
</style>
</head>
<body>
<div class="overlay"></div>
<div class="container">
  <h2 class="mb-3">Sala #<?= $id_sala ?></h2>
  <h5 id="estado">Esperando jugadores...</h5>

  <div id="jugadores" class="mt-3 d-flex flex-wrap justify-content-center"></div>

  <div class="mt-4">
    <button id="salir" class="btn-salir">Salir de la Sala</button>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  const idSala = <?= $id_sala ?>;
  const $jugadores = $('#jugadores');
  const $estado = $('#estado');

  function actualizarJugadores() {
    $.get('ajax_sala.php', { id_sala: idSala }, function(data){
      try {
        const info = JSON.parse(data);
        $jugadores.html(info.html);
        const count = info.count ?? 0;
        if (count < 5) {
          $estado.text('Esperando jugadores... (' + count + '/5)');
        } else {
          $estado.text('Iniciando partida...');
        }
      } catch(e) {
        console.error('Error JSON:', e, data);
      }
    });
  }

  setInterval(actualizarJugadores, 2000);
  actualizarJugadores();

  $('#salir').on('click', function(){
    if (!confirm('¿Deseas salir de la sala?')) return;
    $.post('salir_sala.php', { id_sala: idSala }, function(){
      window.location.href = 'salas.php';
    });
  });
});
</script>
</body>
</html>
