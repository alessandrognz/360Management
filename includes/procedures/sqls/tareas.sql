use users;

DELIMITER //
create procedure LISTAR_DEPARTAMENTOS()
begin
	SELECT id_departamento,nombre_departamento from departamento where eliminado = 0;
end
//DELIMITER ;

DELIMITER //
create procedure UPDATE_TAREA(_id_tarea int, _titulo varchar(250),_descripcion_tarea varchar(250),fecha_limite datetime)
begin
	update tarea set titulo = _titulo, descripcion_tarea = _descripcion_tarea, fecha_limite = _fecha_limite where id_tarea = _id_tarea;
end
//DELIMITER ;

DELIMITER //
create procedure ELIMINAR_TAREA(_id_tarea int)
begin
	UPDATE tarea set eliminado = 1 where id_tarea = _id_tarea;
end
//DELIMITER ;

DELIMITER //
create procedure SELECT_TAREAS_GENERALES(_id_puesto int)
begin
	select nombre_departamento,titulo,descripcion_tarea,fecha_limite,fecha_creacion,descripcion_est_tarea from tareas_departamento as td
    inner join tareas as tr on tr.id_tarea = td.id_tarea
    inner join departamento as dp on dp.id_departamento = td.id_departamento
    inner join estado_tarea as et on et.id_est_tarea = td.id_est_tarea
    where dp.id_departamento = (select id_departamento from puesto where id_puesto = _id_puesto) and month(fecha_limite) <= month(current_date()) AND eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
create procedure SELECT_TAREAS_PERSONALES(_id_usuario int)
begin
	select titulo,descripcion_tarea,fecha_limite,fecha_creacion,descripcion_est_tarea from tareas_usuarios as td
    inner join tareas as tr on tr.id_tarea = td.id_tarea
    inner join usuarios as us on us.id_usuario = td.id_usuario
    inner join estado_tarea as et on et.id_est_tarea = td.id_est_tarea
    where us.id_usuario = _id_usuario and month(fecha_limite) <= month(current_date()) and eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
create procedure INSERTAR_TAREA(_id_usuario_creador int ,_titulo varchar(250),_descripcion_tarea varchar(250),_fecha_limite datetime,_es_general bit)
begin
	INSERT INTO tareas(id_usuario_creador,titulo,descripcion_tarea,fecha_limite,es_general,fecha_creacion,id_est_tarea) values (_id_usuario_creador,_titulo,_descripcion_tarea,_fecha_limite,_es_general,current_timestamp(),1);
	select last_insert_id() as insertado;
end
//
DELIMITER ;

DELIMITER //
create procedure DESTINAR_A_USAURIO(_id_tarea int,_id_usuario int)
begin
	insert into tareas_usuarios(id_tarea,id_usuario,id_est_tarea) values (_id_tarea,_id_usuario,1);
end
//
DELIMITER ;

DELIMITER //
create procedure DESTINAR_A_DEPARTAMENTO(_id_tarea int,_id_departamento int)
begin
	insert into tareas_departamento(id_tarea,id_departamento,id_est_tarea) values (_id_tarea,_id_departamento,1);
end
//
DELIMITER ;