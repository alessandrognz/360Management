<?php
class usuario{

    function ELIMINAR_USUARIO($nombre = ''){
        global $Coneccion;

        if ($nombre === '') {$nombre = $_SESSION['nombre'] ?? '';}

        $comando = $Coneccion->prepare("CALL ELIMINAR_USUARIO(?);");
        $comando->bind_param("s", $nombre);
        $comando->execute();
        $comando->close();

        session_destroy();
        header('Location: index.php');
        exit();
    }

    function MOSTRAR_USUARIO($id_usuario = 0)
    {
        session_destroy();
        header("Location: index.html");
        exit();
    }
    

    
    function MOSTRAR_USUARIOS() {
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL MOSTRAR_USUARIOS();");
        $comando->execute();

        $result   = $comando->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $usuarios;
    }
    function ELIMINAR_USUARIO_LOGICO($id_usuario = 0) {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL ELIMINAR_USUARIO_LOGICO(?);');
        $comando->bind_param('i', $id_usuario);
        $comando->execute();
        $comando->close();
    }

    function CAMBIAR_NOMBRE_USUARIO($id_usuario = 0, $new_nombre = '')
    {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL CAMBIAR_NOMBRE_USUARIO(?,?);');
        $comando->bind_param('is', $id_usuario, $new_nombre);
        $comando->execute();

        $comando->close();
    }
}

?>