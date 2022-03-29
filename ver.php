<?php
require_once './connect.php';
require_once 'includes/header.php';
?>

<?php
$idusuario = $_GET["idusuario"];
//Primero guardamos el id que le pasamos y luego antes de intentar mostrar nada
//comprobamos que existe, que no está vacío y que es un número.
if (!isset($idusuario) || empty($idusuario) || !is_numeric($idusuario)) {
	header("Location:listuser.php");
}

// Primero hacemos la consulta
try {
	$sql = "SELECT * FROM usuarios WHERE idusuario ={$idusuario}";
// Ejecutamos (conexion es el nombre de la conexión a la base de datos)
	$resultsquery = $conexion->query($sql);
//Revisamos OJO Preguntar por qué query
	if ($resultsquery) {
		$mensajeResultado = '<div class="alert alert-success">' .
				"La consulta se realizó correctamente." . '</div>';
		$usuarioMostrar = $resultsquery->fetch();
		if (!isset($usuarioMostrar["idusuario"]) || empty($usuarioMostrar["idusuario"])) {
			header("Location:listuser.php");
		}
	}
} catch (PDOException $ex) {
	$mensajeResultado = '<div class="alert alert-danger">' .
			"La consulta no se realizó correctamente." . '</div>';
	die();
}
echo $mensajeResultado;
?>
<?php
if ($usuarioMostrar["image"] != null) {
	echo '<h5>Imagen de perfil: </h5>';
	?>
	<img src="uploads/<?php echo $usuarioMostrar["image"] ?>" width="150" />
<?php } ?>
<h5>Nombre:</h5>
<a><?php echo $usuarioMostrar["nombre"] ?> </a>
<h5>Apellidos:</h5>
<a><?php echo $usuarioMostrar["apellidos"] ?> </a>
<h5>Correo:</h5>
<a><?php echo $usuarioMostrar["email"] ?> </a>
<h5>Biografía:</h5>
<a><?php echo $usuarioMostrar["biografia"] ?> </a>
<h5>Password:</h5>
<a><?php echo $usuarioMostrar["password"] ?> </a>
</br>
<a href="listuser.php" class="btn btn-success">Volver</a>



<?php require_once 'includes/footer.php'; ?>  