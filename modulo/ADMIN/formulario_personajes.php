<?php
session_start();
require_once(__DIR__ . "/../../DB/conection.php");

$db = new Database();
$con = $db->conectar();

// Solo admin
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

$editar = null;

// Si se edita un personaje
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $con->prepare("SELECT * FROM personajes WHERE Id_personajes=?");
    $stmt->execute([$id]);
    $editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Guardar cambios o nuevo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['Id_personajes'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $skin = $_POST['skin_actual'] ?? '';

    // Subir imagen
    if (!empty($_FILES['skin']['name'])) {
        $nombreArchivo = basename($_FILES['skin']['name']);
        $rutaDestino = "../../IMG/" . $nombreArchivo;
        if (move_uploaded_file($_FILES['skin']['tmp_name'], $rutaDestino)) {
            $skin = "IMG/" . $nombreArchivo;
        }
    }

    if ($id) {
        $sql = $con->prepare("UPDATE personajes SET nombre=?, descripcion=?, skin=? WHERE Id_personajes=?");
        $sql->execute([$nombre, $descripcion, $skin, $id]);
    } else {
        $sql = $con->prepare("INSERT INTO personajes (nombre, descripcion, skin) VALUES (?, ?, ?)");
        $sql->execute([$nombre, $descripcion, $skin]);
    }

    header("Location: crud_personajes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $editar ? 'Editar Personaje' : 'Agregar Personaje' ?></title>
  <link rel="stylesheet" href="../../CSS/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="fondo-personajes">

<div class="personajes-contenedor">
  <h2 class="personajes-titulo"><?= $editar ? 'Editar Personaje' : 'Agregar Nuevo Personaje' ?></h2>

  <form method="POST" enctype="multipart/form-data" class="formulario-personaje">
    <input type="hidden" name="Id_personajes" value="<?= $editar['Id_personajes'] ?? '' ?>">
    <input type="hidden" name="skin_actual" value="<?= $editar['skin'] ?? '' ?>">

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($editar['nombre'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control"><?= htmlspecialchars($editar['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagen del personaje</label>
      <input type="file" name="skin" class="form-control">
      <?php if ($editar && $editar['skin']): ?>
        <p class="mt-2 small">Imagen actual:</p>
        <img src="../../<?= htmlspecialchars($editar['skin']) ?>" alt="Personaje" class="imagen-miniatura">
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success"><?= $editar ? 'Actualizar' : 'Agregar' ?></button>
      <a href="crud_personajes.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

</body>
</html>
