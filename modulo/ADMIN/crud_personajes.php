<?php
session_start();
require_once(__DIR__ . "/../../DB/conection.php");

$db = new Database();
$con = $db->conectar();

// 🔒 Solo administradores
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

// 🧠 Leer personajes
$query = $con->query("SELECT * FROM personajes ORDER BY Id_personajes ASC");
$personajes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Personajes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS interno para organizar la vista -->
  <style>
    body.fondo-personajes {
      background-color: #121212;
      color: #fff;
      font-family: Arial, sans-serif;
    }

    .personajes-contenedor {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .personajes-titulo {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2rem;
      letter-spacing: 1px;
    }

    .row.g-4 {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .card-personaje {
      background-color: #1e1e1e;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .card-personaje:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 16px rgba(0,0,0,0.7);
    }

    .personajes-imagen {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .card-body {
      padding: 15px;
      text-align: center;
      width: 100%;
    }

    .nombre-personaje {
      font-size: 1.2rem;
      margin: 10px 0 5px 0;
      color: #f0f0f0;
    }

    .descripcion-personaje {
      font-size: 0.9rem;
      color: #ccc;
      min-height: 40px; /* para que todas las cards tengan altura uniforme */
    }

    .acciones-personaje {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
    }

    .text-end.mb-4 {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 30px !important;
    }

    .btn {
      min-width: 120px;
    }
  </style>
</head>
<body class="fondo-personajes">

<div class="personajes-contenedor">
  <h2 class="personajes-titulo">ADMINISTRAR PERSONAJES</h2>

  <div class="text-end mb-4">
    <a href="form_personaje.php" class="btn btn-success">Agregar nuevo personaje</a>
    <a href="index.php" class="btn btn-secondary">⬅ Volver al Lobby</a>
  </div>

  <div class="row g-4">
    <?php foreach ($personajes as $p): ?>
      <div class="col-md-4">
        <div class="card-personaje">
          <img src="../../<?= htmlspecialchars($p['skin']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="personajes-imagen">
          <div class="card-body">
            <h5 class="nombre-personaje"><?= htmlspecialchars($p['nombre']) ?></h5>
            <p class="descripcion-personaje"><?= htmlspecialchars($p['descripcion']) ?></p>
            <div class="acciones-personaje">
              <a href="formulario_personajes.php?editar=<?= $p['Id_personajes'] ?>" class="btn btn-warning btn-sm">Editar</a>
              <a href="eliminar_personaje.php?id=<?= $p['Id_personajes'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este personaje?')">Eliminar</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
