<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $DB_config = require __DIR__ . '/db_config.php';

    $Ip = $DB_config['ip'];
    $Puerto = $DB_config['puerto'];
    $Usuario = $DB_config['usuario'];
    $Contrasenia = $DB_config['contrasenia'];
    $DB_nombre = $DB_config['db_nombre'];

    $Coneccion = new mysqli($Ip,$Usuario,$Contrasenia,$DB_nombre,$Puerto);

    if ($Coneccion->connect_error) {
        die("Conexión fallida: " . $Coneccion->connect_error);
    }

    $Coneccion->set_charset("utf8mb4");

    function true_or_false($value= []) {
    return (bool)$value["response"];
    }

    class loginAndRegister{
        function INSERTAR_USUARIO($nombre = '',$email = '',$puesto = 0,$contrasena = ''){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL INSERTAR_USUARIO(?,?,?,?);");
            $comando->bind_param("isss", $puesto,$nombre,$email,$contrasena);
            $comando->execute();

            $result  = $comando->get_result();
            $usuario = $result->fetch_assoc();
            $result->free();
            $comando->close();

            if ($usuario) {
                $_SESSION['nombre']          = $usuario['nombre'];
                $_SESSION['id_usuario']      = $usuario['id_usuario'];
                $_SESSION['id_departamento'] = $usuario['id_departamento'];

                header("Location: session.php");
                exit();
            } else {
                echo "Error al registrarse";
            }
        }

        function INICIAR_SESION($email = '',$contrasena = '') {
            global $Coneccion;

            $comando = $Coneccion->prepare('CALL VERIFICAR_EMAIL(?);');
            $comando->bind_param("s", $email);
            $comando->execute();

            $_email = $comando->get_result();
            $usuario = $_email->fetch_assoc();
            $comando->close();
            $_email = $usuario['email'];

            $comando = $Coneccion->prepare('CALL VERIFICAR_CONTRASENA(?);');
            $comando->bind_param("s", $email);
            $comando->execute();

            $_contrasena = $comando->get_result();
            $usuario = $_contrasena->fetch_assoc();
            $comando->close();

            if (password_verify($contrasena, $usuario['contrasena'])){
                $_SESSION['nombre']     = $usuario['nombre'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['id_puesto']  = $usuario['id_puesto'];

                header('Location: session.php');
                exit();
            };
            return false;
        }
    }
        function ELIMINAR_USUARIO($nombre = ''){
    class TBL_TAREA{
        function SELECT_TAREAS_GENERALES($id){
            global $Coneccion;
            $tabla = [];


            //Comando
            $comando = $Coneccion->prepare("CALL SELECT_TAREAS_GENERALES(?);");
            $comando->bind_param("i", $id);
    
    
            //Llenado de variables
            $comando->execute();
    
            //Obtener resultado
            $result = $comando->get_result();
            $comando->close();




            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $tabla[] = $row;
                }


                $result->free();
            }
        
            return $tabla; // Print es para pruebas visibles, se cambiaria por un return


        }
        function SELECT_TAREAS_PERSONALES(){
            $a="1";
        }
        
    }
            global $Coneccion;

            if ($nombre === '') {$nombre = $_SESSION['nombre'] ?? '';}

            $comando = $Coneccion->prepare("CALL ELIMINAR_USUARIO(?);");
            $comando->bind_param("s", $nombre);
            $comando->execute();
            $comando->close();

            session_destroy();
            header("Location: index.html");
            exit();
        };
        return false;
    
    function MOSTRAR_USUARIO($id_usuario = 0) {
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL MOSTRAR_USUARIO(?);");
        $comando->bind_param("i", $id_usuario);
        $comando->execute();

        $result  = $comando->get_result();
        $usuario = $result->fetch_assoc();
        $result->free();
        $comando->close();

        return $usuario;
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

        $comando = $Coneccion->prepare("CALL ELIMINAR_USUARIO_LOGICO(?);");
        $comando->bind_param("i", $id_usuario);
        $comando->execute();
        $comando->close();
    }
    function CAMBIAR_NOMBRE_USUARIO($id_usuario = 0, $new_nombre = '') {
        global $Coneccion;

        $comando = $Coneccion->prepare('CALL CAMBIAR_NOMBRE_USUARIO(?,?);');
        $comando->bind_param("is", $id_usuario, $new_nombre);
        $comando->execute();

        $comando->close();
    }
?>