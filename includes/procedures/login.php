<?php
class loginAndRegister
{
    function INSERTAR_USUARIO($nombre = '', $email = '', $puesto = 0, $contrasena = '')
    {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL INSERTAR_USUARIO(?,?,?,?);');
        $comando->bind_param('isss', $puesto, $nombre, $email, $contrasena);
        $comando->execute();

        $result = $comando->get_result();
        $usuario = $result->fetch_assoc();
        $result->free();
        $comando->close();

        if ($usuario) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['id_departamento'] = $usuario['id_departamento'];
            $_SESSION['email'] = $email;

            header('Location: session.php');
            exit();
        } else {
            echo 'Error al registrarse';
        }
    }

    function INICIAR_SESION($email = '', $contrasena = ''){
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL VERIFICAR_EMAIL(?);');
        $comando->bind_param('s', $email);
        $comando->execute();

        $_email = $comando->get_result();
        $usuario = $_email->fetch_assoc();
        $comando->close();
        $_email = $usuario['email'] ?? null;

        $comando = $Coneccion->prepare('CALL VERIFICAR_CONTRASENA(?);');
        $comando->bind_param('s', $email);
        $comando->execute();

        $_contrasena = $comando->get_result();
        $usuario = $_contrasena->fetch_assoc();
        $comando->close();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['id_puesto'] = $usuario['id_puesto'];
            $_SESSION['email'] = $email;

                header('Location: session.php');
                exit();
        };
        return false;
    }
}
?>