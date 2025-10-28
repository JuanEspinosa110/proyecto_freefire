<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

// Verificar sesión de administrador
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrar Salas - Free Fire</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* === ESTILO GENERAL (temática Free Fire) === */
body.salas-body {
  background: url("../../IMG/fondo.jpg") no-repeat center center fixed;
  background-size: cover;
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  color: #fff;
  margin: 0;
  padding-top: 40px;
}

/* Sombra negra translúcida */
.salas-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  z-index: 0;
}

.container.salas-container {
  position: relative;
  z-index: 1;
  max-width: 1200px;
}

/* === Tarjetas de Salas === */
.salas-card {
  background: rgba(0,0,0,0.7);
  border-radius: 12px;
  padding: 15px;
  color: white;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.5);
  transition: transform .2s ease;
}
.salas-card:hover {
  transform: scale(1.03);
}

/* === Botones personalizados === */
.salas-btn {
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  padding: 8px 15px;
  transition: all .2s ease;
}
.salas-btn-primary {
  background-color: #ffcc00;
  color: #000;
}
.salas-btn-primary:hover {
  background-color: #ffdd33;
}
.salas-btn-secondary {
  background-color: #6c757d;
  color: #fff;
}
.salas-btn-secondary:hover {
  background-color: #5a6268;
}
.salas-btn-danger {
  background-color: #ff4444;
  color: #fff;
}
.salas-btn-danger:hover {
  background-color: #ff2222;
}
.salas-alert {
  background: rgba(255,255,255,0.2);
  border: none;
  color: #fff;
  border-radius: 10px;
  text-align: center;
  padding: 15px;
}
</style>
</head>

<body class="salas-body">
<div class="salas-overlay"></div>

<div class="container salas-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-warning">⚙️ Administrar Salas</h2>
    <div>
      <a href="crear_sala.php" class="salas-btn salas-btn-primary me-2">➕ Crear Sala</a>
      <a href="index.php" class="salas-btn salas-btn-secondary">⬅ Volver al Lobby</a>
    </div>
  </div>

  <!-- Listado de salas -->
  <div id="salas-listado" class="row g-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.5/dist/jquery.min.js"></script>
<script>
// Carga las tarjetas HTML de las salas desde ajax_salas.php
function cargarSalas() {
  $.ajax({
    url: "ajax_salas.php",
    method: "GET",
    dataType: "html",
    success: function (data) {
      $("#salas-listado").html(data);
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar salas:", error);
      $("#salas-listado").html('<div class="col-12"><div class="salas-alert">Error al cargar salas. Revisa el servidor.</div></div>');
    }
  });
}

$(document).ready(function(){
  cargarSalas();
  // Actualiza cada 3s. Se usa debounce sencillo evitando llamadas paralelas.
  setInterval(cargarSalas, 3000);
});
</script>
</body>
</html>