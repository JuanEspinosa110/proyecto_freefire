<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

header('Content-Type: application/json; charset=utf-8');

$id_sala = isset($_GET['id_sala']) ? (int)$_GET['id_sala'] : null;
if (!$id_sala) {
    echo json_encode(['jugadores_html'=>'','mensaje'=>'Sala no encontrada','finalizar'=>false,'alerta'=>'']);
    exit;
}

// Obtener jugadores: unir usuario con personajes para sacar skin
$stmt = $pdo->prepare("
    SELECT u.id_user, u.username, u.puntos, u.id_niveles, p.skin
    FROM sala_jugadores sj
    JOIN usuario u ON sj.id_user = u.id_user
    LEFT JOIN personajes p ON u.Id_personajes = p.Id_personajes
    WHERE sj.id_sala = ?
    ORDER BY sj.fecha_ingreso ASC
");
$stmt->execute([$id_sala]);
$jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construir HTML seguro para lista de jugadores
$jugadores_html = '';
foreach ($jugadores as $j) {
    $skin_src = '../../IMG/default_skin.png';
    if (!empty($j['skin'])) {
        $s = ltrim($j['skin'], '/');
        if (stripos($s, 'img/') === 0) $skin_src = '../../' . $s;
        else $skin_src = '../../' . $s; // deja tal cual si la ruta ya es relativa
    }
    $jugadores_html .= '<div class="salas-player-card text-center">'
        . '<img src="' . htmlspecialchars($skin_src) . '" alt="' . htmlspecialchars($j['username']) . '">' 
        . '<div><strong>' . htmlspecialchars($j['username']) . '</strong></div>'
        . '<div>Nivel: ' . htmlspecialchars($j['id_niveles']) . '</div>'
        . '<div>Puntos: ' . htmlspecialchars($j['puntos']) . '</div>'
        . '</div>';
}

// Mensaje y lógica: si hay 2+ jugadores -> iniciar; si <2 -> finalizar y limpiar
$count = count($jugadores);
if ($count >= 2) {
    $mensaje = "Iniciando partida en...";
    $finalizar = false;
    $alerta = '';
} else {
    $mensaje = "Esperando jugadores...";
    $finalizar = false;
    $alerta = '';
    // No borramos la sala automáticamente aquí
}

// Devolver JSON
echo json_encode([
    'jugadores_html' => $jugadores_html,
    'mensaje' => $mensaje,
    'finalizar' => $finalizar,
    'alerta' => $alerta,
]);
exit;