<?php
/**
  * Plugin Name: Woocommerce Variations Table - Grid
* Plugin URI: http://codecanyon.net/item/woocommerce-variations-to-table-grid/10494620
* Description: Plugin to turn Woocommerce normal variations select menus to table - grid
* Version: 1.3.4
* Author: Spyros Vlachopoulos
* Author URI: http://www.nitroweb.gr
* License: GPL2
* Text Domain: vartable
*/

    
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

add_action('init', 'vt_StartSession');
function vt_StartSession() {
  if ( vt_is_session_started() === FALSE ) { session_start(); }
}

add_action('wp_logout', 'vt_EndSession');
add_action('wp_login', 'vt_EndSession');

function vt_EndSession() {
  if ( vt_is_session_started() === TRUE ) {
    session_destroy ();
  }
}

// Load plugin textdomain
add_action( 'plugins_loaded', 'vartable_load_textdomain' );
function vartable_load_textdomain() {
  load_plugin_textdomain( 'vartable', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
  
  remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
  add_action( 'woocommerce_variable_add_to_cart', 'vt_woocommerce_variable_add_to_cart', 30 );

}


function vartable_activate() {
  
  $vartable_order = array (
    'vartable_sku' => 'SKU',
    'vartable_thumb' => 'Thumbnail',
    'vartable_stock' => 'Stock',
    'vartable_variations' => 'Variations',
    'vartable_price' => 'Price',
    'vartable_total' => 'Total',
    'vartable_offer' => 'Offer Image',
    'vartable_qty' => 'Quantity',
    'vartable_gift' => 'Gift Wrap',
    'vartable_wishlist' => 'Wishlist',
    'vartable_cart' => 'Add to Cart Button'
  );
  
  
  // set options only if they do not exist
  if (get_option('vartable_disabled') === false) {
    update_option( 'vartable_disabled', 0 );
  }
  if (get_option('vartable_thumb') === false) {
    update_option( 'vartable_thumb', 1 );
  }
  if (get_option('vartable_thumb_size') === false) {
    update_option( 'vartable_thumb_size', 80 );
  }
  if (get_option('vartable_price') === false) {
    update_option( 'vartable_price', 1 );
  }
  if (get_option('vartable_total') === false) {
    update_option( 'vartable_total', 0 );
  }
  if (get_option('vartable_cart') === false) {
    update_option( 'vartable_cart', 1 );
  }
  if (get_option('vartable_qty') === false) {
    update_option( 'vartable_qty', 1 );
  }
  if (get_option('vartable_order') === false) {
    update_option( 'vartable_order', $vartable_order );
  }
  if (get_option('vartable_head') === false) {
    update_option( 'vartable_head', 1 );
  }
  if (get_option('vartable_sorting') === false) {
    update_option( 'vartable_sorting', 1 );
  }
  if (get_option('vartable_default_qty') === false) { 
    update_option('vartable_default_qty', 1); 
  }
  if (get_option('vartable_qty_control') === false) { 
    update_option('vartable_qty_control', 0); 
  }
  if (get_option('vartable_globalposition') === false) { 
    update_option('vartable_globalposition', 'bottom'); 
  }
  if (get_option('vartable_globalcart_status') === false) { 
    update_option('vartable_globalcart_status', 0); 
  }
  if (get_option('vartable_desc_inline') === false) { 
    update_option('vartable_desc_inline', '0'); 
  }
  if (get_option('vartable_weight') === false) { 
    update_option('vartable_weight', '0'); 
  }
  if (get_option('vartable_dimensions') === false) { 
    update_option('vartable_dimensions', '0'); 
  }
  if (get_option('vartable_position') === false) { 
    update_option('vartable_position', 'side'); 
  }
   
}
register_activation_hook( __FILE__, 'vartable_activate' );


include ('grid_options_page.php');

// create the shortcode
function vartable_func( $atts ) {
    $a = shortcode_atts( array(
    'id'                    => 0,
    'disabled'              => '',
    'categories_exc'        => '',
    'categories'            => '',
    'sku'                   => '',
    'thumb'                 => '',
    'thumb_size'            => '',
    'stock'                 => '',
    'in_stock_text'         => '',
    'out_stock_text'        => '',
    'low_stock_text'        => '',
    'low_stock_thresh'      => '',
    'hide_zero'             => '',
    'hide_outofstock'       => '',
    'vartable_zero_to_out'  => '',
    'price'                 => '',
    'offer'                 => '',
    'image'                 => '',
    'qty'                   => '',
    'default_qty'           => '',
    'vartable_qty_control'  => '',
    'cart'                  => '',
    'globalcart'            => '',
    'globalcart_status'     => '',
    'globalposition'        => '',
    'wishlist'              => '',
    'gift'                  => '',
    'order'                 => '',
    'ajax'                  => '',
    'desc'                  => '',
    'weight'                => '',
    'dimensions'            => '',
    'vartable_position'     => '',
    'desc_inline'           => '',
    'head'                  => '',
    'customhead'            => '',
    'sorting'               => '',
    'shortcd'               => 1,
    'title'                 => 0
    ), $atts );

    return (vt_woocommerce_variable_add_to_cart($a));
}
add_shortcode( 'vartable', 'vartable_func' );



function vartableall_func( $atts ) {
    $a = shortcode_atts( array(
    'disabled'              => '',
    'categories_exc'        => '',
    'categories'            => '',
    'sku'                   => '',
    'thumb'                 => '',
    'thumb_size'            => '',
    'stock'                 => '',
    'in_stock_text'         => '',
    'out_stock_text'        => '',
    'low_stock_text'        => '',
    'low_stock_thresh'      => '',
    'hide_zero'             => '',
    'hide_outofstock'       => '',
    'vartable_zero_to_out'  => '',
    'price'                 => '',
    'offer'                 => '',
    'image'                 => '',
    'qty'                   => '',
    'default_qty'           => '',
    'vartable_qty_control'  => '',
    'cart'                  => '',
    'globalcart'            => '',
    'globalcart_status'     => '',
    'globalposition'        => '',
    'wishlist'              => '',
    'gift'                  => '',
    'order'                 => '',
    'ajax'                  => '',
    'desc'                  => '',
    'weight'                => '',
    'dimensions'            => '',
    'vartable_position'     => '',
    'desc_inline'           => '',
    'head'                  => '',
    'customhead'            => '',
    'sorting'               => '',
    'shortcd'               => 1,
    'title'                 => 0
    ), $atts );
    
  $args = array(
    'posts_per_page' => -1, 
    'post_type' => 'product',
    'status' => 'publish',
    'fields'       => 'ids'
  );
  
  if ($a['hide_zero'] == 1 || ($a['hide_zero'] == '' && get_option('vartable_hide_zero') == 1)) {
    $args['meta_key']     = '_min_price_variation_id';
    $args['meta_value']   = 0;
    $args['meta_compare'] = '>';
  }
  
  $args = apply_filters( 'vartableall_query_args', $args, $a);
  
  if (!empty($a['categories'])) {
    
    $args['tax_query'] = array(
      array(
          'taxonomy'  => 'product_cat',
          'field'     => 'term_id', //This is optional, as it defaults to 'term_id'
          'terms'     => explode(',',$a['categories']),
          'operator'  => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
      )
    );
  }
  
  $posts_array = get_posts( $args ); 
  
  $alltables = '';
  
  foreach ( $posts_array as $post_id ) {
    
    $a['id'] = $post_id;
    $alltables .= vt_woocommerce_variable_add_to_cart($a).'
      <hr class="clearfix vartablesplit" />
    ';
  }
  


  return ($alltables);
}
add_shortcode( 'vartableall', 'vartableall_func' );



function vt_woocommerce_variable_add_to_cartv1($allsets) {
  global $product, $post, $woocommerce;
  
  $out = '';
  $form = '';
  $vtrand = rand(1, 100000);
  
  
  // get values from shortcode
  if ($allsets) {
    $vartable_id              = $allsets['id'];
    $vartable_disabled        = $allsets['disabled'];
    $vartable_categories_exc  = $allsets['categories_exc'];
    $vartable_roles_exc       = $allsets['roles_exc'];
    $vartable_categories      = $allsets['categories'];
    $vartable_sku             = $allsets['sku'];
    $vartable_thumb           = $allsets['thumb'];
    $vartable_thumb_size      = $allsets['thumb_size'];
    $vartable_stock           = $allsets['stock'];
    $vartable_in_stock_text   = $allsets['in_stock_text'];
    $vartable_out_stock_text  = $allsets['out_stock_text'];
    $vartable_low_stock_text  = $allsets['low_stock_text'];
    $vartable_low_stock_thresh= $allsets['low_stock_thresh'];
    $vartable_hide_zero       = $allsets['hide_zero'];
    $vartable_hide_outofstock = $allsets['hide_outofstock'];
    $vartable_zero_to_out     = $allsets['vartable_zero_to_out'];
    $vartable_price           = $allsets['price'];
    $vartable_total           = $allsets['total'];
    $vartable_offer           = $allsets['offer'];
    $vartable_image           = $allsets['image'];
    $vartable_qty             = $allsets['qty'];
    $vartable_default_qty     = $allsets['default_qty'];
    $vartable_qty_control     = $allsets['vartable_qty_control'];
    $vartable_cart            = $allsets['cart'];
    $vartable_globalcart      = $allsets['globalcart'];
    $vartable_globalcart_status = $allsets['globalcart'];
    $vartable_globalposition  = $allsets['globalposition'];
    $vartable_wishlist        = $allsets['wishlist'];
    $vartable_gift            = $allsets['gift'];
    $vartable_order           = $allsets['order'];
    $vartable_ajax            = $allsets['ajax'];
    $vartable_desc            = $allsets['desc'];
    $vartable_weight          = $allsets['weight'];
    $vartable_dimensions      = $allsets['dimensions'];
    $vartable_position        = $allsets['vartable_position'];
    $vartable_desc_inline     = $allsets['desc_inline'];
    $vartable_head            = $allsets['head'];
    $vartable_customhead      = $allsets['customhead'];
    $vartable_sorting         = $allsets['sorting'];
    $vartable_shortcd         = $allsets['shortcd'];
    $vartable_title           = $allsets['title'];
  } else {
    $vartable_id              = null;
    $vartable_disabled        = null;
    $vartable_categories_exc  = null;
    $vartable_roles_exc       = null;
    $vartable_categories      = null;
    $vartable_sku             = null;
    $vartable_thumb           = null;
    $vartable_thumb_size      = null;
    $vartable_stock           = null;
    $vartable_in_stock_text   = null;
    $vartable_out_stock_text  = null;
    $vartable_low_stock_text  = null;
    $vartable_low_stock_thresh= null;
    $vartable_hide_zero       = null;
    $vartable_hide_outofstock = null;
    $vartable_zero_to_out     = null;
    $vartable_price           = null;
    $vartable_total           = null;
    $vartable_offer           = null;
    $vartable_image           = null;
    $vartable_qty             = null;
    $vartable_default_qty     = null;
    $vartable_qty_control     = null;
    $vartable_cart            = null;
    $vartable_globalcart      = null;
    $vartable_globalcart_status = null;
    $vartable_globalposition  = null;
    $vartable_wishlist        = null;
    $vartable_gift            = null;
    $vartable_order           = null;
    $vartable_ajax            = null;
    $vartable_desc            = null;
    $vartable_weight          = null;
    $vartable_dimensions      = null;
    $vartable_position        = null;
    $vartable_desc_inline     = null;
    $vartable_head            = null;
    $vartable_customhead      = null;
    $vartable_sorting         = null;
    $vartable_shortcd         = null;
    $vartable_title           = null;
  }
  
  // check if it is a shortcode and if an id has been set
  if ($vartable_id != null) {
    $_pf = new WC_Product_Factory();  
    $product = $_pf->get_product($vartable_id);
  }
  
  if (!is_object($product)) {
    return (__('No product object found', 'vartable'));
  }
  
  $product_cats = wp_get_post_terms( $product->id, 'product_cat' );
  // get all ids
  foreach($product_cats as $pcdata) {
    $pcids[] = $pcdata->term_id;
  }
    

  // make sure that quantity is set
  if (get_option('vartable_default_qty') === false) { update_option('vartable_default_qty', 1); }
  if (get_option('vartable_qty_control') === false) { update_option('vartable_qty_control', 0); }
    
    
    $vartable_disabled        = ($vartable_disabled == null         ? get_option('vartable_disabled') : $vartable_disabled);
    $vartable_categories_exc  = ($vartable_categories_exc == null   ? get_option('vartable_categories_exc') : $vartable_categories_exc);
    $vartable_roles_exc       = ($vartable_roles_exc == null   ? get_option('vartable_roles_exc') : $vartable_roles_exc);
    $vartable_sku             = ($vartable_sku == null              ? get_option('vartable_sku') : $vartable_sku);
    $vartable_thumb           = ($vartable_thumb == null            ? get_option('vartable_thumb') : $vartable_thumb);
    $vartable_thumb_size      = ($vartable_thumb_size == null       ? get_option('vartable_thumb_size') : $vartable_thumb_size);
    $vartable_stock           = ($vartable_stock == null            ? get_option('vartable_stock') : $vartable_stock);
    $vartable_in_stock_text   = ($vartable_in_stock_text == null    ? get_option('vartable_in_stock_text') : $vartable_in_stock_text);
    $vartable_out_stock_text  = ($vartable_out_stock_text == null   ? get_option('vartable_out_stock_text') : $vartable_out_stock_text);
    $vartable_low_stock_text  = ($vartable_low_stock_text == null   ? get_option('vartable_low_stock_text') : $vartable_low_stock_text);
    $vartable_low_stock_thresh= ($vartable_low_stock_thresh == null ? get_option('vartable_low_stock_thresh') : $vartable_low_stock_thresh);
    $vartable_hide_zero       = ($vartable_hide_zero == null        ? get_option('vartable_hide_zero') : $vartable_hide_zero);
    $vartable_hide_outofstock = ($vartable_hide_outofstock == null  ? get_option('vartable_hide_outofstock') : $vartable_hide_outofstock);
    $vartable_zero_to_out     = ($vartable_zero_to_out == null      ? get_option('vartable_zero_to_out') : $vartable_zero_to_out);
    $vartable_price           = ($vartable_price == null            ? get_option('vartable_price') : $vartable_price);
    $vartable_total           = ($vartable_total == null            ? get_option('vartable_total') : $vartable_total);
    $vartable_offer           = ($vartable_offer == null            ? get_option('vartable_offer') : $vartable_offer);
    $vartable_image           = ($vartable_image == null            ? get_option('vartable_image') : $vartable_image);
    $vartable_qty             = ($vartable_qty == null              ? get_option('vartable_qty') : $vartable_qty);
    $vartable_default_qty     = ($vartable_default_qty == null      ? get_option('vartable_default_qty') : $vartable_default_qty);
    $vartable_qty_control     = ($vartable_qty_control == null      ? get_option('vartable_qty_control') : $vartable_qty_control);
    $vartable_cart            = ($vartable_cart == null             ? get_option('vartable_cart') : $vartable_cart);
    $vartable_globalcart      = ($vartable_globalcart == null       ? get_option('vartable_globalcart') : $vartable_globalcart);
    $vartable_globalcart_status = ($vartable_globalcart_status == null ? get_option('vartable_globalcart_status') : $vartable_globalcart_status);
    $vartable_globalposition  = ($vartable_globalposition == null   ? get_option('vartable_globalposition') : $vartable_globalposition);
    $vartable_wishlist        = ($vartable_wishlist == null         ? get_option('vartable_wishlist') : $vartable_wishlist);
    $vartable_gift            = ($vartable_gift == null             ? get_option('vartable_gift') : $vartable_gift);
    $vartable_order           = ($vartable_order == null            ? get_option('vartable_order') : $vartable_order);
    $vartable_ajax            = ($vartable_ajax == null             ? get_option('vartable_ajax') : $vartable_ajax);
    $vartable_desc            = ($vartable_desc == null             ? get_option('vartable_desc') : $vartable_desc);
    $vartable_weight          = ($vartable_weight == null           ? get_option('vartable_weight') : $vartable_weight);
    $vartable_dimensions      = ($vartable_dimensions == null       ? get_option('vartable_dimensions') : $vartable_dimensions);
    $vartable_position        = ($vartable_position == null         ? get_option('vartable_dimensions') : $vartable_position);
    $vartable_desc_inline     = ($vartable_desc_inline == null      ? get_option('vartable_desc_inline') : $vartable_desc_inline);
    $vartable_head            = ($vartable_head == null             ? get_option('vartable_head') : $vartable_head);
    $vartable_customhead      = ($vartable_customhead == null       ? get_option('vartable_customhead') : $vartable_customhead);
    $vartable_sorting         = ($vartable_sorting == null          ? get_option('vartable_sorting') : $vartable_sorting);
    
    // gift wrap option
    $default_message            = '{checkbox} '. sprintf( __( 'Gift wrap this item for %s?', 'woocommerce-product-gift-wrap' ), '{price}' );
    $gift_wrap_enabled          = get_option( 'product_gift_wrap_enabled' ) == 'yes' ? true : false;
		$gift_wrap_cost             = get_option( 'product_gift_wrap_cost', 0 );
		$product_gift_wrap_message  = get_option( 'product_gift_wrap_message');
    
    if ( ! $product_gift_wrap_message ) {
			$product_gift_wrap_message = $default_message;
		}
    
    $is_wrappable = get_post_meta( $product->id, '_is_gift_wrappable', true );
    
		if ( $is_wrappable == '' && $gift_wrap_enabled ) {
			$is_wrappable = 'yes';
		}
    
    //  if the table is disabled for this product display the default select menus
    $checkcat = array();
    if (is_array($vartable_categories_exc) && is_array($pcids)) {
      $checkcat = array_intersect($pcids, $vartable_categories_exc);
    }
    
    $checkrole = array();
    if (is_array($vartable_roles_exc) && is_user_logged_in()) {
      $user_info = get_userdata(get_current_user_id());
      $checkrole = array_intersect($user_info->roles, $vartable_roles_exc);
    }
    if (!is_user_logged_in() && is_array($vartable_roles_exc) && in_array('guest', $vartable_roles_exc)) {
      $checkrole['guest'] = 'guest';
    }
    
    if ( 
      ((get_post_meta($product->id, 'disable_variations_table', true) == 1 || !empty($checkcat)) || $vartable_disabled == 1 || !empty($checkrole)) 
      && get_post_meta($product->id, 'disable_variations_table', true) != 2 
      && $vartable_shortcd != 1) {
      // Enqueue variation scripts
      wp_enqueue_script( 'wc-add-to-cart-variation' );

      // Load the template
      wc_get_template( 'single-product/add-to-cart/variable.php', array(
          'available_variations'  => $product->get_available_variations(),
          'attributes'   			=> $product->get_variation_attributes(),
          'selected_attributes' 	=> $product->get_variation_default_attributes()
        ) );
      return;
    }
  
    if (method_exists($product,'get_variation_attributes')) {
      $varattr = $product->get_variation_attributes();
    } else {
      return (apply_filters('vartable_novariable_warning', __('This is not a variable product', 'vartable'), $product));
    }
    
    $temp_varattr = array();
    foreach($varattr as $attr_key => $attr_values) {
      $temp_varattr[sanitize_title($attr_key)] = $attr_values;
    }
    
    $varattr = array();
    $varattr = $temp_varattr;
    
    $default_feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($product->id, array($vartable_thumb_size, $vartable_thumb_size)) );
    $default_feat_imagefull = wp_get_attachment_image_src( get_post_thumbnail_id($product->id), 'full' );
    
		$variations = $product->get_available_variations();
       
    if (empty($variations)) {
      $out = (__('Now go add some variable products!', 'vartable'));
      if($vartable_shortcd != 1) {
        echo '<p>'.$out.'</p>';
        return;
      } else {
        return ($out);
      }
    }
    
    // if ( current_user_can( 'manage_options' ) ) { echo '<pre>'. print_r($variations, true) .'</pre><hr />'; }

    // prepare arrays to use that will hold data
    $anyvariations = array();
    $standardvariations = array();
    $fullvariations = array();
    
    // get all attributes that are valid
    $attr_keys = array_keys($product->get_attributes());
    $attr_keys2 = array();
    foreach ($attr_keys as $atkey) {
      $attr_keys2[] = str_replace(array('attribute_', 'pa_'), array('',''), $atkey);
    }

    foreach ($variations as $key => $value) {
      
      foreach ($value['attributes'] as $attr_key => $attr_value) {
        
        // skip attributes that are empty and have not been set, mostly created by import plugins
        if (!in_array(str_replace(array('attribute_', 'pa_'), array('',''), $attr_key), $attr_keys2)) { continue; }
        
        if ($attr_value == '') {
          $anyvariations[$key][$value['variation_id']][str_replace('attribute_', '', $attr_key)] = $varattr[str_replace('attribute_', '', $attr_key)];
        } else {
          $standardvariations[$key][$value['variation_id']][str_replace('attribute_', '', $attr_key)] = array($attr_value);
        }
      }
    }

    
    // get variations that have all attributes set
    $fullvariations = array_diff_key($standardvariations, $anyvariations);
    
    $combinationarray = array();
    $combinationsresults = array();
    foreach ($anyvariations as $attrkey => $variat) {
      reset($variat);
      $variationid = key($variat);
      $countvariations = count($variat[$variationid]);
      if (isset($standardvariations[$attrkey]) && isset($variat[$variationid]) && is_array($standardvariations[$attrkey]) && is_array($variat[$variationid])) {
        $combinationarray[$attrkey] = $standardvariations[$attrkey][$variationid] + $variat[$variationid] ;
      }
      if (!is_array($variat[$variationid])) {
        $combinationarray[$attrkey] = $standardvariations[$attrkey][$variationid];
      }
      if (isset($standardvariations[$attrkey][$variationid]) && !is_array($standardvariations[$attrkey][$variationid])) {
        $combinationarray[$attrkey] = $variat[$variationid];
      }
      
    }

    // get all possible combinations for the attributes set as any
    foreach($combinationarray as $attrkey => $combarrays) {
      // more than one attribute is set on the variation
      if (count($combarrays) > 1) {
        $combinationsresults[$attrkey] = vartable_combinations($combarrays);
      } else { // ONLY ONE attribute is set on the variation
        if (is_array($combarrays)) {
          foreach($combarrays as $singleattkey => $singleattval) {
            if (is_array($singleattval)) {
              foreach($singleattval as $thesingleattr) {
                $combinationsresults[$attrkey][][$singleattkey] = $thesingleattr;
              }
            }
          }
        }
      }
    }

    $finalattr = array();
    $i = 0;
    
    // add variations that have all attributes set to the final array
    foreach ($fullvariations as $fkey => $fvalue) {
      $finalattr[$i] = $variations[$fkey];
      $i++;
    }
    
    // add variations that have attributes set as any the final array
    foreach ($combinationsresults as $attrkey => $combinations) {
    
      foreach ($combinations as $combinationsvalues) {
        $finalattr[$i] = $variations[$attrkey];
        if (is_array($combinationsvalues)) {
          foreach ($combinationsvalues as $attrshortkey => $attrstringvalue) {
            $finalattr[$i]['attributes']['attribute_'.$attrshortkey] = $attrstringvalue;
          }
        }
        // if (!is_array($combinationsvalues)) {
          // have to check this
        // }
        $i++;
      }
    }
    
    $attr_ordernames = array();
    foreach ($product->get_attributes() AS $ankey => $akval) {
      if ($akval['is_variation'] == 1) {
        $attr_ordernames[str_replace(array('_', '.', ' '),array('-', '-', '-'),sanitize_title(str_replace('attribute_', '', $ankey)))] = wc_attribute_label( $akval['name'] );
      }
    }
    
   
    
    // order of attributes
    $attr_order = array();
    foreach ($varattr as $attrkey => $attrvalues) {
      $attr_order[] = 'attribute_'.sanitize_title($attrkey);
    }
    
    $anyextraimg = 0;
    $anydescription = 0;
    $anydimension = 0;
    $anyweight = 0;
    $head = '';
    
    ob_start();
    do_action('woocommerce_before_add_to_cart_form', $product->id);
    $woocommerce_before_add_to_cart_form = ob_get_clean();
    $out .= $woocommerce_before_add_to_cart_form;
    
    ob_start();
    do_action('vartable_before_table', $product->id);
    $vartable_before_table = ob_get_clean();
    $out .= $vartable_before_table;
    
    ob_start();
    do_action('vartable_table_class', $product->id);
    $vartable_table_class = ob_get_clean();
    
    $sorting_js = apply_filters( 'vartable_sorting_js', get_option('vartable_sorting'), $product->id);
    $sorting_col = apply_filters( 'vartable_sorting_column', get_post_meta($product->id, 'custom_variations_preordering', true), $product->id);
    $sorting_direction = apply_filters( 'vartable_sorting_direction', get_post_meta($product->id, 'custom_variations_preordering_direction', true), $product->id);
    
    if (($vartable_globalcart == 1 || $vartable_globalcart == 2) && ($vartable_globalposition == 'top' || $vartable_globalposition == 'both')) {
      
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
      
      if ($vartable_title == 1) {
        $out .= '<h3><a href="'. get_permalink($product->id)  .'" title="'. get_the_title($product->id) .'">'. get_the_title($product->id) .'</a></h3>';
      }
      
      $vt_button_classes = apply_filters('vartable_global_button_classes', array(
          'single_add_to_cart_button',
          'btn',
          'button',
          'button_theme',
          'ajax',
          'add_to_cart',
          'avia-button',
          'fusion-button',
          'button-flat',
          'button-round',
          'alt'
        )
      );
 
      $out .= apply_filters('vartable_global_btn', '
        <div class="vartable_gc_wrap vartable_gc_wrap_top">
          <a data-position="top" href="#globalcart" class="globalcartbtn submit '. implode(' ', $vt_button_classes) .'" data-product_id="gc_'.$product->id .'" id="gc_'. $vtrand .'_top" class="btn button alt">'. __('Add selected to cart', 'vartable') .'<span class="vt_products_count"></span></a>
          <span class="added2cartglobal added2cartglobal_'. $vtrand .'">&#10003;</span>
          <span class="vtspinner vtspinner_top vtspinner_'. $vtrand .'"><img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" /></span>
        </div>
      ', $product, 'top', $vtrand);
    } else {
      if ($vartable_title == 1) {
        $out .= '<h3><a href="'. get_permalink($product->id)  .'" title="'. get_the_title($product->id) .'">'. get_the_title($product->id) .'</a></h3>';
      }
    }
    
    $cartredirect = get_option('woocommerce_cart_redirect_after_add');
    
    $out .= '
    <table 
      id="tb_'. $vtrand .'" 
      class="table vartable '. ($sorting_js == 1 ? 'is_sortable' : '') .' '. $vartable_table_class .'" 
      data-random="'. $vtrand .'" 
      '. ($sorting_js == 1 ? 'data-sort="yes"' : 'data-sort="no"') .' 
      '. ($vartable_ajax == 1 ? 'data-vartable_ajax="1"' : 'data-vartable_ajax="0"').' 
      '. ($cartredirect == 'yes' ? 'data-cartredirect="yes"' : 'data-cartredirect="no"') .' 
      data-globalcart="'. $vartable_globalcart .'"
      '. ($sorting_col != '' ? 'data-preorder="'. $sorting_col .'"' : 'data-preorder=""') .' 
      '. ($sorting_direction != '' ? 'data-preorder_direction="'. $sorting_direction .'"' : 'data-preorder_direction=""') .' 
      >
    
      %headplaceholder%
    ';    
    
    $out .= '<tbody>
      ';
    
// echo vtdb($finalattr);


    

    
    // get order / position of the variations to display the table rows        
    $moi = 0;
    $mo_reset = 0;
    $menu_order = array();
    foreach ($finalattr as $vt_variation_array) {
      $vt_variation_object = get_post($vt_variation_array['variation_id']);
      
      if ($mo_reset != $vt_variation_object->menu_order && $vt_variation_object->menu_order != 0) {
        $mo_reset = $vt_variation_object->menu_order;
        $moi = 0;
      }
      $menu_order[$vt_variation_array['variation_id']] = ($vt_variation_object->menu_order * 100) + $moi++;
    }
    
    $ri = 0;
    $vt_trows = array();
		foreach ($finalattr as $key => $value) {
      
      // create an array to hold all TDs
      $allcolumns = array();
      
      $product_variation = new WC_Product_Variation($value['variation_id']);
      
      $vartable_qty_default = get_post_meta($value['variation_id'], 'vartable_qty_default', true);
      
      if (get_post_meta($value['variation_id'], 'vt_variation_hide', true) == 'yes') { continue; }
      if (!($product_variation->get_price() > 0) && $vartable_hide_zero == 1) { continue; }
      
      $varstock = $product_variation->get_stock_quantity();

      if (!($varstock > 0) && $vartable_hide_outofstock == 1) { continue; }
      
      if (!isset($vt_trows[$menu_order[$value['variation_id']]])) {
        $vt_trows[$menu_order[$value['variation_id']]] = '';
      }
      
      ob_start();
      do_action('woocommerce_before_single_variation', $product->id, $value);
      $woocommerce_before_single_variation = ob_get_clean();
      
      ob_start();
      do_action('vartable_inside_add_to_cart_form', $product->id, $value);
      $vartable_inside_add_to_cart_form = ob_get_clean();
      
      $vt_trows[$menu_order[$value['variation_id']]] .= $woocommerce_before_single_variation .'<tr class="'. $value['variation_id'] .' '.(get_post_meta($value['variation_id'], '_stock_status', true) != 'outofstock' ? 'instock' : 'outofstock') .' '. ($product_variation->is_purchasable() ? 'is_purchasable' : '' ) .'" data-price="'.wc_format_decimal($product_variation->get_display_price(), 2) .'">
          ';  
    
		$form .= '
		<form action="'. esc_url( $product->add_to_cart_url() ) .'" method="POST" data-variation_id="'.  $value['variation_id'].$ri .'" id="vtvariation_'. $value['variation_id'].$ri .'" class="vtajaxform" enctype="multipart/form-data">
			<input type="hidden" name="variation_id" value="'. $value['variation_id'] .'" />
			<input type="hidden" name="product_id" value="'. esc_attr( $product->id ) .'" />
			<input type="hidden" name="add-to-cart" value="'. esc_attr( $product->id ) .'" />
      '. $vartable_inside_add_to_cart_form .'
      ';
            
      if ($product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed())) {
        $form .= '<input type="hidden" class="hidden_quantity" name="quantity" value="'. ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)) .'" />';
      }
      
      $form .= '<input type="hidden" class="gift_wrap" name="gift_wrap" value="" />';
      
      $js_attr_arr = array();
      $js_attr_arr_full = array();
			if(!empty($value['attributes'])){
				foreach ($value['attributes'] as $attr_key => $attr_value) {
          if ($attr_value != '') {
				
            $form .= '<input type="hidden" class="form_vartable_attribute" name="'. $attr_key .'" value="'. $attr_value .'" />
            ';
            
            $js_attr_arr[str_replace(array('attribute_pa_', 'attribute_'), array('',''), $attr_key)] = htmlentities(str_replace('"', '||||||', $attr_value), ENT_QUOTES, 'utf-8', FALSE);
            $js_attr_arr_full[$attr_key] = htmlentities(str_replace('"', '||||||', $attr_value), ENT_QUOTES, 'utf-8', FALSE);
          }
				}
        
        $form .= '<input type="hidden" class="form_vartable_attribute_json" name="form_vartable_attribute_json" value=\''. (json_encode($js_attr_arr)) .'\' />
            ';
        $form .= '<input type="hidden" class="form_vartable_attribute_array" name="form_vartable_attribute_array" value=\''. (json_encode($js_attr_arr_full)) .'\' />
            ';
			}
			
      $headenames = vt_fields_func();
      
					
          
            if ($vartable_sku == 1) {
              $allcolumns['vartable_sku'] = '<td class="skucol" data-label="'. apply_filters('vartable_dl_sku', $headenames['vartable_sku'], $value) .'" data-sort-value="'.wc_clean(trim($value['sku'])).'">'. $value['sku'] .'</td>';
            }

            if ($vartable_thumb == 1) {
              $rowimg = '';
              $var_feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($value['variation_id']), array($vartable_thumb_size, $vartable_thumb_size));
              $var_feat_image_full = wp_get_attachment_image_src(get_post_thumbnail_id($value['variation_id']), 'full');
              if (!empty($var_feat_image)) { 
                $rowimg = $var_feat_image; 
              } else {
                $rowimg = $default_feat_image;
              }
              
              if (!empty($var_feat_image_full)) { 
                $rowimgfull = $var_feat_image_full; 
              } else {
                $rowimgfull = $default_feat_imagefull;
              }
            
              if (isset($rowimg[0])) {
                $allcolumns['vartable_thumb'] = '<td class="thumbcol"  data-label="'. apply_filters('vartable_dl_thumb', $headenames['vartable_thumb'], $value) .'">
                  <a href="'. $rowimgfull[0] .'" itemprop="image" class="variationimg zoom '. apply_filters( 'vartable_thumb_class_filter', 'thumb', $value) .'" title="'. $product->post->post_title .' - '.implode(' - ', $value['attributes']) .'"  data-rel="prettyPhoto">
                    <img src="'. $rowimg[0] .'" alt="'. $product->post->post_title .' - '.implode(' - ', $value['attributes']) .'" width="'. $rowimg[1] .'" height="'. $rowimg[2] .'" style="width: '. $vartable_thumb_size.'px; height: auto;" />
                  </a>
                </td>';
              } else {
                $allcolumns['vartable_thumb'] = '<td class="thumbcol" data-label="'. apply_filters('vartable_dl_thumb', $headenames['vartable_thumb'], $value) .'">
                  '. apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" style="width: '. $vartable_thumb_size.'px; height: auto;" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $product->ID ).'
                  </td>';
              }
          
          
            }
          ?>
          
          <?php
            if ($vartable_stock == 1) {

              if (absint($vartable_low_stock_thresh) > 0 && $varstock < absint($vartable_low_stock_thresh) && get_post_meta($value['variation_id'], '_manage_stock', true) == 'yes' ) {
                $allcolumns['vartable_stock'] = '<td class="stockcol" data-label="'. apply_filters('vartable_dl_stock', $headenames['vartable_stock'], $value) .'">'. (get_post_meta($value['variation_id'], '_stock_status', true) != 'outofstock' && $varstock > 0 ? '<span class="lowstock">'. str_replace ('%n', $varstock, __($vartable_low_stock_text, 'vartable')) .'</span>' : '<span class="outofstock">'.__($vartable_out_stock_text, 'vartable').'</span>') .'</td>';
              } else {
                $allcolumns['vartable_stock'] = '<td class="stockcol" data-label="'. apply_filters('vartable_dl_stock', $headenames['vartable_stock'], $value) .'">'. (get_post_meta($value['variation_id'], '_stock_status', true) != 'outofstock' ? '<span class="instock">'. str_replace ('%n', $varstock, __($vartable_in_stock_text, 'vartable')) .'</span>' : '<span class="outofstock">'.__($vartable_out_stock_text, 'vartable').'</span>') .'</td>';
              }
              
            }
          ?>
          <?php
            // get attribute names
            $attrnames = array();
            $orderedattributes = array_merge(array_flip($attr_order), $value['attributes']);
            
            
            
            foreach ($orderedattributes as $taxon => $taxval) {
              
              $taxonkey = sanitize_title(str_replace(array('-', '.', ' '),array('_', '_', '_'),$taxon));

              $temp = '';
              $temp = get_term_by('slug', $taxval, str_replace('attribute_', '', $taxon));
              // echo vtdb($taxval);
              if ($temp !== false) {
                
                $attrnames[$taxonkey] = apply_filters( 'woocommerce_variation_option_name', $temp->name, $value);
                
              } else {
                // get all custom attributes sanitize_title
                if (strpos($product->get_attribute( str_replace('attribute_', '', $taxon) ), '|') !== false) {
                  $allcustomattr = explode('|',$product->get_attribute( str_replace('attribute_', '', $taxon) ));
                } else {
                  $allcustomattr = explode(', ',$product->get_attribute( str_replace('attribute_', '', $taxon) ));
                }
                
                $customattrnames = array();
                
                foreach ($allcustomattr as $customattrname) {
                  $customattrnames[sanitize_title(trim($customattrname))] = apply_filters( 'woocommerce_variation_option_name', $customattrname, $value);
                }
                
                if ($taxval !== false && $taxval !== null && $taxval !== '' && isset($customattrnames[sanitize_title(trim($taxval))])) {
                  
                  $attrnames[$taxonkey] = apply_filters( 'woocommerce_variation_option_name', $customattrnames[sanitize_title(trim($taxval))], $value);
                  
                }
                
              }
            }
          
            // removed on version 1.1.6
						// $allcolumns['vartable_variations'] = '<td class="optionscol" data-label="'. $headenames['vartable_variations'] .'">
              // '. implode( apply_filters( 'vartable_attributes_join', '</td><td>'), apply_filters( 'vartable_attributes_array', $attrnames)) .'
            // </td>
            // ';
                        
            $attrnames = apply_filters( 'vartable_attributes_array', $attrnames, $value);
            
            
            foreach($attrnames as $attr_slug => $attr_td_value) {
              
              if (!isset($allcolumns['vartable_variations'])) {
                $allcolumns['vartable_variations'] = '';
              }
              $headEQ .= '<td>'.$attr_td_value.'</td>';             
              $allcolumns['vartable_variations'] .= apply_filters( 'vartable_attributes_join', '<td class="optionscol '. $attr_slug .'" data-sort-value="'. wc_clean(trim($attr_td_value)) .'" data-label="'. apply_filters('vartable_dl_options', $attr_ordernames[str_replace(array('_', '.', ' '),array('-', '-', '-'),sanitize_title(str_replace('attribute_', '', $attr_slug)))]) .'">'. $attr_td_value .'</td>', $value);
            }
            
            if ($vartable_price == 1) {
              $allcolumns['vartable_price'] = '
              <td class="pricecol" 
                data-label="'. apply_filters('vartable_dl_price', $headenames['vartable_price'], $value) .'" 
                data-price="'.wc_format_decimal($product_variation->get_display_price(), 2) .'" 
                data-sort-value="'. wc_format_decimal($product_variation->get_display_price(), 2) .'">
                '. $product_variation->get_price_html() .'
              </td>';
            }
            if ($vartable_total == 1) {
              $allcolumns['vartable_total'] = '
              <td class="totalcol" data-label="'. apply_filters('vartable_dl_total', $headenames['vartable_total'], $value) .'" data-sort-value="'. wc_format_decimal($product_variation->get_display_price() * ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)), 2) .'">
                '. wc_price($product_variation->get_display_price() * ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value))) .'
                '. (get_option('woocommerce_price_display_suffix') != '' ? ' '.get_option('woocommerce_price_display_suffix') : '') .'
              </td>';
            }

            $override_extra_image = get_post_meta($value['variation_id'], 'override_extra_image', true);
            $enable_extra_image = get_post_meta($value['variation_id'], 'enbable_variations_table_img', true);
            $vartable_qty_step = get_post_meta($value['variation_id'], 'vartable_qty_step', true);
            
            // if not set, then set it to 1
            if (intval($vartable_qty_step) == 0) { $vartable_qty_step = 1; }
            
            
            if (get_post_meta( $product->id, 'disable_variations_table_offer', true ) != 1) {
            
              if ($vartable_offer == 1 || $enable_extra_image == 'yes' ) {
                
                $allcolumns['vartable_offer'] = '<td class="offercol"  data-label="'. apply_filters('vartable_dl_offer', $headenames['vartable_offer'], $value) .'">';

                if (!empty($override_extra_image) && $enable_extra_image != 'no') {
                  $allcolumns['vartable_offer'] .= '<img src="'. $override_extra_image .'" alt="'.  __('offer', 'vartable') .'" />';
                  $anyextraimg = 1;
                } 
                if ($vartable_image !='' && $enable_extra_image != 'no' && empty($override_extra_image)) {
                  $allcolumns['vartable_offer'] .= '<img src="'. $vartable_image .'" alt="'.  __('offer', 'vartable') .'" />';
                  $anyextraimg = 1;
                }
                
                $allcolumns['vartable_offer'] .= '</td>';
              }
            }
            

            if ($vartable_qty == 1) {
              $allcolumns['vartable_qty'] = '
              <td class="qtycol" data-label="'. apply_filters('vartable_dl_qty', $headenames['vartable_qty'], $value) .'">';
                if ($product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed()) ) {
                  
                  if ($vartable_qty_control == 1) {
                  
                    $allcolumns['vartable_qty'] .= '
                    <div class="qtywrap">
                    ';
                    
                    $allcolumns['vartable_qty'] .= '
                    <div class="minusqty qtycontrol">-</div>
                    ';
                  
                  }
                  
                  $allcolumns['vartable_qty'] .= '
                    <input type="number" step="'. apply_filters('vartable_qty_step', $vartable_qty_step, $value) .'" name="var_quantity" value="'. ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)) .'" title="Qty" class="input-text qty text" size="4" '. (intval($value['min_qty']) > 0 && !isset($vartable_default_qty) ? 'min="'. $value['min_qty'] .'"': 'min="0"') .' '. (intval($value['max_qty']) > 0 ? 'max="'. $value['max_qty'] .'"': '') .'>
                  ';
                
                  if ($vartable_qty_control == 1) {
                
                    $allcolumns['vartable_qty'] .= '
                      <div class="plusqty">+</div>
                    ';
                    
                    $allcolumns['vartable_qty'] .= '
                      </div>
                    ';
                    
                  }
                }
              $allcolumns['vartable_qty'] .= '</td>';
            }
            
            
          ob_start();
          do_action('woocommerce_add_to_cart_class', $product->id, $value);
          $woocommerce_add_to_cart_class = ob_get_clean();
          
          ob_start();
          do_action('woocommerce_before_add_to_cart_button', $product->id, $value);
          $woocommerce_before_add_to_cart_button = ob_get_clean();
          
          $allcolumns['vartable_cart'] = '<td class="cartcol '. ($vartable_cart == 0 ? 'vartablehide' : '') .' '. $woocommerce_add_to_cart_class .'" data-label="">'.$woocommerce_before_add_to_cart_button;
          
          // if is purchasable          
          if ( $product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed())) {
            
            // if is out of stock and backorder are allowed
            if (
              (get_post_meta($value['variation_id'], '_stock_status', true) != 'instock' && !empty($value['backorders_allowed'])) 
              ||
              ($vartable_zero_to_out == 1 && $varstock == 0 && get_post_meta($value['variation_id'], '_manage_stock', true) == 'yes')
            ) { 
              $carttext = __( 'Backorder', 'vartable' ); 
            } else { 
              $carttext = __('Add to cart', 'vartable' ); 
            }
            
            $vt_button_classes = apply_filters('vartable_single_button_classes', array(
                  'single_add_to_cart_button',
                  'button',
                  'button_theme',
                  'ajax',
                  'add_to_cart',
                  'avia-button',
                  'fusion-button',
                  'button-flat',
                  'button-round',
                  'alt'
                )
              );
            
            
            $allcolumns['vartable_cart'] .= $form.'
              <button id="add2cartbtn_'. $value['variation_id'].$ri .'" type="submit" data-product_id="'. $value['variation_id'] .'" class="'. implode(' ', $vt_button_classes) .' alt">'. apply_filters('single_add_to_cart_text', $carttext, $product->product_type, $value) .'</button>';
            if ($vartable_ajax == 1 || $vartable_globalcart == 1 || $vartable_globalcart == 2) {
              $allcolumns['vartable_cart'] .= '
                <div class="added2cartwrap" id="added2cart_'.$value['variation_id'].$ri.'"><span class="added2cart" >&#10003;</span></div>
                <span class="vtspinner singlebtn vtspinner_'. $value['variation_id'].$ri .'">
                  <img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" />
                </span>
                ';
            } else {
              $allcolumns['vartable_cart'] .= '
                <div class="added2cartwrap notvisible" id="added2cart_'.$value['variation_id'].$ri.'"></div>
                <span class="vtspinner vtspinner_'. $value['variation_id'].$ri .' notvisible"></span>
                ';
            }
          }
          
          ob_start();
          do_action('woocommerce_after_add_to_cart_button', $product->id, $value);
          $woocommerce_after_add_to_cart_button = ob_get_clean();
          
          $allcolumns['vartable_cart'] .= $woocommerce_after_add_to_cart_button .'</form></td>';
          
          // empty $form
          $form = '';
          
          if ($vartable_globalcart == 1 || $vartable_globalcart == 2) {
            
            $allcolumns['vartable_globalcart'] = '<td class="globalcartcol '. ($vartable_globalcart == 2 ? 'vartablehide' : '') .'" data-label="">';
            if ( $product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed()) ) {
              $allcolumns['vartable_globalcart'] .= '  <input type="checkbox" class="globalcheck" name="check_'. $value['variation_id'].$ri .'" value="1" '. ($vartable_globalcart == 2 || $vartable_globalcart_status == 1 ? 'checked="checked"' : '') .'>';
            }
            $allcolumns['vartable_globalcart'] .= '</td>';
          }
          
          if ($vartable_wishlist == 1 && defined( 'YITH_WCWL' )) {
            $url=strtok($_SERVER["REQUEST_URI"],'?');
            parse_str($_SERVER['QUERY_STRING'], $query_string);
            $query_string['add_to_wishlist'] = basename($value['variation_id']);
            $rdr_str = http_build_query($query_string);
          
          $wishlist = do_shortcode('[yith_wcwl_add_to_wishlist product_id='. $product->id .' icon="fa-heart"]');
          
          $allcolumns['vartable_wishlist'] = '
            <td class="wishcol" data-label="'. apply_filters('vartable_dl_wishlist', $headenames['vartable_wishlist'], $value) .'">
              '. vartable_delete_all_between('</i>', '</a>', $wishlist) .'
            </td>';
          }

          if ( $is_wrappable == 'yes' && $vartable_gift == 1) {

            $current_value = ! empty( $_REQUEST['gift_wrap'] ) ? 1 : 0;

            $cost = get_post_meta( $product->id, '_gift_wrap_cost', true );

            if ( $cost == '' ) {
              $cost = $gift_wrap_cost;
            }

            $price_text = $cost > 0 ? woocommerce_price( $cost ) : __( 'free', 'woocommerce-product-gift-wrap' );
            $checkbox   = '<input type="checkbox" class="var_gift_wrap" name="var_gift_wrap" value="yes" ' . checked( $current_value, 1, false ) . ' />';
            
            
            $allcolumns['vartable_gift'] = '
            <td class="giftcol" data-label="'. apply_filters('vartable_dl_gift', $headenames['vartable_gift'], $value) .'">
              <label>'.  str_replace(array('{price}', '{checkbox}',), array($price_text, $checkbox), $product_gift_wrap_message) .'</label>
            </td>';          
          
          }
          
          if ($vartable_desc == 1 && $vartable_desc_inline == 1) {
            
            $vt_variation_description = '';
            $vt_variation_description = get_post_meta($value['variation_id'], 'vt_variation_description', true);
            
            
            if (!$vt_variation_description && isset($value['variation_description'])) {
              
              $vt_variation_description = $value['variation_description'];
              
            }
            
              $allcolumns['vartable_desc'] = '
              <td class="desccol" data-label="'. apply_filters('vartable_dl_desc', $headenames['vartable_desc'], $value) .'">'. $vt_variation_description .'</td>';
            if ($vt_variation_description != '') {
              $anydescription = 1;
            }
            
          }
          
          if ($vartable_dimensions == 1) {
            $vartable_dimensions_str = '';
            $vartable_dimensions_str = $product_variation->get_dimensions();

            $allcolumns['vartable_dimensions'] = '
              <td class="dimensions_col" data-label="'. apply_filters('vartable_dl_dimensions', $headenames['vartable_dimensions'], $value) .'">'. $vartable_dimensions_str .'</td>';
            if ($product_variation->has_dimensions()) {
              $anydimension = 1;
            }
            
          }
          
          if ($vartable_weight == 1) {
            
            if ($product_variation->has_weight()) {
            $allcolumns['vartable_weight'] = '
              <td class="weight_col" data-sort-value="'. $product_variation->get_weight() .'" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'">'. $product_variation->get_weight().($product_variation->has_weight() ? ' '.get_option('woocommerce_weight_unit') : '') .'</td>';
              $anyweight = 1;
            } else {
              $allcolumns['vartable_weight'] = '
              <td class="weight_col" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'"></td>';
            }
            
          }
          

          // order columns
          $orderedcols = array();
          if (is_array($vartable_order)) {
            foreach ($vartable_order as $vokey => $vovalue) {
              if (isset($allcolumns[$vokey])) {
                $orderedcols[$vokey] = $allcolumns[$vokey];
              }
            }
          } else {
            $orderedcols = $allcolumns;
          }
          
          $orderedcols = array_filter($orderedcols);
          $vt_trows[$menu_order[$value['variation_id']]] .= implode ("\n", apply_filters('vartable_output_array', $orderedcols, $value, $attrnames));
          $ri++;
          
          ob_start();
          do_action('woocommerce_after_single_variation', $product->id, $value);
          $woocommerce_after_single_variation = ob_get_clean();

					$vt_trows[$menu_order[$value['variation_id']]] .= '</tr>'.$woocommerce_after_single_variation.'
          ';
            // add description last
            $vt_variation_description = '';
            $vt_variation_description = get_post_meta($value['variation_id'], 'vt_variation_description', true);
            
            if (!$vt_variation_description && isset($value['variation_description'])) {
              
              $vt_variation_description = $value['variation_description'];
              
            }
            
            if ($vartable_desc == 1 && $vt_variation_description != '' && $vartable_desc_inline != 1) {
              $vt_trows[$menu_order[$value['variation_id']]] .= '
              <tr class="descrow">
                <td class="desccol" colspan="'. (count($orderedcols) + count($attrnames) - 1) .'" data-label="'. apply_filters('vartable_dl_desc', $headenames['vartable_desc'], $value) .'">'. $vt_variation_description .'</td>
              </tr>';
            }

          

		
		}
    
    
    $out .= implode ("\n", apply_filters('vartable_output_rows_by_id', $vt_trows, $product->id));
    
    
    ob_start();
    do_action('woocommerce_after_add_to_cart_form', $product->id);
    $woocommerce_after_add_to_cart_form = ob_get_clean();
    
    $out .= '</tbody>
    </table>
    '. $woocommerce_after_add_to_cart_form.'
    ';
    
    if (($vartable_globalcart == 1 || $vartable_globalcart == 2) && ($vartable_globalposition == 'bottom' || $vartable_globalposition == 'both')) {
      
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
      
      $vt_button_classes = apply_filters('vartable_global_button_classes', array(
            'single_add_to_cart_button',
            'btn',
            'button',
            'button_theme',
            'ajax',
            'add_to_cart',
            'avia-button',
            'fusion-button',
            'button-flat',
            'button-round',
            'alt'
          )
        );
 
      $out .= apply_filters('vartable_global_btn', '
        <div class="vartable_gc_wrap vartable_gc_wrap_bottom">
          <a data-position="bottom" href="#globalcart" class="globalcartbtn submit '. implode(' ', $vt_button_classes) .'" data-product_id="gc_'.$product->id .'" id="gc_'. $vtrand .'_bottom" class="btn button alt">'. __('Add selected to cart', 'vartable') .'<span class="vt_products_count"></span></a>
          <span class="added2cartglobal added2cartglobal_'. $vtrand .'">&#10003;</span>
          <span class="vtspinner vtspinner_bottom vtspinner_'. $vtrand .'"><img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" /></span>
        </div>
      ', $product, 'bottom', $vtrand );
    }
    if ($vartable_ajax == 1 || $vartable_globalcart == 1 || $vartable_globalcart == 2) {
      if (isset($_SESSION['vtajaxfix']) && $_SESSION['vtajaxfix'] != 1) {
      $_SESSION['vtajaxfix'] = 1;
      
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
      

      }
    }
    
    
    ob_start();
    do_action('vartable_after_table', $product->id);
    $vartable_after_table = ob_get_clean();
    
    ob_start();
    do_action('woocommerce_product_meta_end', $product->id);
    $woocommerce_product_meta_end = ob_get_clean();
    
    $out .= $vartable_after_table;
    $out .= $woocommerce_product_meta_end;
    
    if ($vartable_head == 1 && get_post_meta( $product->id, 'disable_variations_table_header', true ) != 1) {
    
      // order header
      $headenames = vt_fields_func();
      $headenames['vartable_cart'] = '';
      $headenames = apply_filters( 'vartable_headenames', $headenames, $product->id);
      $orderedheader = array();
      
      if (is_array($vartable_order)) {
      
        if ($anyextraimg == 1 && get_post_meta( $product->id, 'disable_variations_table_offer', true ) != 1) {
          $vartable_order['vartable_offer'] = __('Offer Image', 'vartable');
        }
        $vi = 0;
        foreach ($vartable_order as $vokey => $vovalue) {
          if (($vokey == 'vartable_gift' && $is_wrappable != 'yes') || $vokey == 'vartable_sorting') {
            continue;
          }
          $sortingval = ' data-sort="float" ';
          if ($vokey == 'vartable_thumb' || $vokey == 'vartable_gift' || $vokey == 'vartable_wishlist' || $vokey == 'vartable_cart' || $vokey == 'vartable_offer') {
            $sortingval = ' data-sort="string" ';
          }
      if ($vokey == 'vartable_globalcart' || $vokey == 'vartable_cart') {
            $sortingval = '';
          }
          if ($vokey == 'vartable_qty') {
            $sortingval = ' data-sort="int" ';
          }
          if ($vokey == 'vartable_price') {
            $sortingval = ' data-sort="float" ';
          }
          
          if ( (isset(${$vokey}) && ${$vokey} == 1) || ($vokey == 'vartable_offer' && $anyextraimg == 1) || $vokey == 'vartable_variations') {
            
            if ($vokey == 'vartable_wishlist' && defined( 'YITH_WCWL' ) == false) {
              continue;
            }
            
            if ($vokey == 'vartable_variations') {
              
              $orderedheader[$vokey] = '';
              
              foreach($attr_ordernames as $attrslug => $attrval) {
                
                ob_start();
                do_action('vartable_variations_th', $product->id, $attrslug);
                $vartable_variations_th = ob_get_clean();
                
                $orderedheader[$vokey] .= '
                  <th '. $sortingval .' class="'.$vokey .' '. $attrslug .'" '. $vartable_variations_th .'>
                    <span>
                      '. apply_filters( 'vartable_header_attributes_join' , $attrval, $product->id, $attrslug) .'
                    </span>
                  </th>
                  ';
              }
              
            } elseif ($vokey == 'vartable_globalcart' && ($vartable_globalcart == 1 || $vartable_globalcart == 2)) {
              $orderedheader[$vokey] = '<th '. $sortingval .'class="'.$vokey .' '. ($vartable_globalcart == 2 ? 'vartablehide' : '') .'"><div class="vartable_selectall button btn"><label for="vtselectall_'. $vtrand .'">'. apply_filters( 'vartable_header_text' , __('Select All', 'vartable'), $product->id) .' <input class="vartable_selectall_check" id="vtselectall_'. $vtrand .'" type="checkbox" id="selecctall"/></label></div></th>';
            } else {
              $orderedheader[$vokey] = '<th '. $sortingval .' class="'.$vokey .'" ><span>'.apply_filters( 'vartable_header_text' , $headenames[$vokey], $product->id).'</span></th>';
            }
          }
          $vi++;
        }
      }
      
      if ($anyextraimg == 0) {
        unset($orderedheader['vartable_offer']);
        unset($allcolumns['vartable_offer']);
      }
      if ($anydescription == 0 || $vartable_desc_inline != 1) {
        unset($orderedheader['vartable_desc']);
      }
      if ($anydimension == 0) {
        unset($orderedheader['vartable_dimensions']);
      }
      if ($anyweight == 0) {
        unset($orderedheader['vartable_weight']);
      }
      
      $joinedheader = implode('', apply_filters( 'vartable_header_th', $orderedheader, $product->id));
    
      if (get_post_meta( $product->id, 'custom_variations_table_header', true ) != '') {
        $head .= get_post_meta( $product->id, 'custom_variations_table_header', true );
      } elseif ($vartable_customhead != '' && get_post_meta( $product->id, 'custom_variations_table_header_skip', true ) != 1) {
        
        $head .= $vartable_customhead;
        
      } else {
      
        $head .= '<thead>
            <tr>
              '.
            $joinedheader
          .' 
            </tr>
          </thead>
        ';
      }
    }
    
    $out = str_replace('%headplaceholder%', $head, $out);
    if ($anyextraimg == 0) {
      $out = str_replace('<td class="offercol" data-label="'. apply_filters('vartable_dl_offer', $headenames['vartable_offer'], $product->id) .'"></td>', '', $out);
    }
    
    if ($anydescription == 0 && $vartable_desc_inline == 1) {
      $out = str_replace('<td class="desccol" data-label="'. apply_filters('vartable_dl_desc', $headenames['vartable_desc'], $value) .'"></td>', '', $out);
    }
    
    if ($anyweight == 0) {
      $out = str_replace('<td class="weight_col" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'"></td>', '', $out);
    }
    if ($anydimension == 0) {
      $out = str_replace('<td class="dimensions_col" data-label="'. apply_filters('vartable_dl_dimensions', $headenames['vartable_dimensions'], $value) .'"></td>', '', $out);
    }
    
    // removed on version 1.2.7 
    // it was causing some woocommerce shotcodes to break
    // wp_reset_query();
    // wp_reset_postdata();
    
    if($vartable_shortcd != 1) {
      echo $out;
    } else {
      return ($out);
    }
}



// move table under the image
add_action( 'wp', 'vartable_position_hook' );
function vartable_position_hook() {
  global $post;

  $product_cats = wp_get_post_terms( $post->ID, 'product_cat' );
  // get all ids
  foreach($product_cats as $pcdata) {
    $pcids[] = $pcdata->term_id;
  }
  $vartable_categories_exc  = get_option('vartable_categories_exc');
  
  //  if the table is disabled for this product display the default select menus
  $checkcat = array();
  if (is_array($vartable_categories_exc) && is_array($pcids)) {
    $checkcat = array_intersect($pcids, $vartable_categories_exc);
  }
  
  
  
  if ( (get_option('vartable_position') == 'under' && get_post_meta($post->ID, 'disable_variations_table', true) == 2) || (get_option('vartable_position') == 'under' && get_option('vartable_disabled') != 1 && get_post_meta($post->ID, 'disable_variations_table', true) != 1 && empty($checkcat)) ) {
    remove_action( 'woocommerce_variable_add_to_cart', 'vt_woocommerce_variable_add_to_cart', 30 );
    add_action( 'woocommerce_after_single_product_summary', 'vt_woocommerce_variable_add_to_cart', 5 );
  }
}



add_action("wp_enqueue_scripts", "woovartables_scripts", 20); 
function woovartables_scripts() {
  
  global $woocommerce;
  
  wp_register_style( 'woovartables_css', plugins_url('assets/css/woovartables.css', __FILE__) );
  wp_enqueue_style('woovartables_css');
  
  if (isset($_GET['page']) && $_GET['page'] == 'variationstable') {
    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-draggable");
    wp_enqueue_script("jquery-ui-droppable");
  }
       
  if (get_option('vartable_sorting') == 1) {
    wp_register_script( 'woovartables_table_sort', plugins_url('assets/js/stupidtable.js', __FILE__), array('jquery') );
    wp_enqueue_script("woovartables_table_sort");    
  }
  
  $suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
  $lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
 
  if ( $lightbox_en ) {
    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
    wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
  }
  
  wp_register_script( 'woovartables_js', plugins_url('assets/js/add-to-cart.js',__FILE__ ), array( 'jquery' ));
  wp_enqueue_script('woovartables_js');
   
  $vars = array( 
    'ajax_url' => admin_url( 'admin-ajax.php' ), 
    'cart_url' => $woocommerce->cart->get_cart_url(),
    'vartable_ajax' => get_option('vartable_ajax'),
    'currency_symbol' => get_woocommerce_currency_symbol(),
    'thousand_separator' => wc_get_price_thousand_separator(),
    'decimal_separator' => wc_get_price_decimal_separator(),
    'decimal_decimals' => wc_get_price_decimals(),
    'currency_pos' => get_option( 'woocommerce_currency_pos' ),
    'price_display_suffix' => get_option( 'woocommerce_price_display_suffix' )
  );
  
  wp_localize_script( 'woovartables_js', 'localvars', $vars );
  
}

function vartable_footer_code() {
  global $woocommerce;
  ?>
  <div id="vt_added_to_cart_notification" style="display: none;">
    <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php echo __('Go to cart', 'vartable'); ?>"><span></span> <?php echo __('&times; product(s) added to cart', 'vartable'); ?> &rarr;</a>
  </div>
  <?php
}
add_action('wp_footer', 'vartable_footer_code');



// add product custom fields
add_action( 'woocommerce_product_options_general_product_data', 'spyros_disable_table_product_option' );
function spyros_disable_table_product_option() {
  
    $_pf = new WC_Product_Factory();  
    $product = $_pf->get_product(intval($_GET['post']));
  
    $formatted_attributes = array();
    $attributes = $product->get_attributes();
    $attributes_drop = array();
    
    if (is_array($attributes) && !empty($attributes)) {
      foreach($attributes as $attr=>$attr_deets){
        
        $attribute_label = wc_attribute_label($attr);
        $attributes_drop[$attr] = $attribute_label;
        
      }
    }

  
    woocommerce_wp_select( 
    array( 
      'id'      => 'disable_variations_table', 
      'label'   => __( 'Variations Table Status', 'vartable' ), 
      'options' => array(
        '0'   => __( 'Default plugin settings', 'vartable' ),
        '1'   => __( 'Force disabling', 'vartable' ),
        '2'   => __( 'Force variations table', 'vartable' )
        )
      )
    );
    woocommerce_wp_select( 
    array( 
      'id'      => 'disable_variations_table_header', 
      'label'   => __( 'Disable variations table <strong>header</strong>', 'vartable' ), 
      'options' => array(
        '0'   => __( 'No', 'vartable' ),
        '1'   => __( 'Yes', 'vartable' )
        )
      )
    );
    woocommerce_wp_select( 
    array( 
      'id'      => 'disable_variations_table_offer', 
      'label'   => __( 'Disable variations table <strong>offer/extra image</strong>', 'vartable' ), 
      'options' => array(
        '0'   => __( 'No', 'vartable' ),
        '1'   => __( 'Yes', 'vartable' )
        )
      )
    );
    woocommerce_wp_select( 
    array( 
      'id'      => 'custom_variations_table_header_skip', 
      'label'   => __( 'Skip custom variations table <strong>header</strong> set on settings', 'vartable' ), 
      'options' => array(
        '0'   => __( 'No', 'vartable' ),
        '1'   => __( 'Yes', 'vartable' )
        ),
        'desc_tip'=> true,
        'description' => __('If set to yes it will replace the custom header that you may have set, on the settings page, with the default one, if you do not set a custom one for this product only.', 'vartable' )
      )
    );
    
    woocommerce_wp_textarea_input( 
    array( 
      'id'      => 'custom_variations_table_header', 
      'label'   => __( 'Custom variations table header html code', 'vartable' ),
      'desc_tip'=> true,
      'description' => __('This will replace the table header with your custom html code', 'vartable' )
      )
    );
    
    $attr_options = array(
      'custom'   => __( 'Custom', 'vartable' ),
      'vartable_price'   => __( 'Price', 'vartable' ),
      'vartable_sku'   => __( 'SKU', 'vartable' ),
      'vartable_weight'   => __( 'Weight', 'vartable' ),
      'vartable_stock'   => __( 'Stock Status', 'vartable' ),
    );
    
    $attr_options = array_merge($attr_options, $attributes_drop);
    
    woocommerce_wp_select( 
    array( 
      'id'      => 'custom_variations_preordering', 
      'label'   => __( 'Default variations table ordering', 'vartable' ), 
      'options' => $attr_options,
      'desc_tip'=> true,
      'description' => __( 'This is a BETA feature, which means it needs more development and testing!', 'vartable' )
      )
    );
    woocommerce_wp_select( 
    array( 
      'id'      => 'custom_variations_preordering_direction', 
      'label'   => __( 'Default variations table ordering direction', 'vartable' ), 
      'options' => array(
        'custom'   => __( 'Custom', 'vartable' ),
        'asc'   => __( 'Ascending', 'vartable' ),
        'desc'   => __( 'Descending', 'vartable' )
        )
      )
    );
}

add_action( 'save_post', 'spyros_save_table_product_option' );
function spyros_save_table_product_option( $product_id ) {
    // If this is a auto save do nothing, we only save when update button is clicked
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
  }
	if ( isset( $_POST['disable_variations_table'] ) ) {
		if ( is_numeric( $_POST['disable_variations_table'] ) ) {
			update_post_meta( $product_id, 'disable_variations_table', $_POST['disable_variations_table'] );
    }
	} else {
    delete_post_meta( $product_id, 'disable_variations_table' );
  }
  
  
  if ( isset( $_POST['disable_variations_table_header'] ) ) {
		if ( is_numeric( $_POST['disable_variations_table_header'] ) ) {
			update_post_meta( $product_id, 'disable_variations_table_header', $_POST['disable_variations_table_header'] );
    }
	} else {
    delete_post_meta( $product_id, 'disable_variations_table_header' );
  } 
  
  if ( isset( $_POST['disable_variations_table_offer'] ) ) {
		if ( is_numeric( $_POST['disable_variations_table_offer'] ) ) {
			update_post_meta( $product_id, 'disable_variations_table_offer', $_POST['disable_variations_table_offer'] );
    }
	} else {
    delete_post_meta( $product_id, 'disable_variations_table_offer' );
  }  
  
  if ( isset( $_POST['custom_variations_table_header_skip'] ) ) {
		if ( is_numeric( $_POST['custom_variations_table_header_skip'] ) ) {
			update_post_meta( $product_id, 'custom_variations_table_header_skip', $_POST['custom_variations_table_header_skip'] );
    }
	} else {
    delete_post_meta( $product_id, 'custom_variations_table_header_skip' );
  } 
  
  if ( isset( $_POST['custom_variations_table_header'] ) ) {
		update_post_meta( $product_id, 'custom_variations_table_header', $_POST['custom_variations_table_header'] );
	} else {
    delete_post_meta( $product_id, 'custom_variations_table_header' );
  }
  
  if ( isset( $_POST['custom_variations_preordering'] ) ) {
		update_post_meta( $product_id, 'custom_variations_preordering', $_POST['custom_variations_preordering'] );
	} else {
    delete_post_meta( $product_id, 'custom_variations_preordering' );
  }
  
  if ( isset( $_POST['custom_variations_preordering_direction'] ) ) {
		update_post_meta( $product_id, 'custom_variations_preordering_direction', $_POST['custom_variations_preordering_direction'] );
	} else {
    delete_post_meta( $product_id, 'custom_variations_preordering_direction' );
  }
}




//Display Fields
add_action( 'woocommerce_product_after_variable_attributes', 'vartable_variable_fields', 10, 3 );


function vartable_variable_fields( $loop, $variation_data, $variation ) {
  global $thepostid, $post;
  ?>
	<tr>
		<td>
      <br />
			<?php
// Checkbox

			woocommerce_wp_select( 
			array( 
				'id'            => 'enbable_variations_table_img['.$loop.']', 
				'label'         => __('Display Extra Image', 'vartable' ).' ', 
				'description'   => '',
				'value'         => get_post_meta($variation->ID, 'enbable_variations_table_img', true),
        'options' => array(
            '' => __('Default Settings', 'vartable'),
            'no' => __('No', 'vartable'),
            'yes' => __('Yes', 'woocommerce')
          )
				)
			);
			?>
		</td>
	</tr>
  <tr>
    <td>
      <label for="override_extra_image[<?php echo $loop; ?>]"><?php _e('Override extra image', 'vartable'); ?></label>
      <?php spyros_media_upload('override_extra_image['. $loop .']', get_post_meta($variation->ID, 'override_extra_image', true), $loop); ?>
    </td>
  </tr>
  
  <tr>
		<td>
			<?php

      // Step input

			woocommerce_wp_text_input( 
			array( 
				'id'            => 'vartable_qty_step['.$loop.']', 
				'label'         => __('Quantity Steps', 'vartable' ).' ', 
				'value'         => 2,
        'wrapper_class' => 'form-row-first'
				)
			);
			?>
		</td>
	</tr>
  
  <tr>
		<td>
			<?php

      // Step input

			woocommerce_wp_text_input( 
			array( 
				'id'            => 'vartable_qty_default['.$loop.']', 
				'label'         => __('Default Variation Quantity', 'vartable' ).' ', 
				'value'         => get_post_meta($variation->ID, 'vartable_qty_default', true),
        'wrapper_class' => 'form-row-last'
				)
			);
			?>
		</td>
	</tr>
  
  <tr>
		<td>
			<?php

			woocommerce_wp_textarea_input( 
			array( 
				'id'            => 'vt_variation_description['.$loop.']', 
				'label'         => __('Description', 'vartable' ).' ', 
				'value'         => get_post_meta($variation->ID, 'vt_variation_description', true)
				)
			);
			?>
		</td>
	</tr> 
  
  <tr>
		<td>
			<?php

			woocommerce_wp_checkbox( 
			array( 
				'id'            => 'vt_variation_hide['.$loop.']', 
				'label'         => __('Hide this variation from the table', 'vartable' ).' ', 
				'value'         => get_post_meta($variation->ID, 'vt_variation_hide', true)
				)
			);
			?>
		</td>
	</tr>
  <?php
}

//JS to add fields for new variations
add_action( 'woocommerce_product_after_variable_attributes_js', 'vartable_variable_fields_js' );

/**
 * Create new fields for new variations
 *
*/
function vartable_variable_fields_js() {
?>
	<tr>
		<td>
      <br />
			<?php
			// Checkbox

			woocommerce_wp_select( 
			array( 
				'id'            => 'enbable_variations_table_img[ + loop + ]', 
				'label'         => __('Display Extra Image', 'vartable' ).' ', 
				'description'   => '',
				'value'         => $variation_data['enbable_variations_table_img'][0],
        'options' => array(
            '' => __('Default Settings', 'vartable'),
            'no' => __('No', 'vartable'),
            'yes' => __('Yes', 'woocommerce')
          )
				)
			);
			?>
		</td>
	</tr>
  <tr>
    <td>
      <label for="override_extra_image[ + loop + ]"><?php _e('Override extra image', 'vartable'); ?></label>
      <?php spyros_media_upload('override_extra_image[ + loop + ]', $variation_data['override_extra_image'][0], '+ loop +'); ?>
    </td>
  </tr>
  
  <tr>
		<td>
			<?php
			// Checkbox

			woocommerce_wp_text_input( 
			array( 
				'id'            => 'vartable_qty_step[ + loop + ]', 
				'label'         => __('Quantity Steps', 'vartable' ).' ', 
				'description'   => '',
				'value'         => 2,
        'wrapper_class' => 'form-row-first'
				)
			);
			?>
		</td>
	</tr>
  
  <tr>
		<td>
			<?php
			// Checkbox

			woocommerce_wp_text_input( 
			array( 
				'id'            => 'vartable_qty_default[ + loop + ]', 
				'label'         => __('Default Variation Quantity', 'vartable' ).' ', 
				'description'   => '',
				'value'         => $variation_data['vartable_qty_default'][0],
        'wrapper_class' => 'form-row-last'
				)
			);
			?>
		</td>
	</tr>
  
  <tr>
		<td>
			<?php
			// Checkbox

			woocommerce_wp_textarea_input( 
			array( 
				'id'            => 'vt_variation_description[ + loop + ]', 
				'label'         => __('Description', 'vartable' ).' ', 
				'description'   => '',
				'value'         => $variation_data['vt_variation_description'][0]
				)
			);
			?>
		</td>
	</tr>
  
   
  <tr>
		<td>
			<?php

			woocommerce_wp_checkbox( 
			array( 
				'id'            => 'vt_variation_hide[ + loop + ]', 
				'label'         => __('Hide this variation from the table', 'vartable' ).' ', 
				'value'         => $variation_data['vt_variation_hide'][0]
				)
			);
			?>
		</td>
	</tr>
  <?php
}
//Save variation fields
add_action( 'woocommerce_process_product_meta_variable', 'vartable_save_variable_fields', 10, 1 );
add_action( 'woocommerce_save_product_variation', 'vartable_save_variable_fields', 10, 1 );

/**
 * Save new fields for variations
 *
*/
function vartable_save_variable_fields( $post_id ) {
	if (isset( $_POST['variable_post_id'] ) ) {
    

		$variable_sku          = $_POST['variable_sku'];
		$variable_post_id      = $_POST['variable_post_id'];

    // Checkbox
		$enbable_variations_table_img = $_POST['enbable_variations_table_img'];
		$override_extra_image         = $_POST['override_extra_image'];
		$vartable_qty_step            = $_POST['vartable_qty_step'];
		$vartable_qty_default         = $_POST['vartable_qty_default'];
		$vt_variation_hide            = (isset($_POST['vt_variation_hide']) ? $_POST['vt_variation_hide'] : '');
		$vt_variation_description     = $_POST['vt_variation_description'];
    
		// for ( $i = 0; $i < sizeof( $variable_post_id ); $i++ ) {
		foreach ( $variable_post_id as $i => $vid ) {
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $enbable_variations_table_img[$i] ) ) {
				update_post_meta( $variation_id, 'enbable_variations_table_img', stripslashes( $enbable_variations_table_img[$i] ) );
			} else {
        if (isset($enbable_variations_table_img[$i])) {
          delete_post_meta( $variation_id, 'enbable_variations_table_img', stripslashes( $enbable_variations_table_img[$i] ) );
        }
      }
      if ( isset( $override_extra_image[$i] ) ) {
				update_post_meta( $variation_id, 'override_extra_image', stripslashes( $override_extra_image[$i] ) );
			} else {
        if ($override_extra_image[$i] == '') {
          delete_post_meta( $variation_id, 'override_extra_image', stripslashes( $override_extra_image[$i] ) );
        }
      }
      if ( isset( $vt_variation_description[$i] ) ) {
				update_post_meta( $variation_id, 'vt_variation_description', stripslashes( $vt_variation_description[$i] ) );
			} else {
        if ($vt_variation_description[$i] == '') {
          delete_post_meta( $variation_id, 'vt_variation_description', stripslashes( $vt_variation_description[$i] ) );
        }
      }
      if ( isset( $vartable_qty_step[$i] ) ) {
				update_post_meta( $variation_id, 'vartable_qty_step', stripslashes( $vartable_qty_step[$i] ) );
			} else {
        if ($vartable_qty_step[$i] == '') {
          delete_post_meta( $variation_id, 'vartable_qty_step', stripslashes( $vartable_qty_step[$i] ) );
        }
      }
      if ( isset( $vartable_qty_default[$i] ) ) {
				update_post_meta( $variation_id, 'vartable_qty_default', stripslashes( $vartable_qty_default[$i] ) );
			} else {
        if ($vartable_qty_default[$i] == '') {
          delete_post_meta( $variation_id, 'vartable_qty_default', stripslashes( $vartable_qty_default[$i] ) );
        }
      }
      if ( isset( $vt_variation_hide[$i] ) ) {
				update_post_meta( $variation_id, 'vt_variation_hide', stripslashes( $vt_variation_hide[$i] ) );
			} else {
        delete_post_meta( $variation_id, 'vt_variation_hide' );
      }
		}
  }
}
    
    
    

function vartable_combinations($arrays, $i = 0) {
    $key = array_keys($arrays);
    if (!isset($arrays[$key[$i]])) {
        return array();
    }
    if ($i == count($arrays) - 1) {
        return $arrays[$key[$i]];
    }

    // get vartable_combinations from subsequent arrays
    $tmp = vartable_combinations($arrays, $i + 1);

    $result = array();

    // concat each array from tmp with each element from $arrays[$i]
    foreach ($arrays[$key[$i]] as $v) {
        foreach ($tmp as $t) {
            $result[] = is_array($t) ? 
                array_merge(array($key[$i] => $v), $t) :
                array($key[$i] => $v, $key[($i+1)] => $t);
        }
    }

    return $result;
}


/****
*  
  Get all cateogries
* 
****/
function woovartables_get_all_categories($selected) {
  global $wpdb;
  
  if (empty($selected)) { $selected = array(); }

  $output = '';
  
  $args = array(
    'hide_empty' => false
  );
  
  $terms = get_terms( 'product_cat', $args );
  
  if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
      $output .= '<option '. (in_array($term->term_id, $selected)? 'selected': '') .' value="'. $term->term_id .'">' . $term->name . '</option>';
    }

  }
  
  return $output;
}


function spyros_media_upload($fname, $value = '', $ai='') {
 
// This will enqueue the Media Uploader script
wp_enqueue_media();
?>
    <div>
    <input type="text" name="<?php echo $fname; ?>" id="<?php echo $fname; ?>" value="<?php echo $value; ?>" class="regular-text">
    <input type="button" name="upload-btn<?php echo $ai; ?>" id="upload-btn<?php echo $ai; ?>" class="button-secondary button button-action" value="<?php echo __('Open Media Manager', 'vartable'); ?>"><br />
    <img class="img_<?php echo $ai; ?>" src="<?php echo $value; ?>" />

</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    jQuery('#upload-btn<?php echo $ai; ?>').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            // console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // console.log(image_url);
            // Let's assign the url value to the input field
            jQuery('input[name="<?php echo $fname; ?>"]').val(image_url);
            jQuery('img.img_<?php echo $ai; ?>').attr('src', image_url);
        });
    });
});
</script>
  <?php
}


// Add settings link on plugin page
function vartable_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=variationstable">'. __('Settings', 'vartable') .'</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$vartable_plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$vartable_plugin", 'vartable_plugin_settings_link' );

function vt_resetajaxfix() {
 $_SESSION['vtajaxfix'] = 0; 
}
add_action('wp_footer', 'vt_resetajaxfix');


// remove gift wrap frontend hook
require_once 'wp-filters-extra.php';
function vartable_gifthook_the_remove() {
  if (class_exists('WC_Product_Gift_Wrap')) {
    vartable_remove_filters_for_anonymous_class( 'woocommerce_after_add_to_cart_button', 'WC_Product_Gift_Wrap', 'gift_option_html', 10 );
  }
}

add_action( 'plugins_loaded', 'vartable_gifthook_the_remove', 1) ;



add_action( 'wp_ajax_add_variation_to_cart', 'vartable_ajax_add_variation_to_cart' );
add_action( 'wp_ajax_nopriv_add_variation_to_cart', 'vartable_ajax_add_variation_to_cart' );

function vartable_ajax_add_variation_to_cart() {

    ob_start();

    $product_id        = apply_filters( 'vartable_add_to_cart_product_id', absint( $_POST['product_id'] ) );
    $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

    $variation_id      = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : '';
    $variations        = isset( $_POST['variations'] ) ? json_decode(str_replace('||||||', '\"', stripslashes($_POST['variations'])), true) : '';

    
    
    $passed_validation = apply_filters( 'vartable_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations);

    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
        
        do_action( 'woocommerce_set_cart_cookies', TRUE );
        do_action( 'vartable_ajax_added_to_cart', $product_id );

        if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' || get_option('vartable_ajax') != 1) {
            // wc_add_to_cart_message( $product_id );
            wc_add_to_cart_message( array( $product_id => $quantity ), true );
            
            // add_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
            // add_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
        }

        // Return fragments
        if (get_option('vartable_ajax') == 1) {
          WC_AJAX::get_refreshed_fragments();
        }

    } else {

        // If there was an error adding to the cart, redirect to the product page to show any errors
        $data = array(
            'error' => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
        );

        wp_send_json( $data );

    }

    die();
}



function vtdb($out) {
  if ( is_user_logged_in() ) {
    $out = '<pre>'.print_r($out, true).'</pre>';
  }
  return $out;
}


function vartable_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

/**
* @return bool
*/
function vt_is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

function vartable_delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, $beginning.$end, $string);
}

function vartable_get_editable_roles() {
    
  $roles = array();

  foreach (get_editable_roles() as $role_name => $role_info) {
    
    $roles[$role_name] = $role_info['name'];
    
  }


  return $roles;

}
/************************************************************************************************************/
/************************************************************************************************************/
/*EDUARDO QUINTERO*/
function vartableall_func_v2( $atts ) {
    $a = shortcode_atts( array(
    'disabled'              => '',
    'categories_exc'        => '',
    'categories'            => '',
    'sku'                   => '',
    'thumb'                 => '',
    'thumb_size'            => '',
    'stock'                 => '',
    'in_stock_text'         => '',
    'out_stock_text'        => '',
    'low_stock_text'        => '',
    'low_stock_thresh'      => '',
    'hide_zero'             => '',
    'hide_outofstock'       => '',
    'vartable_zero_to_out'  => '',
    'price'                 => '',
    'offer'                 => '',
    'image'                 => '',
    'qty'                   => '',
    'default_qty'           => '',
    'vartable_qty_control'  => '',
    'cart'                  => '',
    'globalcart'            => '',
    'globalcart_status'     => '',
    'globalposition'        => '',
    'wishlist'              => '',
    'gift'                  => '',
    'order'                 => '',
    'ajax'                  => '',
    'desc'                  => '',
    'weight'                => '',
    'dimensions'            => '',
    'vartable_position'     => '',
    'desc_inline'           => '',
    'head'                  => '',
    'customhead'            => '',
    'sorting'               => '',
    'shortcd'               => 1,
    'title'                 => 0
    ), $atts );
    
  $args = array(
    'posts_per_page' => -1, 
    'post_type' => 'product',
    'status' => 'publish',
    'fields'       => 'ids'
  );
  
  if ($a['hide_zero'] == 1 || ($a['hide_zero'] == '' && get_option('vartable_hide_zero') == 1)) {
    $args['meta_key']     = '_min_price_variation_id';
    $args['meta_value']   = 0;
    $args['meta_compare'] = '>';
  }
  
  $args = apply_filters( 'vartableall_query_args', $args, $a);
  
  if (!empty($a['categories'])) {
    $args['tax_query'] = array(
      array(
          'taxonomy'  => 'product_cat',
          'field'     => 'term_id', //This is optional, as it defaults to 'term_id'
          'terms'     => explode(',',$a['categories']),
          'operator'  => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
      )
    );
  }
  
  $posts_array = get_posts( $args ); 
  
  $alltables = '';
  $i = 0;
  foreach ( $posts_array as $post_id ) {
    $a['id'] = $post_id;
	$texto = vt_woocommerce_variable_add_to_cart($a);
	if($texto != 'Este no es un producto variable' && $texto != 'This is not a variable product' && $texto != 'Now go add some variable products!'){
		$alltables .= $texto;
		$i++;
		if($i == 4)break; 
	}
  }
  return ($alltables);
}
add_shortcode( 'vartableallv2', 'vartableall_func_v2' );
/****************/
function vt_woocommerce_variable_add_to_cart($allsets) {
  global $product, $post, $woocommerce;
  $out = '';
  $headEQ = '';
  $form = '';
  $vtrand = rand(1, 100000);
  
  
  // get values from shortcode
  if ($allsets) {
    $vartable_id              = $allsets['id'];
    $vartable_disabled        = $allsets['disabled'];
    $vartable_categories_exc  = $allsets['categories_exc'];
    $vartable_roles_exc       = $allsets['roles_exc'];
    $vartable_categories      = $allsets['categories'];
    $vartable_sku             = $allsets['sku'];
    $vartable_thumb           = $allsets['thumb'];
    $vartable_thumb_size      = $allsets['thumb_size'];
    $vartable_stock           = $allsets['stock'];
    $vartable_in_stock_text   = $allsets['in_stock_text'];
    $vartable_out_stock_text  = $allsets['out_stock_text'];
    $vartable_low_stock_text  = $allsets['low_stock_text'];
    $vartable_low_stock_thresh= $allsets['low_stock_thresh'];
    $vartable_hide_zero       = $allsets['hide_zero'];
    $vartable_hide_outofstock = $allsets['hide_outofstock'];
    $vartable_zero_to_out     = $allsets['vartable_zero_to_out'];
    $vartable_price           = $allsets['price'];
    $vartable_total           = $allsets['total'];
    $vartable_offer           = $allsets['offer'];
    $vartable_image           = $allsets['image'];
    $vartable_qty             = $allsets['qty'];
    $vartable_default_qty     = $allsets['default_qty'];
    $vartable_qty_control     = $allsets['vartable_qty_control'];
    $vartable_cart            = $allsets['cart'];
    $vartable_globalcart      = $allsets['globalcart'];
    $vartable_globalcart_status = $allsets['globalcart'];
    $vartable_globalposition  = $allsets['globalposition'];
    $vartable_wishlist        = $allsets['wishlist'];
    $vartable_gift            = $allsets['gift'];
    $vartable_order           = $allsets['order'];
    $vartable_ajax            = $allsets['ajax'];
    $vartable_desc            = $allsets['desc'];
    $vartable_weight          = $allsets['weight'];
    $vartable_dimensions      = $allsets['dimensions'];
    $vartable_position        = $allsets['vartable_position'];
    $vartable_desc_inline     = $allsets['desc_inline'];
    $vartable_head            = $allsets['head'];
    $vartable_customhead      = $allsets['customhead'];
    $vartable_sorting         = $allsets['sorting'];
    $vartable_shortcd         = $allsets['shortcd'];
    $vartable_title           = $allsets['title'];
  } else {
    $vartable_id              = null;
    $vartable_disabled        = null;
    $vartable_categories_exc  = null;
    $vartable_roles_exc       = null;
    $vartable_categories      = null;
    $vartable_sku             = null;
    $vartable_thumb           = null;
    $vartable_thumb_size      = null;
    $vartable_stock           = null;
    $vartable_in_stock_text   = null;
    $vartable_out_stock_text  = null;
    $vartable_low_stock_text  = null;
    $vartable_low_stock_thresh= null;
    $vartable_hide_zero       = null;
    $vartable_hide_outofstock = null;
    $vartable_zero_to_out     = null;
    $vartable_price           = null;
    $vartable_total           = null;
    $vartable_offer           = null;
    $vartable_image           = null;
    $vartable_qty             = null;
    $vartable_default_qty     = null;
    $vartable_qty_control     = null;
    $vartable_cart            = null;
    $vartable_globalcart      = null;
    $vartable_globalcart_status = null;
    $vartable_globalposition  = null;
    $vartable_wishlist        = null;
    $vartable_gift            = null;
    $vartable_order           = null;
    $vartable_ajax            = null;
    $vartable_desc            = null;
    $vartable_weight          = null;
    $vartable_dimensions      = null;
    $vartable_position        = null;
    $vartable_desc_inline     = null;
    $vartable_head            = null;
    $vartable_customhead      = null;
    $vartable_sorting         = null;
    $vartable_shortcd         = null;
    $vartable_title           = null;
  }
  
  // check if it is a shortcode and if an id has been set
  if ($vartable_id != null) {
    $_pf = new WC_Product_Factory();  
    $product = $_pf->get_product($vartable_id);
  }
  
  if (!is_object($product)) {
    return (__('No product object found', 'vartable'));
  }
  
  $product_cats = wp_get_post_terms( $product->id, 'product_cat' );
  // get all ids
  foreach($product_cats as $pcdata) {
    $pcids[] = $pcdata->term_id;
  }
    

  // make sure that quantity is set
  if (get_option('vartable_default_qty') === false) { update_option('vartable_default_qty', 1); }
  if (get_option('vartable_qty_control') === false) { update_option('vartable_qty_control', 0); }
    
    $vartable_disabled        = ($vartable_disabled == null         ? get_option('vartable_disabled') : $vartable_disabled);
    $vartable_categories_exc  = ($vartable_categories_exc == null   ? get_option('vartable_categories_exc') : $vartable_categories_exc);
    $vartable_roles_exc       = ($vartable_roles_exc == null   ? get_option('vartable_roles_exc') : $vartable_roles_exc);
    $vartable_sku             = ($vartable_sku == null              ? get_option('vartable_sku') : $vartable_sku);
    $vartable_thumb           = ($vartable_thumb == null            ? get_option('vartable_thumb') : $vartable_thumb);
    $vartable_thumb_size      = ($vartable_thumb_size == null       ? get_option('vartable_thumb_size') : $vartable_thumb_size);
    $vartable_stock           = ($vartable_stock == null            ? get_option('vartable_stock') : $vartable_stock);
    $vartable_in_stock_text   = ($vartable_in_stock_text == null    ? get_option('vartable_in_stock_text') : $vartable_in_stock_text);
    $vartable_out_stock_text  = ($vartable_out_stock_text == null   ? get_option('vartable_out_stock_text') : $vartable_out_stock_text);
    $vartable_low_stock_text  = ($vartable_low_stock_text == null   ? get_option('vartable_low_stock_text') : $vartable_low_stock_text);
    $vartable_low_stock_thresh= ($vartable_low_stock_thresh == null ? get_option('vartable_low_stock_thresh') : $vartable_low_stock_thresh);
    $vartable_hide_zero       = ($vartable_hide_zero == null        ? get_option('vartable_hide_zero') : $vartable_hide_zero);
    $vartable_hide_outofstock = ($vartable_hide_outofstock == null  ? get_option('vartable_hide_outofstock') : $vartable_hide_outofstock);
    $vartable_zero_to_out     = ($vartable_zero_to_out == null      ? get_option('vartable_zero_to_out') : $vartable_zero_to_out);
    $vartable_price           = ($vartable_price == null            ? get_option('vartable_price') : $vartable_price);
    $vartable_total           = ($vartable_total == null            ? get_option('vartable_total') : $vartable_total);
    $vartable_offer           = ($vartable_offer == null            ? get_option('vartable_offer') : $vartable_offer);
    $vartable_image           = ($vartable_image == null            ? get_option('vartable_image') : $vartable_image);
    $vartable_qty             = ($vartable_qty == null              ? get_option('vartable_qty') : $vartable_qty);
    $vartable_default_qty     = ($vartable_default_qty == null      ? get_option('vartable_default_qty') : $vartable_default_qty);
    $vartable_qty_control     = ($vartable_qty_control == null      ? get_option('vartable_qty_control') : $vartable_qty_control);
    $vartable_cart            = ($vartable_cart == null             ? get_option('vartable_cart') : $vartable_cart);
    $vartable_globalcart      = ($vartable_globalcart == null       ? get_option('vartable_globalcart') : $vartable_globalcart);
    $vartable_globalcart_status = ($vartable_globalcart_status == null ? get_option('vartable_globalcart_status') : $vartable_globalcart_status);
    $vartable_globalposition  = ($vartable_globalposition == null   ? get_option('vartable_globalposition') : $vartable_globalposition);
    $vartable_wishlist        = ($vartable_wishlist == null         ? get_option('vartable_wishlist') : $vartable_wishlist);
    $vartable_gift            = ($vartable_gift == null             ? get_option('vartable_gift') : $vartable_gift);
    $vartable_order           = ($vartable_order == null            ? get_option('vartable_order') : $vartable_order);
    $vartable_ajax            = ($vartable_ajax == null             ? get_option('vartable_ajax') : $vartable_ajax);
    $vartable_desc            = ($vartable_desc == null             ? get_option('vartable_desc') : $vartable_desc);
    $vartable_weight          = ($vartable_weight == null           ? get_option('vartable_weight') : $vartable_weight);
    $vartable_dimensions      = ($vartable_dimensions == null       ? get_option('vartable_dimensions') : $vartable_dimensions);
    $vartable_position        = ($vartable_position == null         ? get_option('vartable_dimensions') : $vartable_position);
    $vartable_desc_inline     = ($vartable_desc_inline == null      ? get_option('vartable_desc_inline') : $vartable_desc_inline);
    $vartable_head            = ($vartable_head == null             ? get_option('vartable_head') : $vartable_head);
    $vartable_customhead      = ($vartable_customhead == null       ? get_option('vartable_customhead') : $vartable_customhead);
    $vartable_sorting         = ($vartable_sorting == null          ? get_option('vartable_sorting') : $vartable_sorting);
    
    // gift wrap option
    $default_message            = '{checkbox} '. sprintf( __( 'Gift wrap this item for %s?', 'woocommerce-product-gift-wrap' ), '{price}' );
    $gift_wrap_enabled          = get_option( 'product_gift_wrap_enabled' ) == 'yes' ? true : false;
		$gift_wrap_cost             = get_option( 'product_gift_wrap_cost', 0 );
		$product_gift_wrap_message  = get_option( 'product_gift_wrap_message');
    
    if ( ! $product_gift_wrap_message ) {
			$product_gift_wrap_message = $default_message;
		}
    
    $is_wrappable = get_post_meta( $product->id, '_is_gift_wrappable', true );
    
		if ( $is_wrappable == '' && $gift_wrap_enabled ) {
			$is_wrappable = 'yes';
		}
    
    //  if the table is disabled for this product display the default select menus
    $checkcat = array();
    if (is_array($vartable_categories_exc) && is_array($pcids)) {
      $checkcat = array_intersect($pcids, $vartable_categories_exc);
    }
    
    $checkrole = array();
    if (is_array($vartable_roles_exc) && is_user_logged_in()) {
      $user_info = get_userdata(get_current_user_id());
      $checkrole = array_intersect($user_info->roles, $vartable_roles_exc);
    }
    if (!is_user_logged_in() && is_array($vartable_roles_exc) && in_array('guest', $vartable_roles_exc)) {
      $checkrole['guest'] = 'guest';
    }
    
    if ( 
      ((get_post_meta($product->id, 'disable_variations_table', true) == 1 || !empty($checkcat)) || $vartable_disabled == 1 || !empty($checkrole)) 
      && get_post_meta($product->id, 'disable_variations_table', true) != 2 
      && $vartable_shortcd != 1) {
      // Enqueue variation scripts
      wp_enqueue_script( 'wc-add-to-cart-variation' );

      // Load the template
      wc_get_template( 'single-product/add-to-cart/variable.php', array(
          'available_variations'  => $product->get_available_variations(),
          'attributes'   			=> $product->get_variation_attributes(),
          'selected_attributes' 	=> $product->get_variation_default_attributes()
        ) );
      return;
    }
  
    if (method_exists($product,'get_variation_attributes')) {
      $varattr = $product->get_variation_attributes();
    } else {
      return (apply_filters('vartable_novariable_warning', __('This is not a variable product', 'vartable'), $product));
    }
    
    $temp_varattr = array();
    foreach($varattr as $attr_key => $attr_values) {
      $temp_varattr[sanitize_title($attr_key)] = $attr_values;
    }
    
    $varattr = array();
    $varattr = $temp_varattr;
    
    $default_feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($product->id, array($vartable_thumb_size, $vartable_thumb_size)) );
    $default_feat_imagefull = wp_get_attachment_image_src( get_post_thumbnail_id($product->id), 'full' );
	$variations = $product->get_available_variations();
    
    if (empty($variations)) {
      $out = (__('Now go add some variable products!', 'vartable'));
      if($vartable_shortcd != 1) {
        echo '<p>'.$out.'</p>';
        return;
      } else {
        return ($out);
      }
    }
    
    // if ( current_user_can( 'manage_options' ) ) { echo '<pre>'. print_r($variations, true) .'</pre><hr />'; }

    // prepare arrays to use that will hold data
    $anyvariations = array();
    $standardvariations = array();
    $fullvariations = array();
    
    // get all attributes that are valid
    $attr_keys = array_keys($product->get_attributes());
    $attr_keys2 = array();
	$i = 0;
    foreach ($attr_keys as $atkey) {
      $attr_keys2[] = str_replace(array('attribute_', 'pa_'), array('',''), $atkey);
    }
	///**EQ**///
	$cantidadVariables = count($attr_keys);
	///**EQ**///
	
    foreach ($variations as $key => $value) {
      foreach ($value['attributes'] as $attr_key => $attr_value) {
        // skip attributes that are empty and have not been set, mostly created by import plugins
        if (!in_array(str_replace(array('attribute_', 'pa_'), array('',''), $attr_key), $attr_keys2)) { continue; }
        
        if ($attr_value == '') {
          $anyvariations[$key][$value['variation_id']][str_replace('attribute_', '', $attr_key)] = $varattr[str_replace('attribute_', '', $attr_key)];
        } else {
          $standardvariations[$key][$value['variation_id']][str_replace('attribute_', '', $attr_key)] = array($attr_value);
        }
      }
    }

    
    // get variations that have all attributes set
    $fullvariations = array_diff_key($standardvariations, $anyvariations);
    $combinationarray = array();
    $combinationsresults = array();
    foreach ($anyvariations as $attrkey => $variat) {
      reset($variat);
      $variationid = key($variat);
      $countvariations = count($variat[$variationid]);
      if (isset($standardvariations[$attrkey]) && isset($variat[$variationid]) && is_array($standardvariations[$attrkey]) && is_array($variat[$variationid])) {
        $combinationarray[$attrkey] = $standardvariations[$attrkey][$variationid] + $variat[$variationid] ;
      }
      if (!is_array($variat[$variationid])) {
        $combinationarray[$attrkey] = $standardvariations[$attrkey][$variationid];
      }
      if (isset($standardvariations[$attrkey][$variationid]) && !is_array($standardvariations[$attrkey][$variationid])) {
        $combinationarray[$attrkey] = $variat[$variationid];
      }
      
    }

    // get all possible combinations for the attributes set as any
    foreach($combinationarray as $attrkey => $combarrays) {
      // more than one attribute is set on the variation
      if (count($combarrays) > 1) {
        $combinationsresults[$attrkey] = vartable_combinations($combarrays);
      } else { // ONLY ONE attribute is set on the variation
        if (is_array($combarrays)) {
          foreach($combarrays as $singleattkey => $singleattval) {
            if (is_array($singleattval)) {
              foreach($singleattval as $thesingleattr) {
                $combinationsresults[$attrkey][][$singleattkey] = $thesingleattr;
              }
            }
          }
        }
      }
    }


    $finalattr = array();
    $i = 0;
    
    // add variations that have all attributes set to the final array
    foreach ($fullvariations as $fkey => $fvalue) {
      $finalattr[$i] = $variations[$fkey];
      $i++;
    }
    
    // add variations that have attributes set as any the final array
    foreach ($combinationsresults as $attrkey => $combinations) {
      foreach ($combinations as $combinationsvalues) {
        $finalattr[$i] = $variations[$attrkey];
        if (is_array($combinationsvalues)) {
          foreach ($combinationsvalues as $attrshortkey => $attrstringvalue) {
            $finalattr[$i]['attributes']['attribute_'.$attrshortkey] = $attrstringvalue;
          }
        }
        $i++;
      }
    }
    
    $attr_ordernames = array();
    foreach ($product->get_attributes() AS $ankey => $akval) {
      if ($akval['is_variation'] == 1) {
        $attr_ordernames[str_replace(array('_', '.', ' '),array('-', '-', '-'),sanitize_title(str_replace('attribute_', '', $ankey)))] = wc_attribute_label( $akval['name'] );
      }
    }

    // order of attributes
    $attr_order = array();
    foreach ($varattr as $attrkey => $attrvalues) {
      $attr_order[] = 'attribute_'.sanitize_title($attrkey);
    }
    
    $anyextraimg = 0;
    $anydescription = 0;
    $anydimension = 0;
    $anyweight = 0;
    $head = '';
    
    ob_start();
    do_action('woocommerce_before_add_to_cart_form', $product->id);
    $woocommerce_before_add_to_cart_form = ob_get_clean();
    $out .= $woocommerce_before_add_to_cart_form;
    
    ob_start();
    do_action('vartable_before_table', $product->id);
    $vartable_before_table = ob_get_clean();
    $out .= $vartable_before_table;
    
    ob_start();
    do_action('vartable_table_class', $product->id);
    $vartable_table_class = ob_get_clean();
    
    $sorting_js = apply_filters( 'vartable_sorting_js', get_option('vartable_sorting'), $product->id);
    $sorting_col = apply_filters( 'vartable_sorting_column', get_post_meta($product->id, 'custom_variations_preordering', true), $product->id);
    $sorting_direction = apply_filters( 'vartable_sorting_direction', get_post_meta($product->id, 'custom_variations_preordering_direction', true), $product->id);
    
    //if (($vartable_globalcart == 1 || $vartable_globalcart == 2) && ($vartable_globalposition == 'top' || $vartable_globalposition == 'both')) {  
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
	  //*EQ*//
      $headEQ .= '<thead><tr><th scope="col" style=" background: #9e4777; color: white;"><a href="'. get_permalink($product->id)  .'" title="'. get_the_title($product->id) .'">'. get_the_title($product->id) .'</a></th>';
      //*EQ*//     
      $vt_button_classes = apply_filters('vartable_global_button_classes', array(
          'single_add_to_cart_button',
          'btn',
          'button',
          'button_theme',
          'ajax',
          'add_to_cart',
          'avia-button',
          'fusion-button',
          'button-flat',
          'button-round',
          'alt'
        )
      );
 
      /*$out .= apply_filters('vartable_global_btn', '
        <div class="vartable_gc_wrap vartable_gc_wrap_top">
          <a data-position="top" href="#globalcart" class="globalcartbtn submit '. implode(' ', $vt_button_classes) .'" data-product_id="gc_'.$product->id .'" id="gc_'. $vtrand .'_top" class="btn button alt">'. __('Add selected to cart', 'vartable') .'<span class="vt_products_count"></span></a>
          <span class="added2cartglobal added2cartglobal_'. $vtrand .'">&#10003;</span>
          <span class="vtspinner vtspinner_top vtspinner_'. $vtrand .'"><img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" /></span>
        </div>', $product, 'top', $vtrand);
    */
    $cartredirect = get_option('woocommerce_cart_redirect_after_add');
    
    //$out .= '<div class="col-md-6 grid-item2"><table 
	$out .= '<div class="grid-item-masonry"><table     
	  id="tb_'. $vtrand .'" 
      class="table vartable '. ($sorting_js == 1 ? 'is_sortable' : '') .' '. $vartable_table_class .'" 
      data-random="'. $vtrand .'" 
      '. ($sorting_js == 1 ? 'data-sort="yes"' : 'data-sort="no"') .' 
      '. ($vartable_ajax == 1 ? 'data-vartable_ajax="1"' : 'data-vartable_ajax="0"').' 
      '. ($cartredirect == 'yes' ? 'data-cartredirect="yes"' : 'data-cartredirect="no"') .' 
      data-globalcart="'. $vartable_globalcart .'"
      '. ($sorting_col != '' ? 'data-preorder="'. $sorting_col .'"' : 'data-preorder=""') .' 
      '. ($sorting_direction != '' ? 'data-preorder_direction="'. $sorting_direction .'"' : 'data-preorder_direction=""') .' 
      >
    
      %headplaceholder%
    ';    
    
    $out .= '<tbody>';
    $moi = 0;
    $mo_reset = 0;
    $menu_order = array();
    foreach ($finalattr as $vt_variation_array) {
      $vt_variation_object = get_post($vt_variation_array['variation_id']);
      
      if ($mo_reset != $vt_variation_object->menu_order && $vt_variation_object->menu_order != 0) {
        $mo_reset = $vt_variation_object->menu_order;
        $moi = 0;
      }
      $menu_order[$vt_variation_array['variation_id']] = ($vt_variation_object->menu_order * 100) + $moi++;
    }
    
    $ri = 0;
    $vt_trows = array();
	/**AQUI COMIENZA EL DOBLE FOR **/
	///**EQ**///
	$arrayFilaTexto = array();
	$arraycolumnaTexto = array();
	$mostrarTitulo = true;
	$cantidadColumnas = 0;
	///**EQ**///
	foreach ($finalattr as $key => $value) {
	  // create an array to hold all TDs
      $allcolumns = array();
      $product_variation = new WC_Product_Variation($value['variation_id']);
      
      $vartable_qty_default = get_post_meta($value['variation_id'], 'vartable_qty_default', true);
      
      if (get_post_meta($value['variation_id'], 'vt_variation_hide', true) == 'yes') { continue; }
      if (!($product_variation->get_price() > 0) && $vartable_hide_zero == 1) { continue; }
      
      $varstock = $product_variation->get_stock_quantity();

      if (!($varstock > 0) && $vartable_hide_outofstock == 1) { continue; }
      
      if (!isset($vt_trows[$menu_order[$value['variation_id']]])) {
        $vt_trows[$menu_order[$value['variation_id']]] = '';
      }
      
      ob_start();
      do_action('woocommerce_before_single_variation', $product->id, $value);
      $woocommerce_before_single_variation = ob_get_clean();
      
      ob_start();
      do_action('vartable_inside_add_to_cart_form', $product->id, $value);
      $vartable_inside_add_to_cart_form = ob_get_clean();
      
      $vt_trows[$menu_order[$value['variation_id']]] .= $woocommerce_before_single_variation .'<tr class="'. $value['variation_id'] .' '.(get_post_meta($value['variation_id'], '_stock_status', true) != 'outofstock' ? 'instock' : 'outofstock') .' '. ($product_variation->is_purchasable() ? 'is_purchasable' : '' ) .'" data-price="'.wc_format_decimal($product_variation->get_display_price(), 2) .'">
          ';  
    
		$form .= '
		<form action="'. esc_url( $product->add_to_cart_url() ) .'" method="POST" data-variation_id="'.  $value['variation_id'].$ri .'" id="vtvariation_'. $value['variation_id'].$ri .'" class="vtajaxform" enctype="multipart/form-data">
			<input type="hidden" name="variation_id" value="'. $value['variation_id'] .'" />
			<input type="hidden" name="product_id" value="'. esc_attr( $product->id ) .'" />
			<input type="hidden" name="add-to-cart" value="'. esc_attr( $product->id ) .'" />
      '. $vartable_inside_add_to_cart_form .'
      ';
            
      if ($product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed())) {
        $form .= '<input type="hidden" class="hidden_quantity" name="quantity" value="'. ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)) .'" />';
      }
      
      $form .= '<input type="hidden" class="gift_wrap" name="gift_wrap" value="" />';
      
      $js_attr_arr = array();
      $js_attr_arr_full = array();
			if(!empty($value['attributes'])){
				foreach ($value['attributes'] as $attr_key => $attr_value) {
          if ($attr_value != '') {
				
            $form .= '<input type="hidden" class="form_vartable_attribute" name="'. $attr_key .'" value="'. $attr_value .'" />
            ';
            
            $js_attr_arr[str_replace(array('attribute_pa_', 'attribute_'), array('',''), $attr_key)] = htmlentities(str_replace('"', '||||||', $attr_value), ENT_QUOTES, 'utf-8', FALSE);
            $js_attr_arr_full[$attr_key] = htmlentities(str_replace('"', '||||||', $attr_value), ENT_QUOTES, 'utf-8', FALSE);
          }
				}
        
        $form .= '<input type="hidden" class="form_vartable_attribute_json" name="form_vartable_attribute_json" value=\''. (json_encode($js_attr_arr)) .'\' />
            ';
        $form .= '<input type="hidden" class="form_vartable_attribute_array" name="form_vartable_attribute_array" value=\''. (json_encode($js_attr_arr_full)) .'\' />
            ';
			}
			
		$headenames = vt_fields_func();
            // get attribute names
            $attrnames = array();
            $orderedattributes = array_merge(array_flip($attr_order), $value['attributes']);
            foreach ($orderedattributes as $taxon => $taxval) {	
              $taxonkey = sanitize_title(str_replace(array('-', '.', ' '),array('_', '_', '_'),$taxon));

              $temp = '';
              $temp = get_term_by('slug', $taxval, str_replace('attribute_', '', $taxon));
              // echo vtdb($taxval);
              if ($temp !== false) {
                
                $attrnames[$taxonkey] = apply_filters( 'woocommerce_variation_option_name', $temp->name, $value);
                
              } else {
                // get all custom attributes sanitize_title
                if (strpos($product->get_attribute( str_replace('attribute_', '', $taxon) ), '|') !== false) {
                  $allcustomattr = explode('|',$product->get_attribute( str_replace('attribute_', '', $taxon) ));
                } else {
                  $allcustomattr = explode(', ',$product->get_attribute( str_replace('attribute_', '', $taxon) ));
                }
                
                $customattrnames = array();
                
                foreach ($allcustomattr as $customattrname) {
                  $customattrnames[sanitize_title(trim($customattrname))] = apply_filters( 'woocommerce_variation_option_name', $customattrname, $value);
                }
                
                if ($taxval !== false && $taxval !== null && $taxval !== '' && isset($customattrnames[sanitize_title(trim($taxval))])) {
                  
                  $attrnames[$taxonkey] = apply_filters( 'woocommerce_variation_option_name', $customattrnames[sanitize_title(trim($taxval))], $value);
                  
                }
                
              }
            }
          
            // removed on version 1.1.6
						// $allcolumns['vartable_variations'] = '<td class="optionscol" data-label="'. $headenames['vartable_variations'] .'">
              // '. implode( apply_filters( 'vartable_attributes_join', '</td><td>'), apply_filters( 'vartable_attributes_array', $attrnames)) .'
            // </td>
            // ';
                        
            $attrnames = apply_filters( 'vartable_attributes_array', $attrnames, $value);
			$k = 0;
            foreach($attrnames as $attr_slug => $attr_td_value) {
				if (!isset($allcolumns['vartable_variations'])) {
					$allcolumns['vartable_variations'] = '' ;
				}
				if($k == 1){ 
				    if(!in_array($attr_td_value, $arrayFilaTexto)){
						$headEQ .= '<th style="text-align: center;">'.$attr_td_value.'</th>';
						array_push($arrayFilaTexto,$attr_td_value);
					}
				}else{
					$k++;
					//if($mostrarTitulo){
						$allcolumns['vartable_variations'] .= apply_filters( 'vartable_attributes_join', '<td class="optionscol '. $attr_slug .'" data-sort-value="'. wc_clean(trim($attr_td_value)) .'" data-label="'. apply_filters('vartable_dl_options', $attr_ordernames[str_replace(array('_', '.', ' '),array('-', '-', '-'),sanitize_title(str_replace('attribute_', '', $attr_slug)))]) .'">'. $attr_td_value .'</td>', $value);
						//$mostrarTitulo = false;
					//}
				}
            }
            if ($vartable_price == 1) {
              $allcolumns['vartable_price'] = '
              <td class="pricecol" 
                data-label="'. apply_filters('vartable_dl_price', $headenames['vartable_price'], $value) .'" 
                data-price="'.wc_format_decimal($product_variation->get_display_price(), 2) .'" 
                data-sort-value="'. wc_format_decimal($product_variation->get_display_price(), 2) .'">
                '. $product_variation->get_price_html() .'
              </td>';
            }
            if ($vartable_total == 1) {
              $allcolumns['vartable_total'] = '
              <td class="totalcol" data-label="'. apply_filters('vartable_dl_total', $headenames['vartable_total'], $value) .'" data-sort-value="'. wc_format_decimal($product_variation->get_display_price() * ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)), 2) .'">
                '. wc_price($product_variation->get_display_price() * ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value))) .'
                '. (get_option('woocommerce_price_display_suffix') != '' ? ' '.get_option('woocommerce_price_display_suffix') : '') .'
              </td>';
            }

            $override_extra_image = get_post_meta($value['variation_id'], 'override_extra_image', true);
            $enable_extra_image = get_post_meta($value['variation_id'], 'enbable_variations_table_img', true);
            $vartable_qty_step = get_post_meta($value['variation_id'], 'vartable_qty_step', true);
            
            // if not set, then set it to 1
            if (intval($vartable_qty_step) == 0) { $vartable_qty_step = 1; }
            
            
            if (get_post_meta( $product->id, 'disable_variations_table_offer', true ) != 1) {
            
              if ($vartable_offer == 1 || $enable_extra_image == 'yes' ) {
                
                $allcolumns['vartable_offer'] = '<td class="offercol"  data-label="'. apply_filters('vartable_dl_offer', $headenames['vartable_offer'], $value) .'">';

                if (!empty($override_extra_image) && $enable_extra_image != 'no') {
                  $allcolumns['vartable_offer'] .= '<img src="'. $override_extra_image .'" alt="'.  __('offer', 'vartable') .'" />';
                  $anyextraimg = 1;
                } 
                if ($vartable_image !='' && $enable_extra_image != 'no' && empty($override_extra_image)) {
                  $allcolumns['vartable_offer'] .= '<img src="'. $vartable_image .'" alt="'.  __('offer', 'vartable') .'" />';
                  $anyextraimg = 1;
                }
                
                $allcolumns['vartable_offer'] .= '</td>';
              }
            }
            

            if ($vartable_qty == 1) { 
              $allcolumns['vartable_qty'] = '
              <td class="qtycol" data-price="'.wc_format_decimal($product_variation->get_display_price(), 2) .'" data-variation="'.$value['variation_id'].'" data-label="'. apply_filters('vartable_dl_qty', $headenames['vartable_qty'], $value) .'">
			  <input type="hidden" class="hidden_price" name="hidden_price" value="'.wc_format_decimal($product_variation->get_display_price(), 2) .'">
			  <input type="hidden" class="hidden_variation" name="hidden_variation" value="'.$value['variation_id'].'">
			  <input type="hidden" class="hidden_product" name="hidden_variation" value="'.$product->id.'">';
                if ($product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed()) ) {
                  
                  if ($vartable_qty_control == 1) {
                  
                    $allcolumns['vartable_qty'] .= '
                    <div class="qtywrap">
                    ';
                    
                    $allcolumns['vartable_qty'] .= '
                    <div class="minusqty qtycontrol">-</div>
                    ';
                  
                  }
                  
                  $allcolumns['vartable_qty'] .= '
                    <input type="number" step="'. apply_filters('vartable_qty_step', $vartable_qty_step, $value) .'" name="var_quantity" value="'. ($vartable_qty_default > 0 ? $vartable_qty_default : apply_filters('vartable_default_qty', $vartable_default_qty, $value)) .'" title="Qty" class="input-text qty text" size="4" '. (intval($value['min_qty']) > 0 && !isset($vartable_default_qty) ? 'min="'. $value['min_qty'] .'"': 'min="0"') .' '. (intval($value['max_qty']) > 0 ? 'max="'. $value['max_qty'] .'"': '') .'>
                  ';
                
                  if ($vartable_qty_control == 1) {
                
                    $allcolumns['vartable_qty'] .= '
                      <div class="plusqty">+</div>
                    ';
                    
                    $allcolumns['vartable_qty'] .= '
                      </div>
                    ';
                    
                  }
                }
              $allcolumns['vartable_qty'] .= '</td>';
            }
            
            
          ob_start();
          do_action('woocommerce_add_to_cart_class', $product->id, $value);
          $woocommerce_add_to_cart_class = ob_get_clean();
          
          ob_start();
          do_action('woocommerce_before_add_to_cart_button', $product->id, $value);
          $woocommerce_before_add_to_cart_button = ob_get_clean();
          
          $allcolumns['vartable_cart'] = '<td class="cartcol '. ($vartable_cart == 0 ? 'vartablehide' : '') .' '. $woocommerce_add_to_cart_class .'" data-label="">'.$woocommerce_before_add_to_cart_button;
          
          // if is purchasable          
          if ( $product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed())) {
            
            // if is out of stock and backorder are allowed
            if (
              (get_post_meta($value['variation_id'], '_stock_status', true) != 'instock' && !empty($value['backorders_allowed'])) 
              ||
              ($vartable_zero_to_out == 1 && $varstock == 0 && get_post_meta($value['variation_id'], '_manage_stock', true) == 'yes')
            ) { 
              $carttext = __( 'Backorder', 'vartable' ); 
            } else { 
              $carttext = __('Add to cart', 'vartable' ); 
            }
            
            $vt_button_classes = apply_filters('vartable_single_button_classes', array(
                  'single_add_to_cart_button',
                  'button',
                  'button_theme',
                  'ajax',
                  'add_to_cart',
                  'avia-button',
                  'fusion-button',
                  'button-flat',
                  'button-round',
                  'alt'
                )
              );
            
            
            $allcolumns['vartable_cart'] .= $form.'
              <button id="add2cartbtn_'. $value['variation_id'].$ri .'" type="submit" data-product_id="'. $value['variation_id'] .'" class="'. implode(' ', $vt_button_classes) .' alt">'. apply_filters('single_add_to_cart_text', $carttext, $product->product_type, $value) .'</button>';
            if ($vartable_ajax == 1 || $vartable_globalcart == 1 || $vartable_globalcart == 2) {
              $allcolumns['vartable_cart'] .= '
                <div class="added2cartwrap" id="added2cart_'.$value['variation_id'].$ri.'"><span class="added2cart" >&#10003;</span></div>
                <span class="vtspinner singlebtn vtspinner_'. $value['variation_id'].$ri .'">
                  <img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" />
                </span>
                ';
            } else {
              $allcolumns['vartable_cart'] .= '
                <div class="added2cartwrap notvisible" id="added2cart_'.$value['variation_id'].$ri.'"></div>
                <span class="vtspinner vtspinner_'. $value['variation_id'].$ri .' notvisible"></span>
                ';
            }
          }
          
          ob_start();
          do_action('woocommerce_after_add_to_cart_button', $product->id, $value);
          $woocommerce_after_add_to_cart_button = ob_get_clean();
          
          $allcolumns['vartable_cart'] .= $woocommerce_after_add_to_cart_button .'</form></td>';
          
          // empty $form
          $form = '';
          
          if ($vartable_globalcart == 1 || $vartable_globalcart == 2) {
            
            $allcolumns['vartable_globalcart'] = '<td style="display:none;opacity:-1" class="globalcartcol '. ($vartable_globalcart == 2 ? 'vartablehide' : '') .'" data-label="">';
            if ( $product_variation->is_purchasable() && ($product_variation->is_in_stock() || $product_variation->backorders_allowed()) ) {
              $allcolumns['vartable_globalcart'] .= '  <input type="checkbox" class="globalcheck" name="check_'. $value['variation_id'].$ri .'" value="1" '. ($vartable_globalcart == 2 || $vartable_globalcart_status == 1 ? 'checked="checked"' : '') .'>';
            }
            $allcolumns['vartable_globalcart'] .= '</td>';
          }
          /*
          if ($vartable_wishlist == 1 && defined( 'YITH_WCWL' )) {
            $url=strtok($_SERVER["REQUEST_URI"],'?');
            parse_str($_SERVER['QUERY_STRING'], $query_string);
            $query_string['add_to_wishlist'] = basename($value['variation_id']);
            $rdr_str = http_build_query($query_string);
          
          $wishlist = do_shortcode('[yith_wcwl_add_to_wishlist product_id='. $product->id .' icon="fa-heart"]');
          
          $allcolumns['vartable_wishlist'] = '
            <td class="wishcol" data-label="'. apply_filters('vartable_dl_wishlist', $headenames['vartable_wishlist'], $value) .'">
              '. vartable_delete_all_between('</i>', '</a>', $wishlist) .'
            </td>';
          }

          if ( $is_wrappable == 'yes' && $vartable_gift == 1) {

            $current_value = ! empty( $_REQUEST['gift_wrap'] ) ? 1 : 0;

            $cost = get_post_meta( $product->id, '_gift_wrap_cost', true );

            if ( $cost == '' ) {
              $cost = $gift_wrap_cost;
            }

            $price_text = $cost > 0 ? woocommerce_price( $cost ) : __( 'free', 'woocommerce-product-gift-wrap' );
            $checkbox   = '<input type="checkbox" class="var_gift_wrap" name="var_gift_wrap" value="yes" ' . checked( $current_value, 1, false ) . ' />';
            
            
            $allcolumns['vartable_gift'] = '
            <td class="giftcol" data-label="'. apply_filters('vartable_dl_gift', $headenames['vartable_gift'], $value) .'">
              <label>'.  str_replace(array('{price}', '{checkbox}',), array($price_text, $checkbox), $product_gift_wrap_message) .'</label>
            </td>';          
          
          }
          
          if ($vartable_desc == 1 && $vartable_desc_inline == 1) {
            
            $vt_variation_description = '';
            $vt_variation_description = get_post_meta($value['variation_id'], 'vt_variation_description', true);
            
            
            if (!$vt_variation_description && isset($value['variation_description'])) {
              
              $vt_variation_description = $value['variation_description'];
              
            }
            
              $allcolumns['vartable_desc'] = '
              <td class="desccol" data-label="'. apply_filters('vartable_dl_desc', $headenames['vartable_desc'], $value) .'">'. $vt_variation_description .'</td>';
            if ($vt_variation_description != '') {
              $anydescription = 1;
            }
            
          }
          
          if ($vartable_dimensions == 1) {
            $vartable_dimensions_str = '';
            $vartable_dimensions_str = $product_variation->get_dimensions();

            $allcolumns['vartable_dimensions'] = '
              <td class="dimensions_col" data-label="'. apply_filters('vartable_dl_dimensions', $headenames['vartable_dimensions'], $value) .'">'. $vartable_dimensions_str .'</td>';
            if ($product_variation->has_dimensions()) {
              $anydimension = 1;
            }
            
          }
          
          if ($vartable_weight == 1) {
            
            if ($product_variation->has_weight()) {
            $allcolumns['vartable_weight'] = '
              <td class="weight_col" data-sort-value="'. $product_variation->get_weight() .'" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'">'. $product_variation->get_weight().($product_variation->has_weight() ? ' '.get_option('woocommerce_weight_unit') : '') .'</td>';
              $anyweight = 1;
            } else {
              $allcolumns['vartable_weight'] = '
              <td class="weight_col" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'"></td>';
            }
            
          }
          */

          // order columns
          //$orderedcols = array();
          if (is_array($vartable_order)) {
            foreach ($vartable_order as $vokey => $vovalue) {
              if (isset($allcolumns[$vokey])) {
                $orderedcols[$vokey] = $allcolumns[$vokey];
              }
            }
          } else {
            $orderedcols = $allcolumns;
          }
		  $valorColumna = $orderedcols['vartable_variations'];
		  if(empty($arraycolumnaTexto)){ 
			array_push($arraycolumnaTexto,$valorColumna);
		  }
		  /**Se hace la tarea de agregar datos a la columna**/
		  $orderedcols = array_filter($orderedcols);
          /**Se verifica que cambio de categoria**/
		  if(!in_array($valorColumna, $arraycolumnaTexto)){
			//$mostrarTitulo = true;
			$cantidadColumnas++;
			$arraycolumnaTexto = array();			
			array_push($arraycolumnaTexto,$valorColumna);
		  }
		  $menu_order[$value['variation_id']] = $cantidadColumnas;
		  $vt_trows[$menu_order[$value['variation_id']]] .= implode ( apply_filters('vartable_output_array', $orderedcols, $value, $attrnames));
		  $ri++; 
		  ob_start();
          do_action('woocommerce_after_single_variation', $product->id, $value);
          $woocommerce_after_single_variation = ob_get_clean();
		  //$vt_trows[$menu_order[$value['variation_id']]] .= '</tr>'.$woocommerce_after_single_variation.'';
		}
    $out .= implode ("\n", apply_filters('vartable_output_rows_by_id', $vt_trows, $product->id));
    
    
    ob_start();
    //do_action('woocommerce_after_add_to_cart_form', $product->id);
    //$woocommerce_after_add_to_cart_form = ob_get_clean();
    
    $out .= '</tbody></table>'. $woocommerce_after_add_to_cart_form.'';
    
    if (($vartable_globalcart == 1 || $vartable_globalcart == 2) && ($vartable_globalposition == 'bottom' || $vartable_globalposition == 'both')) {
      
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
      
      $vt_button_classes = apply_filters('vartable_global_button_classes', array(
            'single_add_to_cart_button',
            'btn',
            'button',
            'button_theme',
            'ajax',
            'add_to_cart',
            'avia-button',
            'fusion-button',
            'button-flat',
            'button-round',
            'alt'
          )
        );
 
      $out .= apply_filters('vartable_global_btn', '
        <div style=" text-align: center; display: grid; " class="vartable_gc_wrap vartable_gc_wrap_bottom">
          <a data-position="bottom" href="#globalcart" style=" background: #9e4777; color: white; " class="globalcartbtn submit '. implode(' ', $vt_button_classes) .'" data-product_id="gc_'.$product->id .'" id="gc_'. $vtrand .'_bottom" class="btn button alt">'. __('Add selected to cart', 'vartable') .'<span class="vt_products_count"></span></a>
          <span class="added2cartglobal added2cartglobal_'. $vtrand .'">&#10003;</span>
          <span class="vtspinner vtspinner_bottom vtspinner_'. $vtrand .'"><img src="'. plugins_url('images/spinner.png', __FILE__) .'" width="16" height="16" alt="spinner" /></span>
        </div>
      ', $product, 'bottom', $vtrand );
    }
	$out .= '</div>';
    if ($vartable_ajax == 1 || $vartable_globalcart == 1 || $vartable_globalcart == 2) {
      if (isset($_SESSION['vtajaxfix']) && $_SESSION['vtajaxfix'] != 1) {
      $_SESSION['vtajaxfix'] = 1;
      
      ob_start();
      do_action('vartable_add_gc_button', $product->id);
      $vartable_add_gc_button = ob_get_clean();
      

      }
    }
    
    
    ob_start();
    do_action('vartable_after_table', $product->id);
    $vartable_after_table = ob_get_clean();
    
    ob_start();
    do_action('woocommerce_product_meta_end', $product->id);
    $woocommerce_product_meta_end = ob_get_clean();
    
    $out .= $vartable_after_table;
    $out .= $woocommerce_product_meta_end;
    
    if ($vartable_head == 1 && get_post_meta( $product->id, 'disable_variations_table_header', true ) != 1) {
    
      // order header
      $headenames = vt_fields_func();
      $headenames['vartable_cart'] = '';
      $headenames = apply_filters( 'vartable_headenames', $headenames, $product->id);
      $orderedheader = array();
      
      if (is_array($vartable_order)) {
      
        if ($anyextraimg == 1 && get_post_meta( $product->id, 'disable_variations_table_offer', true ) != 1) {
          $vartable_order['vartable_offer'] = __('Offer Image', 'vartable');
        }
        $vi = 0;
        foreach ($vartable_order as $vokey => $vovalue) {
          if (($vokey == 'vartable_gift' && $is_wrappable != 'yes') || $vokey == 'vartable_sorting') {
            continue;
          }
          $sortingval = ' data-sort="float" ';
          if ($vokey == 'vartable_thumb' || $vokey == 'vartable_gift' || $vokey == 'vartable_wishlist' || $vokey == 'vartable_cart' || $vokey == 'vartable_offer') {
            $sortingval = ' data-sort="string" ';
          }
      if ($vokey == 'vartable_globalcart' || $vokey == 'vartable_cart') {
            $sortingval = '';
          }
          if ($vokey == 'vartable_qty') {
            $sortingval = ' data-sort="int" ';
          }
          if ($vokey == 'vartable_price') {
            $sortingval = ' data-sort="float" ';
          }
          
          if ( (isset(${$vokey}) && ${$vokey} == 1) || ($vokey == 'vartable_offer' && $anyextraimg == 1) || $vokey == 'vartable_variations') {
            
            if ($vokey == 'vartable_wishlist' && defined( 'YITH_WCWL' ) == false) {
              continue;
            }
            
            if ($vokey == 'vartable_variations') {
              
              $orderedheader[$vokey] = '';
              
              foreach($attr_ordernames as $attrslug => $attrval) {
                
                ob_start();
                do_action('vartable_variations_th', $product->id, $attrslug);
                $vartable_variations_th = ob_get_clean();
                
                $orderedheader[$vokey] .= '
                  <th '. $sortingval .' class="'.$vokey .' '. $attrslug .'" '. $vartable_variations_th .'>
                    <span>
                      '. apply_filters( 'vartable_header_attributes_join' , $attrval, $product->id, $attrslug) .'
                    </span>
                  </th>
                  ';
              }
              
            } elseif ($vokey == 'vartable_globalcart' && ($vartable_globalcart == 1 || $vartable_globalcart == 2)) {
              $orderedheader[$vokey] = '<th '. $sortingval .'class="'.$vokey .' '. ($vartable_globalcart == 2 ? 'vartablehide' : '') .'"><div class="vartable_selectall button btn"><label for="vtselectall_'. $vtrand .'">'. apply_filters( 'vartable_header_text' , __('Select All', 'vartable'), $product->id) .' <input class="vartable_selectall_check" id="vtselectall_'. $vtrand .'" type="checkbox" id="selecctall"/></label></div></th>';
            } else {
              $orderedheader[$vokey] = '<th '. $sortingval .' class="'.$vokey .'" ><span>'.apply_filters( 'vartable_header_text' , $headenames[$vokey], $product->id).'</span></th>';
            }
          }
          $vi++;
        }
      }
      
      if ($anyextraimg == 0) {
        unset($orderedheader['vartable_offer']);
        unset($allcolumns['vartable_offer']);
      }
      if ($anydescription == 0 || $vartable_desc_inline != 1) {
        unset($orderedheader['vartable_desc']);
      }
      if ($anydimension == 0) {
        unset($orderedheader['vartable_dimensions']);
      }
      if ($anyweight == 0) {
        unset($orderedheader['vartable_weight']);
      }
      
      $joinedheader = implode('', apply_filters( 'vartable_header_th', $orderedheader, $product->id));
    
      if (get_post_meta( $product->id, 'custom_variations_table_header', true ) != '') {
        $head .= get_post_meta( $product->id, 'custom_variations_table_header', true );
      } elseif ($vartable_customhead != '' && get_post_meta( $product->id, 'custom_variations_table_header_skip', true ) != 1) {
        
        $head .= $vartable_customhead;
        
      } else {
      
        
      }
    }
	$headEQ .= '</tr> </thead>';
	$out = str_replace('%headplaceholder%',$headEQ ,$out);
    if ($anyextraimg == 0) {
      $out = str_replace('<td class="offercol" data-label="'. apply_filters('vartable_dl_offer', $headenames['vartable_offer'], $product->id) .'"></td>', '', $out);
    }
    
    if ($anydescription == 0 && $vartable_desc_inline == 1) {
      $out = str_replace('<td class="desccol" data-label="'. apply_filters('vartable_dl_desc', $headenames['vartable_desc'], $value) .'"></td>', '', $out);
    }
    
    if ($anyweight == 0) {
      $out = str_replace('<td class="weight_col" data-label="'. apply_filters('vartable_dl_weight', $headenames['vartable_weight'], $value) .'"></td>', '', $out);
    }
    if ($anydimension == 0) {
      $out = str_replace('<td class="dimensions_col" data-label="'. apply_filters('vartable_dl_dimensions', $headenames['vartable_dimensions'], $value) .'"></td>', '', $out);
    }
    
    // removed on version 1.2.7 
    // it was causing some woocommerce shotcodes to break
    // wp_reset_query();
    // wp_reset_postdata();
    
    if($vartable_shortcd != 1) {
      echo $out;
    } else {
      return ($out);
    }
}
/*FIN EDUARDO QUINTERO*/
/************************************************************************************************************/
/************************************************************************************************************/
?>