-- DROP DATABASE IF EXISTS informatica;
-- A. Crear BD
CREATE DATABASE informatica;
USE informatica;

-- B. Tablas
-- 1. Tabla Marcas
CREATE TABLE marcas(
idmarca SMALLINT AUTO_INCREMENT PRIMARY KEY,
marca VARCHAR(30) NOT NULL,
fechaalta DATETIME NOT NULL DEFAULT NOW(),
fechabaja DATETIME NULL,
estado CHAR(1) NOT NULL DEFAULT '1',
CONSTRAINT uk_marca_mat UNIQUE (marca)
)ENGINE = INNODB;

INSERT INTO marcas (marca) VALUES
('Samsung'), ('HP'), ('Micronics');

SELECT * FROM marcas;

-- 2. Tabla Marcas
CREATE TABLE clasificaciones(
idclasificacion SMALLINT AUTO_INCREMENT PRIMARY KEY,
clasificacion VARCHAR(50) NOT NULL,
fechaalta DATETIME NOT NULL DEFAULT NOW(),
fechabaja DATETIME NULL,
estado CHAR(1) NOT NULL	DEFAULT '1',
CONSTRAINT uk_clasificacion_cla UNIQUE (clasificacion)
)ENGINE = INNODB;

INSERT INTO clasificaciones (clasificacion) VALUES
("Memoria RAM"), ("Fuente de poder"), ("Mouse"), ("Monitor");

SELECT * FROM clasificaciones;

-- 3. Tabla Marcas
CREATE TABLE productos(
idproducto INT AUTO_INCREMENT PRIMARY KEY,
idclasificacion SMALLINT NOT NULL,
idmarca SMALLINT NOT NULL,
descripcion	VARCHAR(100) NOT NULL,
esnuevo CHAR(1) NOT NULL DEFAULT 'S',
numeroserie VARCHAR(30) NULL,
precio DECIMAL(6,2)	NOT NULL,
fechaalta DATETIME NOT NULL DEFAULT NOW(),
fechabaja DATETIME NULL,
estado CHAR(1) NOT NULL DEFAULT '1',
CONSTRAINT fk_idclasificacion_cla FOREIGN KEY (idclasificacion) REFERENCES clasificaciones (idclasificacion),
CONSTRAINT fk_idmarca_cla FOREIGN KEY (idmarca) REFERENCES marcas (idmarca)
)ENGINE = INNODB;

INSERT INTO productos (idclasificacion, idmarca, descripcion, precio) VALUES
(1, 1, '16 Gb. DDR5 5000Mhz', 570), (2, 2, '500 Watts real', 170);

INSERT INTO productos (idclasificacion, idmarca, descripcion, precio) VALUES
(3,2,'Mouse inalambrico', 60), (4,3,'Monitor Samsung 48', 150);

SELECT * FROM productos;

-- CREACIÓN DE PROCEDIMIENTOS ALMACENADOS
-- 1° Procedimiento almacenado para listar productos
DELIMITER $$
CREATE PROCEDURE spu_productos_listar()
BEGIN
	SELECT pro.idproducto, cla.clasificacion, mar.marca, pro.descripcion,
			pro.esnuevo, pro.numeroserie, pro.precio
		FROM productos pro
	INNER JOIN clasificaciones cla ON pro.idclasificacion = cla.idclasificacion
	INNER JOIN marcas mar ON pro.idmarca = mar.idmarca
	WHERE pro.estado = '1';
END $$
-- Para ejecutar un procedimiento y verificar su funcionamiento
-- CALL spu_tabla_accion(variables_opcional);
-- CALL spu_productos_listar();

-- Los datos se enviarán desde la vista, entonces requerimos
-- variables de entrada, por eso se declaran con "IN" (INPUT)
-- Además las variables deben iniciar con @ o _ y su tipo debe
-- coincidir con el definido de la tabla
-- 2° Procedimiento almacenado para registrar productos
DELIMITER $$
CREATE PROCEDURE spu_productos_registrar(
IN _idclasificacion SMALLINT,
IN _idmarca SMALLINT,
IN _descripcion VARCHAR(100),
IN _esnuevo CHAR(1),
IN _numeroserie VARCHAR(30),
IN _precio DECIMAL(6,2))
BEGIN
	-- No se pasan los campos definidos con DEFAULT, a excepcion de "esnuevo" ya que el usuario tiene
	-- la posibilidad de elegir el valor desde la vista/view
	INSERT INTO productos (idclasificacion, idmarca, descripcion, esnuevo, numeroserie, precio)
	VALUES (_idclasificacion, _idmarca, _descripcion, _esnuevo, _numeroserie, _precio);
END $$

-- 3° Procedimiento almacenado para eliminar productos
DELIMITER $$
CREATE PROCEDURE spu_productos_eliminar(IN _idproducto INT)
BEGIN
	UPDATE productos
		SET estado = '0' WHERE idproducto = _idproducto;
END $$

-- 4° Procedimiento almacenado para obtener productos por 'id'
DELIMITER $$
CREATE PROCEDURE spu_productos_obtener(IN _idproducto INT)
BEGIN
	SELECT idproducto, idclasificacion, idmarca, descripcion, esnuevo, numeroserie, precio
	FROM productos WHERE estado = '1' AND idproducto = _idproducto;
END $$

-- 5° Procedimiento almacenado para actualizar productos
DELIMITER $$
CREATE PROCEDURE spu_productos_actualizar(
IN _idproducto		INT,
IN _idclasificacion	SMALLINT,
IN _idmarca			SMALLINT,
IN _descripcion		VARCHAR(100),
IN _esnuevo			CHAR(1),
IN _numeroserie		VARCHAR(30),
IN _precio			DECIMAL(6,2))
BEGIN
	IF _numeroserie = '' THEN 
	SET _numeroserie = NULL; 
	END IF;

	UPDATE productos SET
	idclasificacion = _idclasificacion,
	idmarca = idmarca,
	descripcion = _descripcion,
	esnuevo = _esnuevo,
	numeroserie = _numeroserie,
	precio = _precio
	WHERE idproducto = _idproducto;
END $$

UPDATE productos SET numeroserie = NULL;
SELECT * FROM productos WHERE NUMEROSERIE IS NULL;

-- TEST PROCEDIMIENTOS TABLA PRODUCTOS
CALL spu_productos_listar();
CALL spu_productos_obtener(1);
CALL spu_productos_eliminar(2);
CALL spu_productos_registrar(3, 2, 'Mouse', 'N', 'ABC', 100);
CALL spu_productos_actualizar(3, 3, 2, 'Mouse gamer RGB', 'S', 'HN-7SA521', 78.5);
SELECT * FROM productos

-- Solo se utiliza la PK y el campo a mostrar en la lista
-- 6° Procedimiento almacenado para listar clasificaciones
DELIMITER $$
CREATE PROCEDURE spu_clasificaciones_listar()
BEGIN
	SELECT idclasificacion, clasificacion
		FROM clasificaciones
    WHERE estado = '1'
    ORDER BY clasificacion;
END $$

-- Solo se utiliza la PK y el campo a mostrar en la lista
-- 7° Procedimiento almacenado para listar marcas
DELIMITER $$
CREATE PROCEDURE spu_marcas_listar()
BEGIN
	SELECT idmarca, marca FROM marcas
    WHERE estado = '1' ORDER BY marca;
END $$

CALL spu_clasificaciones_listar();
CALL spu_marcas_listar();


















-- LUNES 17/10/2022
-- Creación de la tabla usuarios
CREATE TABLE usuarios(
idusuario INT AUTO_INCREMENT PRIMARY KEY,
apellidos VARCHAR(30) NOT NULL,
nombres	VARCHAR(30)	NOT NULL,
email VARCHAR(100) NOT NULL,
claveacceso	VARCHAR(100) NOT NULL,
nivelacceso	CHAR(1)	NOT NULL DEFAULT 'A',
fechacreacion DATETIME NOT NULL DEFAULT NOW(),
fechabaja DATETIME NULL,
estado CHAR(1) NOT NULL	DEFAULT '1',
CONSTRAINT uk_email_usu UNIQUE (email)
)ENGINE = INNODB;

INSERT INTO usuarios (apellidos, nombres, email, claveacceso) VALUES
('Jacobo Saravia', 'José', 'jose@gmail.com', '123456'),
('Martinez Salazar', 'Juan', 'juan@gmail.com', '123456'),
('Cárdenas Pachas', 'Sofía', 'sofia@gmail.com', '123456');

-- CLAVE : SENATI
UPDATE usuarios SET claveacceso = '$2y$10$5cGdvQhiacym30wcLIqS6uyxaZtkHp4pU.ERLhpxBp6jTOaw22s7O';

-- Primero se valida si existe el USUARIO.
-- Segundo se comprueba la CONTRASEÑA.
-- Tercero se da acceso a la App.
-- 8° Procedimiento almacenado para que los usuarios inicien sesión 
DELIMITER $$
CREATE PROCEDURE spu_usuarios_login(IN _email VARCHAR(100))
BEGIN
	SELECT idusuario, apellidos, nombres, email, claveacceso, nivelacceso
	FROM usuarios
	WHERE email = _email AND estado = '1';
END $$




















-- LUNES 21/10/2022
-- 9° Procedimiento almacenado para registrar usuarios (como Invitado)
DELIMITER $$
CREATE PROCEDURE spu_usuarios_signup(
IN _apellidos VARCHAR(30),
IN _nombres VARCHAR(30),
IN _email VARCHAR(100),
IN _claveacceso VARCHAR(100),
IN _nivelacceso CHAR(1))
BEGIN
	INSERT INTO usuarios (apellidos, nombres, email, claveacceso, nivelacceso)
	VALUES (_apellidos, _nombres, _email, _claveacceso, _nivelacceso);
END $$

-- 10° Procedimiento almacenado para mostrar los tipos de usuarios
DELIMITER $$
CREATE PROCEDURE spu_usuarios_listar()
BEGIN
	SELECT idusuario, apellidos, nombres, email, nivelacceso, fechacreacion
	FROM usuarios
	WHERE estado = '1';
END $$

-- 11° Procedimiento almacenado para cambiar nivel de acceso (invitado → admin)
DELIMITER $$
CREATE PROCEDURE spu_usuarios_promover(IN _idusuario INT)
BEGIN
	UPDATE usuarios SET nivelacceso='A' WHERE idusuario = _idusuario;
END $$

-- 12° Procedimiento almacenado para cambiar nivel de acceso (admin → invitado)
DELIMITER $$
CREATE PROCEDURE spu_usuarios_degradar(IN _idusuario INT)
BEGIN
	UPDATE usuarios SET nivelacceso='I' WHERE idusuario = _idusuario;
END $$

-- 13° Procedimiento almacenado para inhabilitar usuarios
DELIMITER $$
CREATE PROCEDURE spu_usuarios_inhabilitar(IN _idusuario INT)
BEGIN
	UPDATE usuarios
		SET estado = '0' WHERE idusuario = _idusuario;
END $$

-- 14° Procedimiento almacenado para reiniciar clave usuarios
DELIMITER $$
CREATE PROCEDURE spu_usuarios_reiniciar(
	IN _idusuario INT,
	IN _claveacceso VARCHAR(100))
BEGIN
	UPDATE usuarios SET
		claveacceso = _claveacceso
	WHERE idusuario = _idusuario;
END $$

CALL spu_usuarios_login('jose@gmail.com');
CALL spu_usuarios_signup('De la Cruz Peña', 'Carlos', 'carlos@gmail.com', '123456', 'I');
CALL spu_usuarios_listar();
CALL spu_usuarios_promover(3);
CALL spu_usuarios_degradar(1);
CALL spu_usuarios_inhabilitar(2);
CALL spu_usuarios_reiniciar(4, 'PASS');
SELECT * FROM usuarios;

-- UPDATE usuarios SET nivelacceso = 'A';
-- UPDATE usuarios SET estado = '1';

























-- LUNES 31/10/2022
ALTER TABLE usuarios ADD telefono CHAR(9) NULL;
ALTER TABLE usuarios ADD fotoperfil VARCHAR(100) NULL;
UPDATE usuarios SET telefono = '956834915' WHERE idusuario = 1;
SELECT * FROM usuarios;

CREATE TABLE desbloqueos(
iddesbloqueo INT AUTO_INCREMENT PRIMARY KEY,
idusuario INT NOT NULL,
coddesbloqueo CHAR(4) NOT NULL,
fechacreacion DATETIME NOT NULL DEFAULT NOW(),
fechaactivacion DATETIME NULL,					-- Solo los que reinicien tendrán este valor
estado CHAR(1) NOT NULL DEFAULT '1',
CONSTRAINT fk_idusuario_des FOREIGN KEY (idusuario) REFERENCES usuarios (idusuario)
)ENGINE = INNODB;

SELECT * FROM desbloqueos;

-- El correo es una forma de validar que el USUARIO EXISTE
-- pero lo que realmente necesitamos es su TELEFONO
-- DROP PROCEDURE spu_usuarios_gettelefono;
DELIMITER $$
CREATE PROCEDURE spu_usuarios_gettelefono(
IN _email VARCHAR(100))
BEGIN
	-- Paso 1: Obtener el teléfono del usuario
	SELECT idusuario, email, telefono FROM usuarios WHERE email = _email AND estado = '1';
END $$

CALL spu_usuarios_gettelefono('jose@gmail.com');
-- SELECT * FROM desbloqueos;

-- DROP PROCEDURE spu_desbloqueos_registrar;
DELIMITER $$
CREATE PROCEDURE spu_desbloqueos_registrar(
IN _idusuario INT,
IN _coddesbloqueo CHAR(4))
BEGIN
	UPDATE desbloqueos SET estado = '0'
	WHERE idusuario = _idusuario;

	INSERT INTO desbloqueos (idusuario, coddesbloqueo)
	VALUES (_idusuario, _coddesbloqueo);
END $$

-- TRUNCATE TABLE desbloqueos;

-- Eliminar el SPU
-- DROP PROCEDURE spu_desbloqueos_validar;
DELIMITER $$
CREATE PROCEDURE spu_desbloqueos_validar(
	IN _idusuario INT,
	IN _coddesbloqueo CHAR(4))
BEGIN

	-- Creamos variables
	-- Variables de salida
	DECLARE _resultado VARCHAR(5);
	DECLARE _mensaje VARCHAR(200); 
	
	-- Variables cuantificables
	DECLARE _registros INT;
	DECLARE _fechacreacion DATETIME;
	DECLARE _tiempodif TIME;
	DECLARE _minutos INT;
	
	SET _resultado = 'ERROR';
	SET _mensaje = 'El código ingresado es incorrecto o está inactivo, vuelva a generar';
	SET _registros = (SELECT COUNT(*) FROM desbloqueos WHERE idusuario = _idusuario AND coddesbloqueo = _coddesbloqueo AND estado = '1');

	-- Verificamos si los datos son correctos
	IF _registros = 1 THEN
		
		-- Ahora debemos verificar que no existan más de 15 min de diferencia
		-- Primero comprobamos que se trate del mismo día
		-- Obtenemos la fechacreacion
		SET _fechacreacion = (SELECT fechacreacion FROM desbloqueos WHERE idusuario = _idusuario ORDER BY 1 DESC LIMIT 1);
		
		-- Validando el día
		IF DATE(NOW()) = DATE(_fechacreacion) THEN
			-- Ahora validamos el tiempo (15 minutos)
			SET _tiempodif = TIMEDIFF(TIME(NOW()), TIME(_fechacreacion));
			SET _minutos = HOUR(_tiempodif) * 60 + MINUTE(_tiempodif);
			
			IF _minutos <= 15 THEN
					-- Cerramos el código desbloqueo
					UPDATE desbloqueos SET fechaactivacion = NOW(), estado = '0' WHERE idusuario = _idusuario AND estado = '1';
					SET _resultado = 'OK';
					SET _mensaje = 'Proceso terminado correctamente';				
			END IF; -- Validación 15 min
		END IF; -- Validación mismo día
	END IF; -- Validación código correcto
	
	-- Retorno de datos:
	SELECT _resultado 'resultado', _mensaje 'mensaje';
END $$

CALL spu_desbloqueos_validar(1, '3694');

-- PRÁCTICAS DE DATEDIFF(fechaMayor o fechaFuturo, fechaMenor o fechaPasada)
SELECT DATEDIFF ('2022-11-07', '2022-01-01') AS 'Días transcurridos';
SELECT DATEDIFF ('2022-11-07', '2022-06-18') AS 'Días de mi vida';
SELECT DATEDIFF ('2022-11-07', '1984-09-20') AS 'Días de vida instructor';
SELECT DATEDIFF ('2022-12-25', CURDATE()) AS 'Días para Navidad';
SELECT DATEDIFF ('2023-06-18', CURDATE()) AS 'Días para próx. cumpleaños';

SELECT TIMEDIFF ('12:00:00', '11:30:00');
SELECT TIMEDIFF ('12:03:00', '00:00:00');

SELECT HOUR(TIMEDIFF('11:00:00', '10:40:00'));
SELECT MINUTE(TIMEDIFF('11:00:00', '10:40:00'));

-- Cuántos minutos pasaron entre 8:45 y 10:55
SELECT HOUR(TIMEDIFF('10:55:00', '8:45:00'))*60 + MINUTE(TIMEDIFF('10:55:00', '8:45:00'));

-- Si tenemos que comparar dos valores (FECHA/HORA), primero debemos
-- Estar seguro que son el mismo día
-- Cuando TIMEDIFF sobrepase 23:59:59 significa que algo hicimos mal
SELECT DATE('2022-11-07 08:00:00'); -- Utiliza solo la fecha
SELECT TIME('2022-11-07 08:00:00'); -- Utiliza solo la hora
SELECT TIMEDIFF('2022-11-07 08:00:00', '2022-11-06 06:30:00');





SELECT * FROM usuarios;