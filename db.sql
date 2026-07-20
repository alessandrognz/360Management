create database if not exists users
character set utf8mb4
collate utf8mb4_unicode_520_ci;

USE users;



-- Tablas

create table departamento(
	id_departamento int auto_increment primary key,
    nombre_departamento varchar(150) not null,
    
    eliminado BIT DEFAULT(0)
);

create table puesto(
	id_puesto int auto_increment primary key,
    id_departamento int not null,
    nombre_puesto varchar(150) not null,
    descripcion_puesto varchar(200) not null,
	eliminado BIT DEFAULT(0),
	constraint fk_departamento_puesto foreign key(id_departamento) references departamento(id_departamento)
);

create table usuarios(  
	id_usuario INT auto_increment primary key,
    id_puesto int not null,
    nombre varchar(50) NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    fecha_registro datetime not null DEFAULT current_timestamp,
    contrasena varchar(250) not null,
    eliminado BIT DEFAULT(0),
    constraint fk_puesto_usuarios foreign key(id_puesto) references puesto(id_puesto)
);

create table estado_tarea(
id_est_tarea int auto_increment primary key,
descripcion_est_tarea varchar(50),
eliminado bit default(0)
);

create table tareas(
	-- PKs y Fks
	id_tarea int unsigned auto_increment primary key,
    id_usuario_creador int not null,
    id_est_tarea int default(1),
    
    -- Datos de la tarea
    titulo varchar(25) not null,
    descripcion_tarea varchar(250) not null,
	
    -- Gestion de la tarea
    fecha_creacion datetime not null default current_timestamp,
    fecha_limite datetime null,
    fecha_completada datetime null,
    
    -- Estados
    eliminado BIT DEFAULT(0),
	es_general bit default(0),
    
    constraint fk_tarea_creador foreign key(id_usuario_creador) references usuarios(id_usuario),
    constraint fk_tarea_estado foreign key(id_est_tarea) references estado_tarea(id_est_tarea)
);

create table tareas_departamento(-- pks y fks
    id_tarea INT UNSIGNED not null,
    id_departamento INT not null,
    id_est_tarea int default(1),

	-- datos
    fecha_asignacion datetime not null default current_timestamp,
    fecha_completada datetime null,
	
    -- estados
    eliminado BIT DEFAULT(0),
    completado bit default(0),

    constraint fk_tud_tarea foreign key(id_tarea) references tareas(id_tarea),
    constraint fk_tu_departamento foreign key(id_departamento) references departamento(id_departamento),
    constraint fk_tarea_dep_estado foreign key(id_est_tarea) references estado_tarea(id_est_tarea),
    constraint uq_tarea_usuario unique(id_tarea, id_departamento) -- Esto podria dar problemas a futuro(?). Comprendo la intencion, pero se debe monitorear.
);

create table tareas_usuarios(
	-- pks y fks
    id_tarea INT UNSIGNED not null,
    id_usuario INT not null,
    id_est_tarea int default(1),

	-- datos
    fecha_asignacion datetime not null default current_timestamp,
    fecha_completada datetime null,
	
    -- estados
    eliminado BIT DEFAULT(0),
    completado bit default(0),

    constraint fk_tu_tarea foreign key(id_tarea) references tareas(id_tarea),
    constraint fk_tu_usuario foreign key(id_usuario) references usuarios(id_usuario),
    constraint fk_tarea_usu_estado foreign key(id_est_tarea) references estado_tarea(id_est_tarea),
    constraint uq_tarea_usuario unique(id_tarea, id_usuario) -- Esto podria dar problemas a futuro(?). Comprendo la intencion, pero se debe monitorear.
);

-- Inserts

insert into estado_tarea(descripcion_est_tarea) values ("Pendiente"),("En progreso"),("Completado"),("Fallido");
insert into departamento(nombre_departamento) values ('Dirección y Estrategia'),('Administración y Finanzas'),('Recursos Humanos'),('Marketing y Ventas'),('Tecnología y Desarrollo'),('Operaciones, Logística y Producto'),('Legal y Calidad');
insert into puesto(id_departamento,nombre_puesto,descripcion_puesto) values
((select id_departamento from departamento where nombre_departamento = 'Dirección y Estrategia'),		'Director General (CEO)',			'Máximo responsable de la empresa y de su estrategia global.'),
((select id_departamento from departamento where nombre_departamento = 'Dirección y Estrategia'),		'Director de Operaciones (COO)',		'Supervisa las operaciones diarias de la organización.'),
((select id_departamento from departamento where nombre_departamento = 'Administración y Finanzas'),		'Director Financiero (CFO)',			'Responsable de la gestión económica y financiera.'),
((select id_departamento from departamento where nombre_departamento = 'Administración y Finanzas'),		'Contador / Contable',				'Registra y controla las cuentas y movimientos contables.'),
((select id_departamento from departamento where nombre_departamento = 'Administración y Finanzas'),		'Auxiliar Administrativo',			'Apoya en tareas administrativas y de gestión documental.'),
((select id_departamento from departamento where nombre_departamento = 'Recursos Humanos'),			'Director de Recursos Humanos',			'Dirige la gestión y desarrollo del personal.'),
((select id_departamento from departamento where nombre_departamento = 'Recursos Humanos'),			'Técnico de Selección / Reclutador',		'Busca, evalúa y selecciona candidatos para la empresa.'),
((select id_departamento from departamento where nombre_departamento = 'Marketing y Ventas'),			'Director de Marketing (CMO)',			'Define y dirige la estrategia de marketing.'),
((select id_departamento from departamento where nombre_departamento = 'Marketing y Ventas'),			'Especialista en Marketing Digital',		'Gestiona campañas y presencia en canales digitales.'),
((select id_departamento from departamento where nombre_departamento = 'Marketing y Ventas'),			'Community Manager',				'Administra las redes sociales y la comunidad online.'),
((select id_departamento from departamento where nombre_departamento = 'Marketing y Ventas'),			'Ejecutivo de Ventas / Comercial',		'Capta clientes y cierra ventas de productos o servicios.'),
((select id_departamento from departamento where nombre_departamento = 'Marketing y Ventas'),			'Gerente de Cuentas (Account Manager)',		'Gestiona la relación con las cuentas y clientes clave.'),
((select id_departamento from departamento where nombre_departamento = 'Tecnología y Desarrollo'),		'Director de Tecnología (CTO)',			'Lidera la estrategia y el área tecnológica.'),
((select id_departamento from departamento where nombre_departamento = 'Tecnología y Desarrollo'),		'Desarrollador de Software',			'Diseña, programa y mantiene aplicaciones de software.'),
((select id_departamento from departamento where nombre_departamento = 'Tecnología y Desarrollo'),		'Administrador de Sistemas (SysAdmin)',		'Administra servidores, redes e infraestructura técnica.'),
((select id_departamento from departamento where nombre_departamento = 'Operaciones, Logística y Producto'),	'Gerente de Producto (Product Manager)',	'Define la visión y el ciclo de vida del producto.'),
((select id_departamento from departamento where nombre_departamento = 'Operaciones, Logística y Producto'),	'Responsable de Logística',			'Coordina el almacenaje, transporte y distribución.'),
((select id_departamento from departamento where nombre_departamento = 'Operaciones, Logística y Producto'),	'Especialista en Atención al Cliente',		'Atiende y resuelve las consultas de los clientes.'),
((select id_departamento from departamento where nombre_departamento = 'Legal y Calidad'),			'Asesor Legal / Abogado Corporativo',		'Asesora en asuntos jurídicos y legales de la empresa.'),
((select id_departamento from departamento where nombre_departamento = 'Legal y Calidad'),			'Auditor de Calidad',				'Verifica y garantiza el cumplimiento de estándares de calidad.');


-- Procedures


DELIMITER //
create procedure INSERTAR_USUARIO(in _id_puesto int , in _nombre varchar(50), in _email varchar(100), in _contrasena varchar(250))
begin
    insert into usuarios(id_puesto,nombre,email,contrasena) values
    (_id_puesto,_nombre,_email,_contrasena);

    if row_count() > 0 then
        select u.id_usuario, u.nombre, p.id_departamento
        from usuarios u
        join puesto p on u.id_puesto = p.id_puesto
        where u.id_usuario = last_insert_id();
    end if;
end
//
DELIMITER ;

DELIMITER //
create procedure VERIFICAR_EMAIL(_email varchar(100))
begin
	select email
    from usuarios
    where email = _email and eliminado = 0;
end
//
DELIMITER ; 

DELIMITER //

DELIMITER //
CREATE PROCEDURE VERIFICAR_CONTRASENA(_email VARCHAR(100))
BEGIN
    SELECT contrasena, nombre, id_usuario, id_puesto
    FROM usuarios
    WHERE email = _email AND eliminado = 0;
END
//
DELIMITER ;

DELIMITER //
create procedure ELIMINAR_USUARIO(_nombre VARCHAR(50))
begin
    update usuarios 
    set eliminado = 1 
    where nombre = _nombre and eliminado = 0; 
end
//
DELIMITER ;


DELIMITER //
create procedure CAMBIAR_NOMBRE_USUARIO(_id_usuario int ,_nombre varchar(50))
begin
	update usuarios 
    set nombre = _nombre
    where id_usuario = _id_usuario and eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
use users;
create procedure MOSTRAR_USUARIOS()
begin
    select u.id_usuario, u.id_puesto, u.nombre, u.email, u.fecha_registro, p.nombre_puesto
    from usuarios u
    join puesto p on u.id_puesto = p.id_puesto
    where u.eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
create procedure MOSTRAR_USUARIO(_id_usuario int)
begin
    select u.id_usuario, u.nombre, u.email, u.fecha_registro, p.nombre_puesto
    from usuarios u
    join puesto p on u.id_puesto = p.id_puesto
    where u.id_usuario = _id_usuario and u.eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
create procedure ELIMINAR_USUARIO_LOGICO(_id_usuario int)
begin
    update usuarios
    set eliminado = 1
    where id_usuario = _id_usuario and eliminado = 0;
end
//
DELIMITER ;

-- Otros

create index idx_tu_usuario on tareas_usuarios(id_usuario);
