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
	<div class="container centrar">
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
				<th>Imagen</th>
				<th>Operaciones</th>
			</tr>
			<?php while ($fila = $resultsquery->fetch()) { ?>

				<tr>
					<td><?= $fila["nombre"] ?> </td>
					<td><?= $fila["apellidos"] ?> </td>
					<td><?= $fila["email"] ?> </td>			
					<td><?php
						if ($fila["image"] != null) {
							echo '<img src="uploads/'.$fila["image"].'" width="60" />'. $fila['image'];
						}
						?>
					</td>
					<td>
						<a href="ver.php?idusuario=<?= $fila['idusuario'] ?>" class="btn btn-success">Ver</a>
						<a href="edit.php?idusuario=<?= $fila['idusuario'] ?>" class="btn btn-warning">Editar</a>
						<a href="delete.php?idusuario=<?= $fila['idusuario'] ?>" class="btn btn-danger">Eliminar</a>
					</td>
				</tr>
<?php } ?>
		</table>
	</div>
</body>
</html>
<?php require 'includes/footer.php'; ?>