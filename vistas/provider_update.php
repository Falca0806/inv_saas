<div class="container is-fluid mb-6">
	<h1 class="title">Proveedores</h1>
	<h2 class="subtitle">Actualizar Proveedor</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        include "./include/btn_back.php";

        require_once "./php/main.php";

        $id = (isset($_GET['provider_id_up'])) ? $_GET['provider_id_up'] : 0 ;
        $id = limpiar_cadena($id);

        $veri_proveedor = conexion();
        $veri_proveedor = $veri_proveedor->query("SELECT * FROM proveedor WHERE proveedor_id ='$id'");

        if ($veri_proveedor->rowCount() > 0) {
            $datos = $veri_proveedor->fetch();
    ?>
	<div class="form-rest mb-6 mt-6"></div>
	<form action="./php/proveedor_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="proveedor_id" value="<?php echo $datos['proveedor_id']; ?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Razón Social</label>
				  	<input class="input" type="text" name="proveedor_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required value="<?php echo $datos['proveedor_nombre']; ?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Ubicación</label>
				  	<input class="input" type="text" name="proveedor_direccion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150" value="<?php echo $datos['proveedor_direccion']; ?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
				  	<input class="input" type="text" name="proveedor_telef" pattern="[0-9]{11,11}" maxlength="11" value="<?php echo $datos['proveedor_telef']; ?>">
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
        $veri_proveedor = null;
    ?>
</div>