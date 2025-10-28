<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../index.php");
    exit();
}
$id_user = (int)$_SESSION['id_user'];
$id_sala = isset($_GET['id_sala']) ? (int)$_GET['id_sala'] : null;
if (!$id_sala) die("Sala no encontrada.");

// Registrar entrada si no existe en sala_jugadores
$stmt = $pdo->prepare("SELECT 1 FROM sala_jugadores WHERE id_user = ? AND id_sala = ?");
$stmt->execute([$id_user, $id_sala]);
if (!$stmt->fetch()) {
    $pdo->prepare("INSERT INTO sala_jugadores (id_user, id_sala) VALUES (?, ?)")->execute([$id_user, $id_sala]);
    // Actualizar contador en tabla sala para mantener sincronía (opcional)
    $pdo->prepare("UPDATE sala SET jugadores_actuales = (SELECT COUNT(*) FROM sala_jugadores WHERE id_sala = ?) WHERE id_sala = ?")
        ->execute([$id_sala, $id_sala]);
}

// Obtener info del mapa para el fondo (usa tabla mapa.imagen)
$stmt = $pdo->prepare("SELECT s.id_mapa, m.imagen AS mapa_imagen FROM sala s LEFT JOIN mapa m ON s.id_mapa = m.id_mapa WHERE s.id_sala = ?");
$stmt->execute([$id_sala]);
$sala_info = $stmt->fetch(PDO::FETCH_ASSOC);
$mapa_img = $sala_info['mapa_imagen'] ?? 'fondo.jpg';
// Normalizar ruta del mapa para usar desde este archivo (modulo/ADMIN/ver_sala.php)
if (preg_match('/^https?:\\/\\//', $mapa_img)) {
    $mapa_path = $mapa_img;
} else {
    $mapa = ltrim($mapa_img, '/');
    // Si la ruta ya incluye IMG/ la respetamos; si no, la consideramos dentro de IMG/
    if (stripos($mapa, 'img/') === 0) {
        $mapa_path = '../../' . $mapa; // e.g. IMG/bermuda.jpeg
    } else {
        $mapa_path = '../../IMG/' . $mapa; // e.g. bermuda.jpeg -> ../../IMG/bermuda.jpeg
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Sala de Espera - Sala #<?= htmlspecialchars($id_sala) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.5/dist/jquery.min.js"></script>
<style>
/* fondo - la ruta en la BD ya incluye /img/mapas/..., hacemos un fallback si es ruta relativa */
body.salas-body {
  background: url("<?= htmlspecialchars($mapa_path) ?>") center/cover no-repeat fixed;
  font-family:Poppins, sans-serif;
  color:#fff;
  min-height:100vh;
  position:relative;
  margin:0;
}
.overlay { position:absolute;inset:0;background:rgba(0,0,0,0.6);z-index:1; }
.container.salas-container{position:relative;z-index:2;padding-top:60px;max-width:900px;margin:0 auto;text-align:center}
.salas-player-card{background:rgba(0,0,0,0.75);border-radius:10px;padding:12px;margin:8px;width:170px}
.salas-player-card img{width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #ffd700;margin-bottom:6px}
#salas-timer{font-size:2.6rem;margin:8px 0}
.btn-salir{background:#ff4444;border:none;color:#fff;padding:10px 18px;border-radius:10px}
</style>
</head>
<body class="salas-body">
<div class="overlay"></div>
<div class="container salas-container">
  <h2>Sala de Espera - Sala #<?= htmlspecialchars($id_sala) ?></h2>
  <div id="salas-mensaje">Esperando jugadores...</div>
  <div id="salas-timer">60</div>

  <div id="salas-jugadores" class="d-flex flex-wrap justify-content-center mt-4">
    <!-- Jugadores iniciales inyectados por AJAX también -->
  </div>

  <button id="salir-sala" class="btn-salir mt-3">Salir de la Sala</button>
</div>

<script>
let timer = 60;
function actualizarSala(){
  // Usar getJSON para parseo automático
  $.getJSON('ajax_sala.php', { id_sala: <?= $id_sala ?> })
    .done(function(info){
      $('#salas-jugadores').html(info.jugadores_html);
      $('#salas-mensaje').text(info.mensaje);
      if (info.finalizar) {
        if (info.alerta) alert(info.alerta);
        window.location.href = 'salas.php';
      }
    })
    .fail(function(xhr, status, err){
      console.error('Error AJAX ajax_sala:', err);
    });
}
function iniciarTimer(){
  $('#salas-timer').text(timer);
  var t = setInterval(function(){
    if (timer > 0) {
      timer--;
      $('#salas-timer').text(timer);
    } else {
      clearInterval(t);
      $('#salas-mensaje').text('¡Iniciando partida!');
      // aquí podrías redirigir a partida.php
    }
  }, 1000);
}
$(function(){
  actualizarSala(); iniciarTimer();
  setInterval(actualizarSala, 2000);
  $('#salir-sala').click(function(){
    $.post('salir_sala.php', { id_sala: <?= $id_sala ?> })
      .done(function(){ window.location.href = 'salas.php'; })
      .fail(function(){ alert('No se pudo salir de la sala. Intenta de nuevo.'); });
  });
});
</script>
</body>
</html>