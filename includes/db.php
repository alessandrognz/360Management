<?php
require 'includes/procedures/login.php';
require 'includes/procedures/tbl_tarea.php';
require 'includes/procedures/tbl_usuarios.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_config = require __DIR__ . '/db_config.php';

$Ip = $DB_config['ip'];
$Puerto = $DB_config['puerto'];
$Usuario = $DB_config['usuario'];
$Contrasenia = $DB_config['contrasenia'];
$DB_nombre = $DB_config['db_nombre'];

$Coneccion = new mysqli($Ip, $Usuario, $Contrasenia, $DB_nombre, $Puerto);

if ($Coneccion->connect_error) {
    die('Conexión fallida: ' . $Coneccion->connect_error);
}

$Coneccion->set_charset('utf8mb4');

function true_or_false($value = [])
{
    return (bool) $value['response'];
}
?>