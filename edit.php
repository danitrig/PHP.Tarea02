<?php
require_once 'connect.php';
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
require_once 'includes/header.php';
?>

<?php
$mensajeResultado = "";

//Cuando pulsemos Enviar guardaremos cada campo en su parámetro dentro de la BD.
if (isset($_POST['submit'])) {

	//Guardamos las variables
	//Con filter_var sanitizamos los campos, eliminando carácteres que puedan dar problemas

	$nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
	$apellidos = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);
	$biografia = filter_var($_POST["biografia"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
	$password = sha1($_POST['password']); //sha1 se usa para encriptar , como seguridad
	$image = $_FILES["image"];

	if (!empty($_POST["nombre"]) && !empty($_POST["apellidos"]) && !empty($_POST["biografia"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {

		//Instrucción SQL parametrizada
		try {
			//Definición
			$sql = "INSERT INTO usuarios(nombre, apellidos, biografia, email, password, image)
					VALUES (:nombre, :apellidos, :biografia, :email, :password, :image)";
			//Preparación
			$query = $conexion->prepare($sql);
			//Ejecución
			$query->execute([
				'nombre' => $nombre,
				'apellidos' => $apellidos,
				'biografia' => $biografia,
				'email' => $email,
				'password' => $password,
				'image' => "probando"
			]);
			//Si se realiza correctamente mostrará un mensaje satisfactorio.
			if ($query) {
				$mensajeResultado = '<div class="alert alert-success">' . "REGISTRO REALIZADO CORRECTAMENTE" . '</div>';
			}//Si no se inserta mostrará un mensaje de error.
		} catch (PDOException $ex) {
			$mensajeResultado = '<div class="alert alert-success">' . "ALGO SALIÓ MAL AL REGISTRARSE" . '</div>';
			die();
		}
	}
}
echo $mensajeResultado;
?>

<h2>Editar Usuario: <?php echo $usuarioMostrar["idusuario"] . " - " . $usuarioMostrar["nombre"] . " " . $usuarioMostrar["apellidos"]; ?></h2>
<form action="adduser.php" method="POST" enctype="multipart/form-data">

	<!-- NOMBRE -->
    <label for="nombre">Nombre: 
        <input type="text" name="nombre" class="form-control" <?php
		if (isset($_POST["nombre"])) {
			echo "value='{$_POST["nombre"]}'";
		}
		?> />
    </label>
	</br>
	<?php
	if (isset($_POST["submit"])) {
		//En preg_match es donde van metidas las expresiones regulares
		//Con filter_var sanitizamos los campos, eliminando carácteres que puedan dar problemas

		if (!empty($_POST["nombre"]) && strlen($_POST["nombre"]) <= 20 &&
				!is_numeric($_POST["nombre"]) && !preg_match("/[0-9]/", $_POST["nombre"])
		) {
			
		} else {
			echo '<div class="alert alert-danger">' . "No se ha definido nombre." . '</div>';
		}
	}
	?>
	<!-- APELLIDOS -->

	<label for="apellidos">Apellidos: 
        <input type="text" name="apellidos" class="form-control" <?php
		if (isset($_POST["apellidos"])) {
			echo "value='{$_POST["apellidos"]}'";
		}
		?> />
	</label>
	</br>

	<?php
	if (isset($_POST["submit"])) {

		if (!empty($_POST["apellidos"]) && !is_numeric($_POST["apellidos"]) &&
				!preg_match("/[0-9]/", $_POST["apellidos"])
		) {
			
		} else {
			echo '<div class="alert alert-danger">' . "No se han definido apellidos." . '</div>';
		}
	}
	?>

	<!-- BIOGRAFIA -->
	<label for="biografia">Biografía:
		<textarea name="biografia" class="form-control"><?php
			if (isset($_POST["biografia"])) {
				echo $_POST["biografia"];
			}
			?></textarea>
	</label>
	</br>

	<?php
	if (isset($_POST["submit"])) {
		if (!empty($_POST["biografia"])) {
			
		} else {
			echo '<div class="alert alert-danger">' . "No se ha definido biografía." . '</div>';
		}
	}
	?>

	<!-- CORREO -->
	<label for="email">Correo Electrónico:
		<input type="email" name="email" class="form-control" <?php
		if (isset($_POST["email"])) {
			echo "value='{$_POST["email"]}'";
		}
		?> />
	</label>
	</br>

	<?php
	if (isset($_POST["submit"])) {


		if (!empty($_POST["email"]) && preg_match("/^\w+@[a-zA-Z]+\.[a-zA-Z]{2,3}$/", $_POST["email"])) {
			
		} else {
			echo '<div class="alert alert-danger">' . "No ha introducido un correo válido." . '</div>';
		}
	}
	?>

	<!-- CONTRASEÑA -->
	<label for="password">Contraseña:
		<input type="password" name="password" class="form-control" />
	</label>
	</br>

	<?php
	if (isset($_POST["submit"])) {
		if (!empty($_POST["password"]) && strlen($_POST["password"]) >= 6) {
			
		} else {
			echo '<div class="alert alert-danger">' . "No ha introducido una contraseña válida." . '</div>';
		}
	}
	?>

	<!-- IMAGEN -->
	<label for="image">Imagen:
		<input type="file" name="image" class="form-control" 
			   </label>
		</br>

		<?php
		if (isset($_POST["submit"])) {

			if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
				
			} else {
				echo '<div class="alert alert-danger">' . "No ha enviado ninguna imagen." . '</div>';
			}
		}
		?>
		<input type="submit" value="Enviar" name="submit" class="btn btn-success"/>
		<a href="listuser.php" class="btn btn-danger">Cancelar</a>
</form>

<?php require 'includes/footer.php' ?>

   