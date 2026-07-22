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

insert into departamento(nombre_departamento) values
('Dirección y Estrategia'),
('Administración y Finanzas'),
('Recursos Humanos'),
('Marketing y Ventas'),
('Tecnología y Desarrollo'),
('Operaciones, Logística y Producto'),
('Legal y Calidad');

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

DELIMITER //

create procedure CAMBIAR_CONTRASENA(_id_usuario int, _contrasena varchar(250))
begin
	update usuarios 
    set contrasena = _contrasena
    where id_usuario = _id_usuario AND eliminado = 0;
end
//
DELIMITER ;

DELIMITER //
create procedure CAMBIAR_EMAIL(_id_usuario int ,_email varchar(50))
begin
	update usuarios 
    set email = _email
    where id_usuario = _id_usuario and eliminado = 0;
end
//
DELIMITER ;