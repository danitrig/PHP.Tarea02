<?php

require_once 'connect.php';

$idusuario = $_GET['idusuario'];

if (isset($_GET['idusuario']) && (is_numeric($_GET['idusuario']))) {
	try {
		$sql = "DELETE FROM usuarios WHERE idusuario=:idusuario";
		$query = $conexion->prepare($sql);
		$query->execute(['idusuario' => $idusuario]);

		if ($query) {
			header("Location:listuser.php");
		}
	} catch (PDOException $ex) {
		echo '<div class="alert alert-success">' . "El usuario NO se eliminó<br/>(" . $ex->getMessage() . ')</div>';
		die();
	}
} else {
	header("Location:listuser.php");
}
?>