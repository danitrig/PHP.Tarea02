<?php
//En este archivo crearemos la tabla usuarios en la base de datos bdusuarios

//Aquí accedemos al archivo para conectarnos a la base de datos
require_once './includes/connect.php';

try {
	$crearTablaUsuarios = "CREATE TABLE IF NOT EXISTS usuarios(
		idusuario int(255) auto_increment not null,
		nombre		varchar(255),
		apellidos	varchar(255),
		biografia	text,
		email		varchar(255),
		password	varchar(255),
		image		varchar(255),
		CONSTRAINT pk_idusuario PRIMARY KEY (idusuario)
		);";

	$conexion->exec($crearTablaUsuarios);

	echo "</br> La tabla se creó correctamente </div>";
} catch (PDOException $ex) {
	echo "La tabla NO se creó correctamente." . $ex->getMessage() . '</div>';
}
?>