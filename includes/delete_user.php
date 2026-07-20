<?php
    require 'db.php';
    session_start();
    global $Coneccion;

    $nombre = $_SESSION['nombre'] ?? '';

    $comando = $Coneccion->prepare("CALL ELIMINAR_USUARIO(?);");
    $comando->bind_param("s", $nombre);
    $comando->execute();
    $comando->close();

    session_destroy();
    header("Location: index.html");
    exit();
        