<?php
require __DIR__ . '/auth_check.php';
require __DIR__ . '/db.php';

$crud_user = new CRUD_USER();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crud_user->ELIMINAR_USUARIO_LOGICO((int) $_SESSION['id_usuario']);

    $_SESSION = [];
    session_destroy();

    header('Location: ../index.php');
    exit();
}

header('Location: ../settings.php');
exit();
?>
