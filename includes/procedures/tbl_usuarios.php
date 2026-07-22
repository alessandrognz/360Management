<?php
class CRUD_USER
{
    function ELIMINAR_USUARIO($nombre = '')
    {
        global $Coneccion;

        if ($nombre === '') {
            $nombre = $_SESSION['nombre'] ?? '';
        }

        $comando = $Coneccion->prepare('CALL ELIMINAR_USUARIO(?);');
        $comando->bind_param('s', $nombre);
        $comando->execute();
        $comando->close();

        session_destroy();
        header('Location: index.php');
        exit();
    }

    function MOSTRAR_USUARIO($id_usuario = 0)
    {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL MOSTRAR_USUARIO(?);');
        $comando->bind_param('i', $id_usuario);
        $comando->execute();

        $result = $comando->get_result();
        $usuario = $result->fetch_assoc();
        $result->free();
        $comando->close();

        return $usuario;
    }

    function MOSTRAR_USUARIOS()
    {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL MOSTRAR_USUARIOS();');
        $comando->execute();

        $result = $comando->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $usuarios;
    }

    function ELIMINAR_USUARIO_LOGICO($id_usuario = 0)
    {
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

    function CAMBIAR_CONTRASENA($contrasena = '', $nueva_contrasena = '')
    {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL VERIFICAR_CONTRASENA(?);');
        $email = $_SESSION['email'];
        $comando->bind_param('s', $email);
        $comando->execute();

        $_contrasena = $comando->get_result();
        $usuario = $_contrasena->fetch_assoc();
        $comando->close();

        if (!$usuario) {
            return false;
        }

        if (password_verify($contrasena, $usuario['contrasena'])) {
            if (!function_exists('REMPLAZAR_CONTRASENA')) {
                function REMPLAZAR_CONTRASENA(int $id_usuario, $new_contrasena = '')
                {
                    global $Coneccion;
                    $comando = $Coneccion->prepare('CALL CAMBIAR_CONTRASENA(?,?);');

                    $comando->bind_param('is', $id_usuario, $new_contrasena);
                    $comando->execute();
                    $comando->close();
                }
            }

            REMPLAZAR_CONTRASENA($usuario['id_usuario'], password_hash($nueva_contrasena, PASSWORD_BCRYPT));
            return true;
        }
        return false;
    }
}