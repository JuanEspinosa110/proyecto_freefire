    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once(__DIR__ . "/../../DB/conection.php");
    $db = new Database();
    $con = $db->conectar();
    session_start();

    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $clave = $_POST['clave'];
    $confirmar = $_POST['confirmar'];

    if ($clave != $confirmar) {
        echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
        exit;
    }

    $clave_segura = password_hash($clave, PASSWORD_DEFAULT);

    $verificar = $con->query("SELECT * FROM usuario WHERE username='$usuario' OR correo='$correo'");
    if ($verificar->rowCount() > 0) {
        echo "<script>alert('El usuario o correo ya existen'); window.history.back();</script>";
        exit;
    }

    $puntos = 0;
    $id_niveles = 1;
    $id_tip_user = 2;
    $id_personajes = 1;
    $id_estado = 2;

    $sql = "INSERT INTO usuario (username, nombre, correo, contrasena, puntos, id_niveles, id_tip_user, Id_personajes, id_estado, ultima_conexion)
            VALUES ('$usuario', '$nombre', '$correo', '$clave_segura', $puntos, $id_niveles, $id_tip_user, $id_personajes, $id_estado, NULL)";

    $resultado = $con->query($sql);

    if ($resultado) {
        echo "<script>alert('Registro exitoso. Tu cuenta está bloqueada hasta que el administrador la apruebe.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error al registrar el usuario');</script>";
    }
    ?>
