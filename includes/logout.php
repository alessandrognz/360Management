<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    header('Location: ../ini.php');
    exit;
?>