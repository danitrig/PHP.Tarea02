<?php
require_once './connect.php';
//Indica cómo fue el resultado de la consulta
$mensajeResultado = "";

// Primero hacemos la consulta
try {
	$sql = "SELECT * FROM usuarios";
	// Ejecutamos (conexion es el nombre de la conexión a la base de datos)
	$resultsquery = $conexion->query($sql);

	//Para realizar la paginación primero vemos cuantos ususarios tenemos en el registro.
	$numeroUsuarios = $conexion->query("SELECT FOUND_ROWS() as total");
	$numeroUsuarios = $numeroUsuarios->fetch()['total'];

	if ($numeroUsuarios > 0) {
		$usuariosPorPagina = 3; //Para no tener que introducir muchos usuarios y probar que funciona he puesto solo 3

		if (isset($_GET["pagina"])) {
			$pagina = $_GET["pagina"];
		} else {
			$pagina = 0;
		}
		//Si solo tiene una página
		if ($pagina == 0) {
			$start = 0;
			$pagina = 1;
		} else {
			$start = ($pagina - 1) * $usuariosPorPagina;
		}
		$paginasTotales = ceil($numeroUsuarios / $usuariosPorPagina);

		$sql = "SELECT * FROM usuarios order by idusuario DESC LIMIT {$start}, {$usuariosPorPagina};";
		$resultsquery = $conexion->query($sql);
	} else {
		echo'<div class="alert alert-danger">' .
		"Aún NO existe ningún usuario" . '</div>';
	}

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
			<a href="pdf.php" class="btn btn-primary">Imprimir PDF</a>
		</div>
		</br>
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
							echo '<img src="uploads/' . $fila["image"] . '" width="60" /></br>' . $fila['image'];
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
		<?php if ($numeroUsuarios > 0) { ?>
			<!--Usamos la paginación de boopstrap-->
			<ul class="pagination">
				<?php
				if ($pagina == 1) {
					echo '<li><a class="page-link"><</a></li>';
				} else {
					echo '<li><a class="page-link" href="?pagina=' . ($pagina - 1) . '"><</a></li>';
				}

				for ($i = 1; $i <= $paginasTotales; $i++) {

					if ($pagina == $i) {
						echo '<li><a class="page-link">' . $i . '</a></li>';
					} else {
						echo '<li><a class="page-link" href="?pagina=' . $i . '">' . $i . '</a></li>';
					}
				}
				if ($pagina == $paginasTotales) {
					echo '<li><a class="page-link">></a></li>';
				} else {
					echo '<li><a class="page-link" href="?pagina=' . ($pagina + 1) . '">></a></li>';
				}
				?>
			</ul>
		<?php } ?>

	</div>
</body>
</html>
<?php require 'includes/footer.php'; ?>