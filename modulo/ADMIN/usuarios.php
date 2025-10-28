<?php
session_start();
require_once("../../DB/conection.php");
$db = new Database();
$con = $db->conectar();

// 🗑️ Si se pasa un ID por la URL, se elimina
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];

    // Ejecuta la eliminación
    $sql = $con->prepare("DELETE FROM usuario WHERE id_user = ?");
    $sql->execute([$idEliminar]);

    // Muestra mensaje y recarga la página
    echo "<script>alert('Usuario eliminado correctamente'); window.location='usuarios.php';</script>";
    exit();
}

// 🔹 Consultar lista de usuarios
$query = $con->prepare("SELECT id_user, username, correo, puntos, id_estado, ultima_conexion FROM usuario ORDER BY id_user ASC");
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);


$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

// ⚠️ Verificar usuarios inactivos (más de 10 días)
$alerta = $con->prepare("
    SELECT COUNT(*) AS inactivos 
    FROM usuario
    WHERE id_estado = 1
    AND DATEDIFF(NOW(), ultima_conexion) >= 10
");
$alerta->execute();
$data_alerta = $alerta->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios - Panel del Administrador</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tipografía gamer -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">

  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="../../CSS/lobby.css">
</head>

<body>

  <body>
  <!-- Fondo global -->
  <div class="background-overlay"></div>


  <!-- 🔝 Botón de regresar al lobby -->
  <button class="btn btn-warning position-fixed top-0 end-0 m-4 fw-bold" onclick="window.location.href='index.php'">
    <i class="bi bi-arrow-left-circle me-2"></i> Volver al Lobby
  </button>

  <!-- 📋 CONTENIDO PRINCIPAL -->
  <div class="container mt-5">
    <h1 class="text-warning text-center mb-4">USUARIOS REGISTRADOS</h1>

    <!-- 🔔 Alerta automática -->
    <?php if ($data_alerta['inactivos'] > 0): ?>
      <div class="alert alert-warning text-center fw-bold">
        Hay <?= $data_alerta['inactivos'] ?> usuarios con más de 10 días de inactividad.
      </div>
    <?php else: ?>
      <div class="alert alert-success text-center fw-bold">
        No hay usuarios inactivos por más de 10 días.
      </div>
    <?php endif; ?>

    <!-- 🧾 Tabla de usuarios -->
    <div class="table-responsive">
      <table class="table table-dark table-striped table-hover align-middle text-center">
        <thead class="table-warning text-dark">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Puntos</th>
            <th>Última Conexión</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['id_user']) ?></td>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['correo']) ?></td>
              <td><?= htmlspecialchars($u['puntos']) ?></td>
              <td><?= htmlspecialchars($u['ultima_conexion']) ?></td>
              <td>
                <?= $u['id_estado'] == 1 ? 'Activo ' : ($u['id_estado'] == 2 ? 'Bloqueado ' : 'Desconocido') ?>
              </td>
              <td>
                <a href="actualizar_user.php?id=<?= $u['id_user'] ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="usuarios.php?eliminar=<?= $u['id_user'] ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                    <i class="bi bi-trash-fill"></i>
                </a>

                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </div>
</body>
</html>
