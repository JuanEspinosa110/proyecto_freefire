<?php
session_start();
require_once("../../DB/conection.php");

$db = new Database();
$con = $db->conectar();

// Validar sesión
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../index.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Obtener el id_sala actual
$id_sala = $_GET['id_sala'] ?? $_SESSION['id_sala_actual'] ?? null;
if (!$id_sala) {
    die("No se encontró la sala activa.");
}

// Obtener información de la sala
$querySala = $con->prepare("
    SELECT s.id_sala, s.id_mapa, s.id_niveles, n.nombre AS nivel
    FROM sala s
    JOIN niveles n ON s.id_niveles = n.id_niveles
    WHERE s.id_sala = :id_sala
");
$querySala->bindParam(':id_sala', $id_sala);
$querySala->execute();
$sala = $querySala->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    die("No existe la sala seleccionada.");
}

// Jugadores de la sala (según usuario_sala)
$queryJugadores = $con->prepare("
    SELECT u.id_user, u.username, u.puntos, u.id_niveles
    FROM usuario_sala us
    JOIN usuario u ON us.id_user = u.id_user
    WHERE us.id_sala = :id_sala
");
$queryJugadores->bindParam(':id_sala', $id_sala);
$queryJugadores->execute();
$jugadores = $queryJugadores->fetchAll(PDO::FETCH_ASSOC);

// Armas disponibles según nivel
$nivel = $sala['id_niveles'];
if ($nivel == 1) {
    $queryArmas = $con->query("SELECT * FROM armas WHERE id_tipo_arma IN (1,2)");
} else {
    $queryArmas = $con->query("SELECT * FROM armas");
}
$armas = $queryArmas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Partida en curso</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #121212; color: white; }
.card { background-color: #1f1f1f; border: none; color: white; }
.weapon-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
.player-card { padding: 10px; border: 1px solid #555; border-radius: 8px; margin-bottom: 10px; }
</style>
</head>
<body class="container py-4">
<h2 class="text-center mb-3">🔥 Batalla en curso</h2>
<h5 class="text-center text-warning">Sala #<?= $sala['id_sala'] ?> | Nivel: <?= ucfirst($sala['nivel']) ?></h5>

<div class="row mt-4">
    <div class="col-md-4">
        <h4>Tus armas</h4>
        <form id="formAtaque" method="POST" action="procesar_ataque.php">
            <input type="hidden" name="id_sala" value="<?= $sala['id_sala'] ?>">
            <input type="hidden" name="id_user" value="<?= $id_user ?>">
            <div class="mb-3">
                <select name="id_armas" class="form-select" required>
                    <option value="">-- Selecciona un arma --</option>
                    <?php foreach ($armas as $arma): ?>
                        <option value="<?= $arma['id_armas'] ?>">
                            <?= $arma['nombre'] ?> (Cabeza: <?= $arma['dano_cabeza'] ?> / Cuerpo: <?= $arma['dano_cuerpo'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Selecciona enemigo:</label>
                <select name="id_enemigo" class="form-select" required>
                    <option value="">-- Jugadores --</option>
                    <?php foreach ($jugadores as $j): if ($j['id_user'] != $id_user): ?>
                        <option value="<?= $j['id_user'] ?>"><?= $j['username'] ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>

            <button class="btn btn-danger w-100">Atacar 🔫</button>
        </form>
    </div>

    <div class="col-md-8">
        <h4>Jugadores en sala</h4>
        <div class="row">
            <?php foreach ($jugadores as $j): ?>
                <div class="col-md-6">
                    <div class="player-card">
                        <strong><?= $j['username'] ?></strong><br>
                        Puntos: <?= $j['puntos'] ?><br>
                        Nivel: <?= $j['id_niveles'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
