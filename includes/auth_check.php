<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['id_usuario'])) {
        session_destroy();
        header('Location: ../ini.php');
        exit();
    }
?>
