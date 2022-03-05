<?php
require_once './connect.php';
//Indica cómo fue el resultado de la consulta
$mensajeResultado = "";

// Primero hacemos la consulta
try {
	$sql = "SELECT * FROM usuarios";
	// Ejecutamos (conexion es el nombre de la conexión a la base de datos)
	$resultsquery = $conexion->query($sql);
	//Revisamos OJO Preguntar por qué query
	if ($resultsquery) {
		$mensajeResultado = '<div class="alert alert-success">' .
				"La consulta se realizó correctamente." . '</div>';
	}
} catch (PDOException $ex) {
	$mensajeResultado = '<div class="alert alert-danger">' .
			"La consulta no se realizó correctamente." . '</div>';
	die();
}
?>
<?php require 'includes/header.php' ?>

<body class="cuerpo">
	<div class="container centrar" texA>
		<div class="container cuerpo text-left">
			<p><h2><img class="alineadoTextoImagen" src= "images/user.png"width="50px"/>
				Base de Datos de Usuarios</h2></p>
		</div>
		<?php echo $mensajeResultado ?>
		<table class="table table-striped">
			<tr>
				<th>Nombre</th>
				<th>Apellidos</th>
				<th>Email</th>
			</tr>
			<?php
			while ($fila = $resultsquery->fetch()) {
				echo'<tr>';
				echo'<td>' . $fila['nombre'] . '</td>';
				echo'<td>' . $fila['apellidos'] . '</td>';
				echo'<td>' . $fila['email'] . '</td>';
				echo'</tr>';
			}
			?>
		</table>
	</div>
</body>
</html>
<?php require 'includes/footer.php'; ?>