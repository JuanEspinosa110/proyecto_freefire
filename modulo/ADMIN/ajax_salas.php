<?php
session_start();
require '../../DB/conection.php';
$db = new Database();
$pdo = $db->conectar();

// Verificar sesión
if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    echo '<div class="col-12"><div class="salas-alert">Acceso no autorizado</div></div>';
    exit;
}

// Obtener todas las salas
$sql = "SELECT s.id_sala, s.id_mapa, s.jugadores_actuales, s.max_jugadores, s.fecha_creacion, m.imagen AS mapa_imagen
        FROM sala s
        LEFT JOIN mapa m ON s.id_mapa = m.id_mapa
        ORDER BY s.fecha_creacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$salas) {
    echo '<div class="col-12"><div class="salas-alert">No hay salas disponibles.</div></div>';
    exit;
}

// Generar HTML de tarjetas
foreach ($salas as $s) {
    $mapa_img = $s['mapa_imagen'] ?? 'fondo.jpg';
    // Normalizar ruta
    if (preg_match('/^https?:\\/\\//', $mapa_img)) {
        $mapa_path = $mapa_img;
    } else {
        $mapa = ltrim($mapa_img, '/');
        if (stripos($mapa, 'img/') === 0) {
            $mapa_path = '../../' . $mapa;
        } else {
            $mapa_path = '../../IMG/' . $mapa;
        }
    }

    $id = (int)$s['id_sala'];
    $jug_act = (int)$s['jugadores_actuales'];
    $jug_max = (int)$s['max_jugadores'];

    echo '<div class="col-md-4">';
    echo '<a href="ver_sala.php?id_sala=' . $id . '" class="salas-card d-block" style="background-image: url(' . htmlspecialchars($mapa_path) . '); background-size:cover; background-position:center;">';
    echo '<div style="background:rgba(0,0,0,0.5); padding:15px; border-radius:10px;">';
    echo '<h4 class="text-warning">Sala #' . $id . '</h4>';
    echo '<div class="text-white">Jugadores: <strong>' . $jug_act . '</strong> / ' . $jug_max . '</div>';
    echo '<div class="mt-2"><button class="salas-btn salas-btn-primary">Entrar</button></div>';
    echo '</div>';
    echo '</a>';
    echo '</div>';
}

exit;
?>