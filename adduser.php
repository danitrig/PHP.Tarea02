<?php
require_once 'includes/header.php';
require_once 'connect.php';
?>
<?php
$errors = array();
$mensajeResultado = "";

function mostrarErrores($errors, $campo) {
	if (isset($errors[$campo]) && !empty($campo)) {
		$alert = '<div class="alert alert-danger">' . $errors[$campo] . '</div>';
	} else {
		$alert = '';
	}
	return $alert;
}

function mantenerValores($errors, $campo, $textarea = false) {
	if (isset($errors) && count($errors) > 0 && isset($_POST[$campo])) {
		if ($textarea != false) {
			echo $_POST[$campo];
		} else {
			echo "value='{$_POST[$campo]}'";
		}
	}
}

if (isset($_POST["submit"])) {
//En preg_match es donde van metidas las expresiones regulares
//Con filter_var sanitizamos los campos, eliminando carácteres que puedan dar problemas

	$_POST["nombre"] = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);

	if (!empty($_POST["nombre"]) && strlen($_POST["nombre"]) <= 20 &&
			!is_numeric($_POST["nombre"]) && !preg_match("/[0-9]/", $_POST["nombre"])
	) {
		$nombre_validate = true;
	} else {
		$nombre_validate = false;

		$errors["nombre"] = "El nombre introducido no es válido.";
	}

	$_POST["apellidos"] = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);

	if (!empty($_POST["apellidos"]) && !is_numeric($_POST["apellidos"]) &&
			!preg_match("/[0-9]/", $_POST["apellidos"])) {
		$apellidos_validate = true;
	} else {
		$apellidos_validate = false;

		$errors["apellidos"] = "Los apellidos introducidos no son válidos.";
	}

	$_POST["biografia"] = filter_var($_POST["biografia"], FILTER_SANITIZE_STRING);

	if (!empty($_POST["biografia"])) {
		$biografia_validate = true;
	} else {
		$biografia_validate = false;

		$errors["biografia"] = "La biografía introducida no es válida.";
	}

	$_POST["email"] = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

	if (!empty($_POST["email"]) && preg_match("/^\w+@[a-zA-Z]+\.[a-zA-Z]{2,3}$/", $_POST["email"])) {
		$email_validate = true;
	} else {
		$email_validate = false;

		$errors["email"] = "El correo introducido no es válido.";
	}

	if (!empty($_POST["password"]) && strlen($_POST["password"]) >= 6) {
		$password_validate = true;
	} else {
		$password_validate = false;

		$errors["password"] = "La contraseña introducida no es válida.";
	}

	$image = null;
	if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

		//Comprobamos si existe el directorio upload, sino existe lo creamos así.
		if (!is_dir("uploads")) {
			$dir = mkdir("uploads", 0777, true);
		}//Si existe el directorio movemos el archivo a ese directorio.
		else {
			//Le pasamos el nombre por defecto y le concatenamos time() para que no
			//sobreescriba el archivo si subimos una imagen con el mismo nombre.
			$image = time() . " - " . $_FILES["image"]["name"];
			//Con esto movemos el fichero que tenemos en la carpeta temporal a la ruta que especificamos.
			$mover = move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);

			if ($mover) {
				$image_validate = true;
			} else {
				$image_validate = false;
				$errors["image"] = "La imagen introducida no es válida.";
			}
		}
	}
}
//INSERTAR USUARIOS EN LA BASE DE DATOS CREADA
if (count($errors) == 0 && isset($_POST['submit'])) {

	//Guardamos las variables
	//Con filter_var sanitizamos los campos, eliminando carácteres que puedan dar problemas

	$nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
	$apellidos = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);
	$biografia = filter_var($_POST["biografia"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
	$password = sha1($_POST['password']); //sha1 se usa para encriptar , como seguridad
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
			'image' => $image
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
echo $mensajeResultado;
?>

<h2>Crear Nuevo Usuario</h2>
<form action="adduser.php" method="POST" enctype="multipart/form-data">

	<!-- Nombre -->
    <label for="nombre">Nombre:
        <input type="text" name="nombre" class="form-control" 
			   <?php mantenerValores($errors, "nombre") ?>/>
			   <?php echo mostrarErrores($errors, "nombre"); ?>
    </label>
	</br>

	<!-- Apellidos -->
    <label for="apellidos">Apellidos: 
        <input type="text" name="apellidos" class="form-control" 
			   <?php mantenerValores($errors, "apellidos") ?>/>
			   <?php echo mostrarErrores($errors, "apellidos"); ?>
    </label>
    </br>

	<!-- Biografía -->
    <label for="biografia">Biografía:
        <textarea name="biografia" class="form-control"><?php mantenerValores($errors, "biografia", true) ?></textarea>
		<?php echo mostrarErrores($errors, "biografia"); ?>
    </label>
    </br>
	<!-- Correo -->
    <label for="email">Correo Electrónico:
        <input type="email" name="email" class="form-control"
			   <?php mantenerValores($errors, "email") ?>/>
			   <?php echo mostrarErrores($errors, "email"); ?>
    </label>
    </br>

	<!-- Contraseña -->
    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control" />
		<?php echo mostrarErrores($errors, "password"); ?>
    </label>
    </br>

	<!-- Imagen -->
    <label for="image">Imagen:
        <input type="file" name="image" class="form-control" />
    </label>
    </br>

    <input type="submit" value="Enviar" name="submit" class="btn btn-success" />


</form>

<?php require_once 'includes/footer.php' ?>