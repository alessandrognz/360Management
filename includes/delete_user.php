<?php
    require __DIR__ . '/auth_check.php';
    require __DIR__ . '/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        ELIMINAR_USUARIO_LOGICO((int) $_SESSION['id_usuario']);

        $_SESSION = [];
        session_destroy();

        header('Location: ../ini.php');
        exit();
    }

    header('Location: ../settings.php');
    exit();
?>
