<?php
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

if (!$pdo) {
    die("Error al conectar con la base de datos.");
}

if (!isset($_GET['id_sala'])) {
    die("ID de sala no especificado.");
}

$idSala = intval($_GET['id_sala']);

# Cambiar el estado de la sala a “En curso” (id_estado = 2, por ejemplo)
$stmt = $pdo->prepare("UPDATE sala SET id_estado = 2 WHERE id_sala = ?");
$stmt->execute([$idSala]);

# Redirigir al “juego”
header("Location: ../JUEGO/juego.php?id_sala=" . $idSala);
exit;
?>
