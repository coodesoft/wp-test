<?php
add_action('user_register', 'my_user_register');
function my_user_register($user_id) {
	update_user_meta($user_id, 'sincronizar_cliente',"1");
}

add_action( 'wp_loaded', 'my_new_cliente');
function my_new_cliente() {
	$user_id = get_current_user_id();
	$post_id = "user_".$user_id;
	if(get_user_meta($user_id, 'sincronizar_cliente',true) == "1"){
		update_user_meta($user_id, 'sincronizar_cliente',"0");
		$idClientegsx = get_user_meta($user_id,"billing_id_cliente_gs",true);
		if (isset($idClientegsx)){
			$url = $GLOBALS['cgv']['endpoint_datos_usuario'];
			$url = $url . $idClientegsx; 
			/**CURL**/
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL,$url);
			$json = curl_exec($ch);
			if(curl_exec($ch) === false)
			{
				echo 'Curl error: ' . curl_error($ch);
			}
			curl_close($ch);
			/***********/
			/**Convertir a JSON**/
			$cliente = json_decode($json, true);
			update_user_meta($user_id, 'first_name', $cliente['Name']);
			$lista_precios = $cliente['PriceList'];
			$listaPrecioString = "";
			foreach ($lista_precios as $precio) {
                $listaPrecioString = $listaPrecioString.$precio.";";
            }
		    $value = update_field('lista_precios',$listaPrecioString, $post_id );
			/***/
		}
	}
}
?>