<?php
session_start();
require_once("../../DB/conection.php");
$db = new Database();
$con = $db->conectar();

// Recibir datos del formulario
$id_user    = isset($_POST['id_user']) ? (int)$_POST['id_user'] : (int)$_SESSION['id_user'];
$id_enemigo = isset($_POST['id_enemigo']) ? (int)$_POST['id_enemigo'] : null;
$id_arma    = isset($_POST['id_armas']) ? (int)$_POST['id_armas'] : null;
$id_sala    = isset($_POST['id_sala']) ? (int)$_POST['id_sala'] : null;

// Validaciones
if (!$id_user || !$id_sala || !$id_enemigo || !$id_arma) {
    echo "<script>alert('Error: datos incompletos.'); window.history.back();</script>";
    exit();
}

// Validar que ambos jugadores estén en la misma sala
$stmt = $con->prepare("SELECT * FROM usuario_sala WHERE id_sala = :s AND id_user = :u");
$stmt->execute([':s' => $id_sala, ':u' => $id_enemigo]);
if (!$stmt->fetch()) {
    echo "<script>alert('El enemigo no está en la misma sala.'); window.history.back();</script>";
    exit();
}

// Obtener daño del arma
$stmt = $con->prepare("SELECT dano_cuerpo, dano_cabeza FROM armas WHERE id_armas = :id");
$stmt->execute([':id' => $id_arma]);
$arma = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$arma) {
    echo "<script>alert('Arma no encontrada.'); window.history.back();</script>";
    exit();
}

// Calcular daño aleatorio
$dano = rand((int)$arma['dano_cuerpo'], (int)$arma['dano_cabeza']);

// Registrar ataque en detalle_partida
try {
    $stmt = $con->prepare("
        INSERT INTO detalle_partida (id_user, id_sala, id_armas, dano_causado, dano_recibido)
        VALUES (:user, :sala, :arma, :caus, 0)
    ");
    $stmt->execute([
        ':user' => $id_user,
        ':sala' => $id_sala,
        ':arma' => $id_arma,
        ':caus' => $dano
    ]);

    $stmt = $con->prepare("
        INSERT INTO detalle_partida (id_user, id_sala, id_armas, dano_causado, dano_recibido)
        VALUES (:enemigo, :sala, :arma, 0, :rec)
    ");
    $stmt->execute([
        ':enemigo' => $id_enemigo,
        ':sala' => $id_sala,
        ':arma' => $id_arma,
        ':rec' => $dano
    ]);
} catch (Exception $e) {
    echo "<script>alert('Error al registrar ataque: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    exit();
}

// Sumar puntos según el arma
$puntosGanados = match ($id_arma) {
    9 => 1,
    11 => 2,
    13, 14 => 20,
    15, 16 => 10,
    default => 5,
};

$con->prepare("UPDATE usuario SET puntos = puntos + :pts WHERE id_user = :u")
    ->execute([':pts' => $puntosGanados, ':u' => $id_user]);

// Verificar daño total recibido del enemigo
$stmt = $con->prepare("
    SELECT COALESCE(SUM(dano_recibido), 0) AS total
    FROM detalle_partida
    WHERE id_user = :u AND id_sala = :s
");
$stmt->execute([':u' => $id_enemigo, ':s' => $id_sala]);
$totalDano = (int)$stmt->fetchColumn();

// Si llega a 100 de daño → eliminado
if ($totalDano >= 100) {
    $con->prepare("UPDATE usuario_sala SET eliminado = 1 WHERE id_sala = :s AND id_user = :u")
        ->execute([':s' => $id_sala, ':u' => $id_enemigo]);

    $bono = 75;
    $con->prepare("UPDATE usuario SET puntos = puntos + :b WHERE id_user = :u")
        ->execute([':b' => $bono, ':u' => $id_user]);

    echo "<script>alert('🔥 ¡El enemigo fue eliminado! Has ganado +{$bono} puntos.'); window.location='partida.php?id_sala={$id_sala}';</script>";
    exit();
}

echo "<script>alert('Ataque exitoso. Daño causado: {$dano}.'); window.location='partida.php?id_sala={$id_sala}';</script>";
exit();
