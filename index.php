<?php require 'includes/header.php' ?>


<h2>Formulario</h2>
<form action="recibir.php" method="POST" enctype="multipart/form-data">

    <label for="nombre">Nombre:
        <input type="text" name="nombre" class="form-control" />
    </label>

    <label for="apellidos">Apellidos: 
        <input type="text" name="apellidos" class="form-control" />
    </label>
    </br>

    <label for="biografia">Biografía:
        <textarea name="biografia" class="form-control"></textarea>
    </label>
    </br>

    <label for="email">Correo Electrónico:
        <input type="email" name="email" class="form-control" />
    </label>
    </br>

    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control" />
    </label>
    </br>

    <label for="image">Imagen:
        <input type="file" name="image" class="form-control" />
    </label>
    </br>

    <input type="submit" value="Enviar" name="submit" class="btn btn-success" />


</form>

<?php require 'includes/footer.php'; ?>