<?php
  
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
  
// add menu under woocommerce
add_action('admin_menu', 'variations_table_menu_addition', 100);
function variations_table_menu_addition() {
  add_submenu_page( 'woocommerce', 'Variations Table', 'Variations Table', 'manage_options', 'variationstable', 'vartable_options' );
}

add_action( 'admin_init', 'vartable_register_settings' );


function vartable_sortable() {
  
  $sortable = array(
    'vartable_sku' => __('SKU', 'vartable'),
    'vartable_thumb' => __('Thumbnail', 'vartable'),
    'vartable_stock' => __('Stock', 'vartable'),
    'vartable_price' => __('Price', 'vartable'),
    'vartable_total' => __('Total', 'vartable'),
    'vartable_offer' => __('Offer Image', 'vartable'),
    'vartable_cart' => __('Add to Cart Button', 'vartable'),
    'vartable_globalcart' => __('Global Add to Cart Checkbox', 'vartable'),
    'vartable_qty' => __('Quantity', 'vartable'),
    'vartable_weight' => __('Weight', 'vartable'),
    'vartable_dimensions' => __('Dimensions', 'vartable'),
    'vartable_wishlist' => __('Whishlist', 'vartable'),
    'vartable_gift' => __('Gift Wrap', 'vartable'),
    'vartable_desc' => __('Description', 'vartable'),
  );
  
  
  return apply_filters( 'vartable_sortable_filter', $sortable );
}

function vartable_not_sortable(){
  
  $notsoratble = array(
    'vartable_disabled' => __('Disable Globally', 'vartable'),
    'vartable_categories_exc' => __('Categories Exclusion', 'vartable'),
    'vartable_roles_exc' => __('Roles Exclusion', 'vartable'),
    'vartable_thumb_size' => __('Thumbnail Size', 'vartable'),
    'vartable_in_stock_text' => __('In Stock Text', 'vartable'),
    'vartable_out_stock_text' => __('Out of Stock Text', 'vartable'),
    'vartable_low_stock_text' => __('Low Stock Text', 'vartable'),
    'vartable_low_stock_thresh' => __('Low Stock Threshold', 'vartable'),
    'vartable_hide_zero' => __('Hide zero priced variations', 'vartable'),
    'vartable_hide_outofstock' => __('Hide Out of Stock Variations', 'vartable'),
    'vartable_zero_to_out' => __('Treat zero quantity variations as Out of Stock', 'vartable_zero_to_out'),
    'vartable_image' => __('Offer Image File', 'vartable'),
    'vartable_globalcart_status' => __('Global Add to Cart Checkbox Status', 'vartable'),
    'vartable_globalposition' => __('Global Add to Cart Button Position', 'vartable'),
    'vartable_default_qty' => __('Default Quantity', 'vartable'),
    'vartable_qty_control' => __('Display Quantity Controls ( - / + buttons)', 'vartable'),
    'vartable_position' => __('Table position', 'vartable'),
    'vartable_order' => __('Columns Order', 'vartable'),
    'vartable_ajax' => __('Enable Ajax Add to Cart', 'vartable'),
    'vartable_desc_inline' => __('Description Inline', 'vartable'),
    'vartable_head' => __('Table Head', 'vartable'),
    'vartable_customhead' => __('Custom Table Head', 'vartable'),
    'vartable_sorting' => __('Enable Sorting', 'vartable')
  );

  return apply_filters( 'vartable_not_sortable_filter', $notsoratble);
  
}

function vt_fields_func() {
  
  $notsoratble  = vartable_not_sortable();
  $sortable     = vartable_sortable();
  
  $fields_array = array_merge($notsoratble, $sortable);
  
  return (apply_filters( 'vartable_fields_func_filter', $fields_array));
}

// register settings
function vartable_register_settings(){
  $fields = vt_fields_func();
  
  foreach($fields as $field => $fieldtext) {
    register_setting( 'vartable_group', $field ); 
  }
}


function vartable_options() {
  
  if (isset($_GET['hidebar']) && $_GET['hidebar'] == 1) {
    update_option('vt_sidehide', 1);
  }
  
  $hidebar = get_option('vt_sidehide', 0);
  ?>
  <div class="wrap">
    <h2><?php _e('Variations Table Grid Settings', 'vartable'); ?></h2>
    <div class="<?php if ($hidebar !=1) { echo 'leftpanel'; } ?>">    
      <form method="post" action="options.php">
            <?php settings_fields( 'vartable_group' ); ?>
            <?php do_settings_sections( 'vartable_group' ); ?>
            <div class="fieldwrap">
            <p><?php submit_button(__('Save Changes')); ?></p>
            </div>
            <?php
                        
              
            $fields = vt_fields_func();

            foreach($fields as $field => $fieldtext) {
              ${$field}  = get_option($field);
            }
            
            do_action('vartable_before_extra_options');

            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_disabled">'. __('Disable globally', 'vartable') .'</label>
              <select name="vartable_disabled" id="vartable_disabled">
                <option value="1" '. ($vartable_disabled == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_disabled == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            
            // categories        
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_categories_exc">'. __('Disable in Categories', 'vartable') .'</label>
              <select multiple name="vartable_categories_exc[]" id="vartable_categories_exc">
              '. 	woovartables_get_all_categories( $vartable_categories_exc ) .'
              </select>
            </div>
              <hr />
            ';
            
            
            // categories        
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_roles_exc">'. __('Disable for roles', 'vartable') .'</label>
              <select multiple name="vartable_roles_exc[]" id="vartable_roles_exc">
              ';
              foreach(vartable_get_editable_roles() as $role_value => $role_text) {
                echo '<option value="'. $role_value .'" '. (in_array($role_value, $vartable_roles_exc) ? 'selected="selected"' : '') .'>'. $role_text .'</option>';
              }
                echo '<option value="guest" '. (in_array('guest', $vartable_roles_exc) ? 'selected="selected"' : '') .'>'. __('Guest', 'vartable') .'</option>';
              echo '</select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_position">'. __('Table Position', 'vartable') .'</label>
              <select name="vartable_position" id="vartable_position">
                <option value="side" '. ($vartable_position == 'side' ? 'selected="selected"' : '') .'>'. __('Beside image', 'vartable') .'</option>
                <option value="under" '. ($vartable_position == 'under' ? 'selected="selected"' : '') .'>'. __('Under Image', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            
            // columns
            echo '<h4>'. __('Enable / Disable Columns', 'vartable') .'</h4>';
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_sku">'. __('Display SKU', 'vartable') .'</label>
              <select name="vartable_sku" id="vartable_sku">
                <option value="1" '. ($vartable_sku == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_sku == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
             echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_thumb">'. __('Display Thumbnail', 'vartable') .'</label>
              <select name="vartable_thumb" id="vartable_thumb">
                <option value="1" '. ($vartable_thumb == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_thumb == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_thumb_size">'. __('Thumbnail Width in Pixels', 'vartable') .'</label>
              <input type="text" name="vartable_thumb_size" id="vartable_thumb_size" value="'. $vartable_thumb_size .'">
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_stock">'. __('Display Stock', 'vartable') .'</label>
              <select name="vartable_stock" id="vartable_stock">
                <option value="1" '. ($vartable_stock == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_stock == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
              
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_in_stock_text">'. __('In Stock Text', 'vartable') .'</label>
              <input type="text" name="vartable_in_stock_text" id="vartable_in_stock_text" value="'. $vartable_in_stock_text .'">
              <small>'. __('Please use %n to display the quantity of the stock left on the above field, eg: "Just %n left"', 'vartable') .'</small>
            </div>
              <hr />
            ';
              
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_out_stock_text">'. __('Out of Stock Text', 'vartable') .'</label>
              <input type="text" name="vartable_out_stock_text" id="vartable_out_stock_text" value="'. $vartable_out_stock_text .'">
            </div>
              <hr />
            ';
              
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_low_stock_text">'. __('Low Stock Text', 'vartable') .'</label>
              <input type="text" name="vartable_low_stock_text" id="vartable_low_stock_text" value="'. $vartable_low_stock_text .'">
            </div>
              <hr />
            ';
              
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_low_stock_thresh">'. __('Low Stock Threshold', 'vartable') .'</label>
              <input type="number" name="vartable_low_stock_thresh" id="vartable_low_stock_thresh" value="'. $vartable_low_stock_thresh .'">
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_hide_zero">'. __('Hide Zero Priced Variations', 'vartable') .'</label>
              <select name="vartable_hide_zero" id="vartable_hide_zero">
                <option value="1" '. ($vartable_hide_zero == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_hide_zero == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_hide_outofstock">'. __('Hide Out of Stock Variations', 'vartable') .'</label>
              <select name="vartable_hide_outofstock" id="vartable_hide_outofstock">
                <option value="1" '. ($vartable_hide_outofstock == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_hide_outofstock == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_zero_to_out">'. __('Treat zero quantity variations as Out of Stock', 'vartable') .'</label>
              <select name="vartable_zero_to_out" id="vartable_zero_to_out">
                <option value="1" '. ($vartable_zero_to_out == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_zero_to_out == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_price">'. __('Display Price', 'vartable') .'</label>
              <select name="vartable_price" id="vartable_price">
                <option value="1" '. ($vartable_price == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_price == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_total">'. __('Display Total', 'vartable') .'</label>
              <select name="vartable_total" id="vartable_total">
                <option value="1" '. ($vartable_total == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_total == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_qty">'. __('Display Quantity Field', 'vartable') .'</label>
              <select name="vartable_qty" id="vartable_qty">
                <option value="1" '. ($vartable_qty == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_qty == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_default_qty">'. __('Default Quantity Value', 'vartable') .'</label>
              <input type="number" name="vartable_default_qty" id="vartable_default_qty" value="'. $vartable_default_qty .'" min="0">
            </div>
              <hr />
            ';
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_qty_control">'. __('Display Quantity Controls', 'vartable') .'</label>
              <select name="vartable_qty_control" id="vartable_qty_control">
                <option value="1" '. ($vartable_qty_control == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_qty_control == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_weight">'. __('Display Weight', 'vartable') .'</label>
              <select name="vartable_weight" id="vartable_weight">
                <option value="1" '. ($vartable_weight == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_weight == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_dimensions">'. __('Display Dimensions', 'vartable') .'</label>
              <select name="vartable_dimensions" id="vartable_dimensions">
                <option value="1" '. ($vartable_dimensions == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_dimensions == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_cart">'. __('Display Add To Cart', 'vartable') .'</label>
              <select name="vartable_cart" id="vartable_cart">
                <option value="1" '. ($vartable_cart == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_cart == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_globalcart">'. __('Display Global Add To Cart', 'vartable') .'</label>
              <select name="vartable_globalcart" id="vartable_globalcart">
                <option value="1" '. ($vartable_globalcart == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_globalcart == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
                <option value="2" '. ($vartable_globalcart == 2 ? 'selected="selected"' : '') .'>'. __('No, but keep the buttons (all quantities greater than 0 will be added to the cart)', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_globalcart">'. __('Global Add To Cart Status', 'vartable') .'</label>
              <select name="vartable_globalcart_status" id="vartable_globalcart_status">
                <option value="0" '. ($vartable_globalcart_status == 0 ? 'selected="selected"' : '') .'>'. __('Unchecked', 'vartable') .'</option>
                <option value="1" '. ($vartable_globalcart_status == 1 ? 'selected="selected"' : '') .'>'. __('Checked', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';

            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_globalposition">'. __('Global Add To Cart Button Position', 'vartable') .'</label>
              <select name="vartable_globalposition" id="vartable_globalposition">
                <option value="bottom" '. ($vartable_globalposition == 'bottom' ? 'selected="selected"' : '') .'>'. __('Bottom', 'vartable') .'</option>
                <option value="top" '. ($vartable_globalposition == 'top' ? 'selected="selected"' : '') .'>'. __('Top', 'vartable') .'</option>
                <option value="both" '. ($vartable_globalposition == 'both' ? 'selected="selected"' : '') .'>'. __('Both', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';

            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_wishlist">'. __('Display Wishlist', 'vartable') .'</label>
              <select name="vartable_wishlist" id="vartable_wishlist">
                <option value="1" '. ($vartable_wishlist == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_wishlist == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';

            
            if (is_plugin_active('woocommerce-product-gift-wrap/woocommerce-product-gift-wrap.php')) {
              echo '
              <div class="fieldwrap">
                <label class="vm_label" for="vartable_gift">'. __('Display Gift Wrap Option', 'vartable') .'</label>
                <select name="vartable_gift" id="vartable_gift">
                  <option value="1" '. ($vartable_gift == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                  <option value="0" '. ($vartable_gift == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
                </select>
              </div>
                <hr />
              ';
            }
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_offer">'. __('Display Image', 'vartable') .'</label>
              <select name="vartable_offer" id="vartable_offer">
                <option value="1" '. ($vartable_offer == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_offer == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
              <div class="fieldwrap">
                <label class="vm_label" for="vartable_image">'. __('Add Image', 'vartable') .'</label>';
                spyros_media_upload('vartable_image', $vartable_image);
            echo '</div>
              <hr />
            ';
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_desc">'. __('Display Description', 'vartable') .'</label>
              <select name="vartable_desc" id="vartable_desc">
                <option value="1" '. ($vartable_desc == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_desc == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_desc_inline">'. __('Display Description Inline', 'vartable') .'</label>
              <select name="vartable_desc_inline" id="vartable_desc_inline">
                <option value="1" '. ($vartable_desc_inline == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_desc_inline == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_head">'. __('Display Table Head', 'vartable') .'</label>
              <select name="vartable_head" id="vartable_head">
                <option value="1" '. ($vartable_head == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_head == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_customhead">'. __('Custom Table Head', 'vartable') .'</label>
              <textarea name="vartable_customhead" id="vartable_customhead">'. $vartable_customhead .'</textarea>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_sorting">'. __('Enable Sorting', 'vartable') .'</label> 
              <small>'. __('It will have strange results if you will also add description per variation', 'vartable') .'</small>
              <select name="vartable_sorting" id="vartable_sorting">
                <option value="0" '. ($vartable_sorting == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
                <option value="1" '. ($vartable_sorting == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_ajax">'. __('Enable AJAX', 'vartable') .'</label>
              <small>'. __('Enabling this will disable the stock quantity check when adding to cart via the plugin', 'vartable') .'</small>
              <select name="vartable_ajax" id="vartable_ajax">
                <option value="1" '. ($vartable_ajax == 1 ? 'selected="selected"' : '') .'>'. __('Yes', 'vartable') .'</option>
                <option value="0" '. ($vartable_ajax == 0 ? 'selected="selected"' : '') .'>'. __('No', 'vartable') .'</option>
              </select>
            </div>
              <hr />
            ';
            
            do_action('vartable_after_extra_options');
            
            
            $orderfields = vartable_sortable();
            $orderfields['vartable_variations'] = __('Variations', 'vartable');
            if (!empty($vartable_order)) { $orderfields = array_merge($vartable_order, $orderfields); }
            
            
            echo '
            <div class="fieldwrap">
              <label class="vm_label" for="vartable_order">'. __('Order Columns', 'vartable') .'</label>
              <small>'. __('Please drag and drop the below list elements to order the columns of the table', 'vartable') .'</small>
              <ul id="colsort">
            ';
            foreach($orderfields as $field => $fieldtext) {
              echo '<li>&#8597; <input type="hidden" name="vartable_order['. $field .']" value="'. $fieldtext .'" />'. $fieldtext .'</li>';
            }
            echo '
              </ul>
            </div>';        
            
              
        ?>
        <div class="fieldwrap">
          <p><?php submit_button(__('Save Changes')); ?></p>
        </div>
      </form>
    </div> <!-- leftpanel end -->
    
    <?php if ($hidebar !=1) { ?>
    <div class="rightpanel">
      <div class="hideright"><a href="admin.php?page=variationstable&hidebar=1" title="<?php _e('Hide this sidebar forever', 'vartable'); ?>"><?php _e('Hide this sidebar forever', 'vartable'); ?> <span>&times;</span></a></div>
      <br />
      <hr />
      <div class="clearfix clear helpwrap">
        <div class="half standout someair">
          <div>Do you like this plugin? <a href="https://codecanyon.net/item/woocommerce-variations-to-table-grid/reviews/10494620" target="_blank">Rate it!</a></div>
        </div>
        <div class="half standout someair">
          <div>Having problems? <a href="https://codecanyon.net/item/woocommerce-variations-to-table-grid/10494620/comments" target="_blank">We are here to help!</a></div>
        </div>
      </div>
      <hr />
      
      <h3><?php _e('More plugins to enhance your eshop', 'vartable'); ?></h3>
      
      <hr />
      
      <div class="plugitem">
        <h3>
          <a href="https://codecanyon.net/item/woocommerce-products-list-pro/17893660" title="Woocommerce Products List Pro" target="_blank">Woocommerce Products List Pro</a>
        </h3>
        <a href="https://codecanyon.net/item/woocommerce-products-list-pro/17893660" title="Woocommerce Products List Pro" target="_blank" class="img">
          <img src="https://image-cc.s3.envato.com/files/204577469/wcplpro-inline-preview-image.png" width="590" height="300" alt="Woocommerce Products List Pro"/>
        </a>
      </div>      
      
      <hr />
            
      <div class="plugitem">
        <h3>
          <a href="https://codecanyon.net/item/cart-to-quote-for-woocommerce/17477111" title="Cart to Quote for Woocommerce" target="_blank">Cart to Quote for Woocommerce</a>
        </h3>
        <a href="https://codecanyon.net/item/cart-to-quote-for-woocommerce/17477111" title="Cart to Quote for Woocommerce" target="_blank" class="img">
          <img src="https://0.s3.envato.com/files/204842988/woo-cart-to-quote-inline.png" width="590" height="300" alt="Cart to Quote for Woocommerce"/>
        </a>
      </div>      
      
      <hr />
      
      <div class="plugitem">
        <h3>
          <a href="http://codecanyon.net/item/woocommerce-export-products-to-xls/9307040" title="Woocommerce Export Products to XLS" target="_blank">Woocommerce Export Products to XLS</a>
        </h3>
        <a href="http://codecanyon.net/item/woocommerce-export-products-to-xls/9307040" title="Woocommerce Export Products to XLS" target="_blank" class="img">
          <img src="https://image-cc.s3.envato.com/files/130996525/wooxls-inline-preview-image.png" width="590" height="300" alt="Woocommerce Export Products to XLS"/>
        </a>
      </div>
      
      <hr />
      
      <div class="plugitem">
        <h3>
          <a href="http://codecanyon.net/item/woocommerce-lowest-price-match/12156217" title="Woocommerce Lowest Price Match" target="_blank">Woocommerce Lowest Price Match</a>
        </h3>
        <a href="http://codecanyon.net/item/woocommerce-lowest-price-match/12156217" title="Woocommerce Lowest Price Match" target="_blank" class="img">
          <img src="https://image-cc.s3.envato.com/files/184547121/wbpm-inline-preview-image.png" width="590" height="300" alt="Woocommerce Lowest Price Match"/>
        </a>
      </div>
      
            
    </div> <!-- rightpanel end -->
    <?php } ?>
  </div>
  <link href="<?php echo plugins_url('select2/select2.css', __FILE__); ?>" rel="stylesheet"/>
  <script src="<?php echo plugins_url('select2/select2.min.js', __FILE__); ?>"></script>
  <style>
      .clearfix:after {
         visibility: hidden;
         display: block;
         font-size: 0;
         content: " ";
         clear: both;
         height: 0;
         }
      .clearfix { display: inline-block; }

      * html .clearfix { height: 1%; }
      .clearfix { display: block; }
      .helpwrap, .standout { background-color: #fff; }
      .standout a { font-weight: bold; }
      .someair>div { padding: 10px; }
      .half { width: 50%; float: left; }
      .fieldwrap { width: 75%; }
      .vm_label { display: block; clear: both; margin-bottom: 7px; font-size: 18px; }
      select, input, textarea { min-width: 100%; width: 100%; }
      textarea { min-height: 400px;}
      .wooxls-updated {
        margin: 5px 0 15px;
        border-left: 4px solid #7AD03A;
        padding: 1px 12px;
        background-color: #FFF;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
      }
      .action-buttons p { display: inline; }
      .button.button-action { background: #5CB85C; border-color: #4CAE4C; color: #ffffff; font-weight: bold; -webkit-box-shadow: none; 	-moz-box-shadow: none; box-shadow: none; }
      .button.button-action:hover { background: #449D44; border-color: #398439; color: #ffffff;  }
      .button.button-danger { background: #D9534F; border-color: #D43F3A; color: #ffffff; -webkit-box-shadow: none; 	-moz-box-shadow: none; box-shadow: none; }
      .button.button-danger:hover { background: #C9302C; border-color: #AC2925; color: #ffffff; }
      li.wooxls-item:hover, li.wooxls-item:focus { background-color: rgba(255,255,255, 0.8); }
      #colsort li { cursor: move; border-bottom: 1px solid #ffffff; margin: 0; padding: 5px; }
      #colsort li:hover { background-color: #f9f9f9; }
      
      
      .leftpanel, .rightpanel { float: left; overflow: auto; position: relative; }
      .leftpanel { 
        width: 66%; 
        margin-right: 1%;
        padding-right: 1%;
        border-right: 1px solid #ddd;
      }
      .rightpanel { width: 31%; }
      .plugitem img { width: 100%; height: auto; }
      .plugitem a.img { 
        opacity: 0.3;
        transition: opacity .25s ease-in-out;
       -moz-transition: opacity .25s ease-in-out;
       -webkit-transition: opacity .25s ease-in-out;
      }
      .plugitem a.img:hover { opacity: 1; }
      
      .hideright a { color: #bb0000; text-decoration: none; }
      .hideright a:hover { color: #FF0000; }
      .hideright { font-size: 11px; position: absolute; right: 0; top: 0; cursor: pointer; line-height: 11px; }
      .hideright span { font-size: 22px; line-height: 11px; display: block; float: right; }
      
      @media only screen and (max-width: 960px) {
        .fieldwrap {
          width: 100%;
        }
        .rightpanel { 
          width: 38%;
        }
        .leftpanel { 
          width: 60%;
        }
      }
      
      @media only screen and (max-width: 768px) {
        .fieldwrap {
          width: 100%;
        }
        .leftpanel, .rightpanel { 
          float: none; 
          width: 100%;
        }
        .leftpanel {
          -webkit-box-shadow: none;
          -moz-box-shadow: none;
          box-shadow: none;
        }
      }
      
    </style>
    <script>
      jQuery(document).ready(function(){
        jQuery("select").select2({ width: '100%' });
        jQuery( "#colsort" ).sortable();
      });
    </script>
  <?php
}
?>