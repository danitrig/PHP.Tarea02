<?php
//En este archivo se realiza la conexión a la base de datos creada (bdusuarios)

//Conexión			
$dbHost = 'localhost';
$dbName = 'bdusuarios';
$dbUser = 'root';
$dbPass = '';

try {
	$conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo'<div class="alert alert-success">' .
	"La conexión con la Base de Datos bdusuarios es CORRECTA." . '</div>';
	
} catch (PDOException $ex) {
	echo'<div class="alert alert-danger">' .
	"Algo fue MAL. La conexión con la Base de Datos bdusuarios es ERRÓNEA." .
	$ex->getMessage() . '</div>';
}
?>