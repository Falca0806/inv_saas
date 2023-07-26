<div class="container is-fluid mb-6">
	<h1 class="title">Orden de Compra</h1>
	<h2 class="subtitle">Nuevo Orden de Compra</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";

    ?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/compra_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
            <div class="column">
		    	<div class="control">
					<label>Nombre Producto</label>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Lote</label>
				  	<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required >
				</div>
		  	</div>

			<div class="column">
		    	<div class="control">
					<label>Farmacéutica</label>
				  	<input class="input" type="text" name="producto_farmace" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Precio</label>
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Cantidad</label>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha De Vencimiento</label>
				  	<input class="input" type="date" name="fecha_venc" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<label>Proveedor</label><br>
		    	<div class="select is-rounded">
				  	<select name="producto_proveedor" >
				    	<option value="" selected="" >Seleccione una opción</option>
				    	
                        <?php
                            $proveedores = conexion();
                            $proveedores = $proveedores->query("SELECT * FROM proveedor");
                            if ($proveedores->rowCount() > 0) {
                                $proveedores = $proveedores->fetchAll();
                                foreach ($proveedores as $row ) {
                                    echo '<option value="'. $row['proveedor_id'] .'" >'. $row['proveedor_nombre'] .'</option>';
                                }
                            }
                            $proveedores = null;
                        ?>
				  	</select>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>