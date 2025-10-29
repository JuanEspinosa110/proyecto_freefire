<?php
function actualizarNivelUsuario($pdo, $id_user) {
    // Obtener puntos actuales
    $stmt = $pdo->prepare("SELECT puntos FROM usuario WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $puntos = (int)$stmt->fetchColumn();

    // Determinar nuevo nivel
    $nuevoNivel = 1;

    if ($puntos >= 500 && $puntos < 1000) {
        $nuevoNivel = 2;
    } elseif ($puntos >= 1000 && $puntos < 2000) {
        $nuevoNivel = 3;
    } elseif ($puntos >= 2000) {
        $nuevoNivel = 4;
    }

    // Actualizar nivel solo si cambió
    $stmt = $pdo->prepare("UPDATE usuario SET id_niveles = ? WHERE id_user = ?");
    $stmt->execute([$nuevoNivel, $id_user]);
}
?>
