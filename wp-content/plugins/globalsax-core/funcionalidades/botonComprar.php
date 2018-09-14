<?
add_action( 'woocommerce_after_cart_totals', 'tl_realizarPedido' );
function tl_realizarPedido() {
 echo '<div class="">';
 echo ' <a onclick="return EnviarPedido()" style="margin-top:20px" class="fusion-button button-default fusion-button-default-size button">Realizar Pedido</a>';
 echo '</div>';
 echo "<script>
 function EnviarPedido() {
	var data = {
		'action': 'get_enviar_pedido',
		'whatever': 'casa'      // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post('".admin_url( 'admin-ajax.php' )."', data, function(response) {
		//alert('El pedido se ha realizado con exito: ' + response);
		//alert('El pedido se ha realizado con exito');
		if(alert('El pedido se ha realizado con exito!')){}
        else    window.location = '".esc_url( get_permalink(11963) )."'; 
	})
 }</script>";
}
/**LLAMADA AJAX**/
add_action('wp_ajax_get_enviar_pedido', 'ajax_get_enviar_pedido');
add_action('wp_ajax_nopriv_get_enviar_pedido', 'ajax_get_enviar_pedido');

function ajax_get_enviar_pedido(){
	global $woocommerce; 
	$woocommerce->cart->empty_cart(); 
}

/**Eliminar boton de finalizar compra***/
remove_action( 'woocommerce_proceed_to_checkout','woocommerce_button_proceed_to_checkout', 20);
?>