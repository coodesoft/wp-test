<?php
define('GLOBALSAX_CORE','globalsax-core');

function theme_settings_page(){
    ?>
	    <div class="wrap">
	    <h1>GLOBALSAX - CORE</h1>
	    <form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");
	            submit_button();
	        ?>
	    </form>
		</div>
	<?php
}

function display_opcion_generar_cotizacion(){
	?>
		<input type="button" name="generar_cotizacion" value="Sincronizar productos" onclick="sincronizarProductos()"/>
		<script>
			function sincronizarProductos(){
				jQuery.ajax({
				  type : "post",
				  dataType : "json",
				  url : "<?php echo admin_url('admin-ajax.php'); ?>",
				  data : 'action=get_sincronizar_producto&security=<?php echo wp_create_nonce('globalsax'); ?>',
				  success: function(response) {
					location.reload();
				  }
				});
			}
		</script>
	<?php
}

function display_opcion_descargar_lista_precios(){
	?>
		<input type="button" name="generar_precios" value="Sincronizar archivo lista precios" onclick="sincronizarListaPrecios()"/>
		<script>
			function sincronizarListaPrecios(){
				jQuery.ajax({
				  type : "post",
				  dataType : "json",
				  url : "<?php echo admin_url('admin-ajax.php'); ?>",
				  data : 'action=get_sincronizar_precios&security=<?php echo wp_create_nonce('globalsax'); ?>',
				  success: function(response) {
					//location.reload();
					alert("Lista de precios sincronizada con exito");
				  }
				});
			}
		</script>
	<?php
}
function display_theme_panel_fields(){
	add_settings_section("section", "Configuracion de opciones de sistema", null, "theme-options");
	/**/
    add_settings_field("opcion_1", "Sincronizar lista de productos de todos los productos! - Es un proceso lento y que genera mucho estress a la base de datos, por favor sincronizar cuando este seguro que se debe hacer.", "display_opcion_generar_cotizacion","theme-options", "section");
	register_setting("section", "opcion_1");
	add_settings_field("opcion_2", "Actualizar lista de precios! - Es un proceso donde se descargan los archivos que serviran para sincronizar la lista de precios", "display_opcion_descargar_lista_precios","theme-options", "section");
	register_setting("section", "opcion_2");
	//add_settings_field("opcion_2", "1) Opcion 2", "display_opcion_generar_cotizacion","theme-options", "section");
	//register_setting("section", "opcion_2");
	//add_settings_field("opcion_3", "1) Opcion 3", "display_opcion_generar_cotizacion","theme-options", "section");
	//register_setting("section", "opcion_3");
	//add_settings_field("opcion_4", "1) Opcion 4", "display_opcion_generar_cotizacion","theme-options", "section");
    //register_setting("section", "opcion_4");
	/**/
}

add_action("admin_init", "display_theme_panel_fields");
?>
