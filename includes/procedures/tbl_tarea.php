<?php
class CRUD_TAREAS{
    function INSERTAR_TAREA(int $_id_usuario_creador, string $_titulo, string $_descripcion_tarea, $_fecha_limite , int $_es_general, $_listado_enviar){
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL INSERTAR_TAREA(?,?,?,?,?);");
        $comando->bind_param("isssi", $_id_usuario_creador,$_titulo,$_descripcion_tarea,$_fecha_limite,$_es_general);
        $comando->execute();

        $result   = $comando->get_result();
        $id = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();
        $id_tarea = $id[0]["insertado"];
        if($_es_general == 0){
            foreach ($_listado_enviar as $ids) {
                $comando = $Coneccion->prepare("CALL DESTINAR_A_USAURIO(?,?);");
                $comando->bind_param("ii", $id_tarea,$ids);
                $comando->execute();
                $comando->close();

            }

        }elseif($_es_general == 1){
                foreach ($_listado_enviar as $ids) {
                $comando = $Coneccion->prepare("CALL DESTINAR_A_DEPARTAMENTO(?,?);");
                $comando->bind_param("ii", $id_tarea,$ids);
                $comando->execute();
                $comando->close();

            }
        }

    }
    function SELECT_TAREAS_GENERALES(int $_id_puesto){
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL SELECT_TAREAS_GENERALES(?);");
        $comando->bind_param("i", $_id_puesto);
        $comando->execute();

        $result  = $comando->get_result();
        $tareas = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $tareas;
    }
    function SELECT_TAREAS_PERSONALES(int $_id_usuario){
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL SELECT_TAREAS_PERSONALES(?);");
        $comando->bind_param("i", $_id_usuario);
        $comando->execute();

        $result  = $comando->get_result();
        $tareas = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $tareas;
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
    function SELECT_MIS_TAREAS(int $_id_usuario) {
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL SELECT_MIS_TAREAS(?);");
        $comando->bind_param("i", $_id_usuario);
        $comando->execute();

        $result = $comando->get_result();
        $tareas = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $tareas;
    }
    function LISTAR_DEPARTAMENTOS(){
        global $Coneccion;

        $comando = $Coneccion->prepare("CALL LISTAR_DEPARTAMENTOS();");
        $comando->execute();

        $result   = $comando->get_result();
        $departamentos = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $comando->close();

        return $departamentos;
    }
}
    
?>