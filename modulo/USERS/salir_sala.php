<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

$id_user = $_SESSION['id_user'] ?? null;
$id_sala = $_POST['id_sala'] ?? null;

if ($id_user && $id_sala) {
    $stmt = $pdo->prepare("UPDATE usuario_sala SET eliminado = 1 WHERE id_user = ? AND id_sala = ?");
    $stmt->execute([$id_user, $id_sala]);

    $pdo->prepare("UPDATE sala 
                   SET jugadores_actuales = (SELECT COUNT(*) FROM usuario_sala WHERE id_sala = ? AND eliminado = 0)
                   WHERE id_sala = ?")->execute([$id_sala, $id_sala]);
}
?>
