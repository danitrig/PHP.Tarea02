<?php
ob_start(); //Empezamos a llenar el buffer
//Conexión			
$dbHost = 'localhost';
$dbName = 'bdusuarios';
$dbUser = 'root';
$dbPass = '';

try {
	$conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
	
}
// Primero hacemos la consulta
try {
	$sql = "SELECT * FROM usuarios";
	$resultsquery = $conexion->query($sql);
} catch (PDOException $ex) {
	die();
}
?>

<!DOCTYPE html>
<html>

	<head lang="es">
		<meta charset="utf-8" />
		<title>Ejercicios Formulario. Web PHP</title>
		<!--Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<!-- Referencia a la CDN dela hoja de estilos de Bootstrap-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	</head>

	<body> 
		<div class="container">
			<h1>Listado en PDF</h1>
			<hr/>
			<table class="table table-striped">
				<tr>
					<th>Nombre</th>
					<th>Apellidos</th>
					<th>Email</th>
					<th>Imagen</th>
				</tr>
				<?php while ($fila = $resultsquery->fetch()) { ?>

					<tr>
						<td><?= $fila["nombre"] ?> </td>
						<td><?= $fila["apellidos"] ?> </td>
						<td><?= $fila["email"] ?> </td>			
						<td><?php
							if ($fila["image"] != null) {
								echo '<img src="http://' . $_SERVER['HTTP_HOST'] . '/PHP.Tarea02/uploads/' . $fila["image"] . '" width="60" /></br>';
							}
							?>
						</td>
					</tr>
				<?php } ?>
			</table>
	</body>
</html>

<?php
//Guardamos el html en esta variable con ob_get_clean
$html = ob_get_clean();

//La ruta donde descomprimimos el complemento
include_once "./dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf(); //Creamos objeto DomPDF

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream("ListaUsuarios.pdf", array("Attachment" => false));
?>