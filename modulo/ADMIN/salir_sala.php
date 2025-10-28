<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit;
}

$id_user = (int)$_SESSION['id_user'];
$id_sala = isset($_POST['id_sala']) ? (int)$_POST['id_sala'] : (int)($_GET['id_sala'] ?? 0);

if (!$id_sala) {
    echo "error";
    exit;
}

// Borrar registro en sala_jugadores
$pdo->prepare("DELETE FROM sala_jugadores WHERE id_user = ? AND id_sala = ?")->execute([$id_user, $id_sala]);

// Actualizar contador en tabla sala (sin depender de valor antiguo)
$pdo->prepare("UPDATE sala SET jugadores_actuales = (SELECT COUNT(*) FROM sala_jugadores WHERE id_sala = ?) WHERE id_sala = ?")
    ->execute([$id_sala, $id_sala]);

echo "ok";
exit;
