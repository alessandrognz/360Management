<?php
    session_start();

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
    
    
?>