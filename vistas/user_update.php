<?php
    require_once "./php/main.php";

    $id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0 ;
    $id = limpiar_cadena($id);
?>
<div class="container is-fluid mb-6">
    <?php if ($id == $_SESSION['id']) { ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar datos de cuenta</h2>
    <?php }else{ ?>
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Actualizar usuario</h2>
    <?php } ?>
</div>

<div class="container pb-6 pt-6">
    <?php
        include "./include/btn_back.php";
        
        $veri_usuario = conexion();
        $veri_usuario = $veri_usuario->query("SELECT * FROM usuario WHERE usuario_id ='$id'");

        if ($veri_usuario->rowCount() > 0) {
            $datos = $veri_usuario->fetch();
    ?>
	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/usuario_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" value="<?php echo $datos['usuario_id']; ?>" name="usuario_id" required >
		
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
				  	<input class="input" type="text" name="usuario_nombre" value="<?php echo $datos['usuario_nombre']; ?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
				  	<input class="input" type="text" name="usuario_apellido" value="<?php echo $datos['usuario_apellido']; ?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="usuario_usuario" value="<?php echo $datos['usuario_usuario']; ?>" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="usuario_email" value="<?php echo $datos['usuario_email']; ?>" maxlength="70" >
				</div>
		  	</div>
		</div>
		<br>
		<p class="has-text-centered">
			Si desea actualizar la contraseña de este usuario por favor llene los 2 campos. Si no desea actualizar la contraseña deje los campos vacíos.
		</p>
		<br>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Contraseña</label>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Repetir Contraseña</label>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		</div>
		<br>
		<p class="has-text-centered">
			Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CONTRASEÑA con la que ha iniciado sesión
		</p>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Contraseña</label>
				  	<input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
    <?php
        }else{
            include "./include/error_alert.php";
        }
        $veri_usuario = null;
    ?>


</div>