<?php
require '../../DB/conection.php';
session_start();

$id_sala = $_GET['id_sala'] ?? null;
$id_user = $_SESSION['id_user'] ?? 1110495789;

if (!$id_sala) {
    die("Error: sala no especificada.");
}

$db = new Database();
$pdo = $db->conectar();

# Verificar si ya está en la sala
$stmt = $pdo->prepare("SELECT * FROM usuario_sala WHERE id_user = ? AND id_sala = ?");
$stmt->execute([$id_user, $id_sala]);
$ya_esta = $stmt->fetch();

if (!$ya_esta) {
    # Insertar al jugador
    $pdo->prepare("INSERT INTO usuario_sala (id_user, id_sala) VALUES (?, ?)")->execute([$id_user, $id_sala]);
    # Incrementar número de jugadores
    $pdo->prepare("UPDATE sala SET jugadores_actuales = jugadores_actuales + 1 WHERE id_sala = ?")->execute([$id_sala]);
}

header("Location: ver_sala.php?id_sala=" . $id_sala);
exit;
