jQuery(document).ready(function($) {

  var ajaxurl             = localvars.ajax_url;
  var carturl             = localvars.cart_url;
  var currency_symbol     = localvars.currency_symbol;
  var thousand_separator  = localvars.thousand_separator;
  var decimal_separator   = localvars.decimal_separator;
  var decimal_decimals    = localvars.decimal_decimals;
  var currency_pos        = localvars.currency_pos;
  var price_display_suffix= localvars.price_display_suffix;
  var gclicked            = 0;
  var glob_clicked        = 0;
  var vartable_ajax       = localvars.vartable_ajax;
  var $fragment_refresh   = '';
  var count               = 0;
  var numofadded          = 0;
  var requests_done       = 0;
  
  var formdata            = new Array;
  
  /** Cart Handling */
  $supports_html5_storage = ( 'sessionStorage' in window && window['sessionStorage'] !== null );
  if (vartable_ajax == 1) {
    $fragment_refresh = {
        url: ajaxurl,
        // url: woocommerce_params.ajax_url,
        type: 'POST',
        data: { action: 'woocommerce_get_refreshed_fragments' },
        success: function( data ) {
            if ( data && data.fragments ) {

                $.each( data.fragments, function( key, value ) {
                    $(key).replaceWith(value);
                });

                if ( $supports_html5_storage ) {
                    sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
                    sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
                }
                console.log('refresh');
                $('body').trigger( 'wc_fragments_refreshed' );
            }
        }
    };
  }

  function vartable_init() {
    if (jQuery("table.vartable").length > 0) {
      jQuery("table.vartable").each(function(index) {
        
        var vartable            = jQuery(this);
        var random_id           = jQuery(this).data('random');
        var vartable_ajax       = jQuery(this).data('vartable_ajax');
        var cartredirect        = jQuery(this).data('cartredirect');
        var sorting             = jQuery(this).data('sort');
        var vartable_globalcart = jQuery(this).data('globalcart');
        var preorder            = jQuery(this).data('preorder');
        var preorder_direction  = jQuery(this).data('preorder_direction');
                
        
        update_global_sum(jQuery(this).find('input[name="var_quantity"]'));
        
        // gift wrap
        if (vartable.find("input.var_gift_wrap").length > 0) {
          jQuery(document).on("change", vartable.find("input.var_gift_wrap"), function() {
            if(jQuery(this).is(":checked")) {
              jQuery(this).closest('tr').find(".cartcol input.gift_wrap").val("yes");
            } else {
              jQuery(this).closest('tr').find(".cartcol input.gift_wrap").attr("value", "");
            }
          });
        }
        
        
        if (vartable.find("div.qtywrap").length > 0) {
          
          
          
          
          jQuery("#tb_"+ random_id +" div.qtywrap").each(function() {
            var qtythis = jQuery(this);
            qtythis.find(".minusqty").on("click", function() {
              var valnum = parseInt(qtythis.find("input").val());
              var valmin = qtythis.find("input").attr('min');
              
              if( typeof valmin === 'undefined' || valmin === null ){
                valmin = 0;
              }
              
              if (qtythis.find("input").attr("step") && qtythis.find("input").attr("step") > 0) {
                var step = parseInt(qtythis.find("input").attr("step"));
              } else {
                var step = 1;
              }
              
              if (valnum - step >= valmin) {
                qtythis.find("input").val(valnum - step);
                qtythis.closest('tr').find(".cartcol input.hidden_quantity").val(valnum - step);
                qtythis.closest('tr').find(".totalcol").text(get_price_html((valnum - step) * qtythis.closest('tr').data('price')));
                qtythis.find("input").trigger( "qty:change" );
              }
            });
            qtythis.find(".plusqty").on("click", function() {
              var valnum = parseInt(qtythis.find("input").val());
              var valmax = qtythis.find("input").attr('max');
              
              if( typeof valmax === 'undefined' || valmax === null ){
                valmax = -1;
              }
              
              if (qtythis.find("input").attr("step") && qtythis.find("input").attr("step") > 0) {
                var step = parseInt(qtythis.find("input").attr("step"));
              } else {
                var step = 1;
              }
              if ((valnum + step <= valmax) || valmax == -1) {
                qtythis.find("input").val(valnum + step);
                qtythis.closest('tr').find(".cartcol input.hidden_quantity").val(valnum + step);
                qtythis.closest('tr').find(".totalcol").text(get_price_html((valnum + step) * qtythis.closest('tr').data('price')));
                qtythis.find("input").trigger( "qty:change" );
              }
            });
          });
        }
        
        
        if (sorting == 'yes') {

          var $table = jQuery("#tb_"+ random_id +"").stupidtable();
          if (preorder != '' && preorder != 'custom') {
            var $th_to_sort = $table.find("thead th."+preorder);
            $th_to_sort.stupidsort();
          }
          if ($th_to_sort !== undefined && $th_to_sort !== null) {
            if (preorder_direction != '' && preorder_direction != 'custom') {
              $th_to_sort.stupidsort(preorder_direction);
            } else {
              $th_to_sort.stupidsort('asc');
            }
          }
          
          $table.bind('aftertablesort', function (event, data) {
              // data.column - the index of the column sorted after a click
              // data.direction - the sorting direction (either asc or desc)
              // $(this) - this table object

              // console.log("The sorting direction: " + data.direction);
              // console.log("The column index: " + data.column);
          });
          
          
        }

        
        
        
        jQuery(document).on("click", "a#gc_"+ random_id +"_top",function(event) {
          gclicked = 1;
        });
        jQuery(document).on("click", "a#gc_"+ random_id +"_bottom",function(event) {
          gclicked = 1;
        });
      });
      
      jQuery(document).on("submit", "form.vtajaxform", function(event) {

        event.preventDefault();
      
        console.log("triggered");
        
        if (jQuery(this).find(".hidden_quantity").val() > 0) {
          
          // reset the formdata array
          formdata = [];
          formdata.length = 0;
          $formdata = get_form_data(jQuery(this));
          
          
          if (jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).length > 0){
            jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).attr('disabled', 'disabled');
            jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).addClass('working');
            jQuery(".vtspinner_"+ formdata['thisbuttonid'][1]).fadeIn(200);
          }
          
          
          vartable_request(formdata);
          
          
        }
        
        return false;
        
      });
      
      if (jQuery(".vartable_gc_wrap_bottom .globalcartbtn.submit, .vartable_gc_wrap_top .globalcartbtn.submit").length > 0) {
        jQuery(document).on("click", ".vartable_gc_wrap_bottom .globalcartbtn.submit:not(.working), .vartable_gc_wrap_top .globalcartbtn.submit:not(.working)",function(event) {
          

          event.preventDefault();
          glob_clicked = 1;
          gclicked = 1;
          numofadded = 0;
          requests_done = 0;
          
          if (jQuery(this).hasClass('working')) { return false; }
          
          var clickthis = jQuery(this);
          var pid = clickthis.attr("id").split("_");
          
          var vartable            = jQuery('#tb_'+pid[1]);
          var random_id           = vartable.data('random');
          var vartable_ajax       = vartable.data('vartable_ajax');
          var cartredirect        = vartable.data('cartredirect');
          var sorting             = vartable.data('sort');
          var vartable_globalcart = vartable.data('globalcart');
          var preorder            = vartable.data('preorder');
          var preorder_direction  = vartable.data('preorder_direction');
          var position            = jQuery(this).data('position');
          
          clickthis.addClass('working').attr('disabled', 'disabled');
          
          jQuery(".vtspinner_"+ position +".vtspinner_"+ pid[1]).stop(true).fadeIn(100, function() {
                        
            count = 0;
            jQuery("table#tb_"+ random_id +" tbody td.qtycol").not(".descrow").each(function(index) {
              if(jQuery(this).find("input.qty").val() > 0 && jQuery(this).find("input.hidden_price").val() != '0.00') {
                
                count = count +1;
              }
            });
            
            if (count == 0) {
              clickthis.removeClass('working').removeAttr('disabled');
              jQuery(".vtspinner_"+ position +".vtspinner_"+ pid[1]).stop(true).fadeOut(100);
              return false;
            }
            
            
            var trig = 0;
            
            var promises=[];
            
            
            jQuery("table#tb_"+ random_id +" tbody td.qtycol").not(".descrow").each(function(index) {
              if(jQuery(this).find("input.qty").val() > 0 && jQuery(this).find("input.hidden_price").val() != '0.00') {
                // reset the formdata array
                formdata = [];
                formdata.length = 0;
                
                //var formobj = jQuery(this).find("form.vtajaxform");
				var formobj = jQuery(this);
                formdata = get_form_data(formobj);
                
                vartable_request(formdata);
                
                requests_done = requests_done + 1
                               
                
                // promises.push(request);
                numofadded = numofadded + parseInt(jQuery(this).find("input.hidden_quantity").val());

                trig = 1;                    
              }
              
            });
            
            
            jQuery(document).on('vartable_global_add_finished', function() {
              // jQuery.when.apply(null, promises).done(function(){
                
                
                if (vartable_ajax != 1 || cartredirect == 'yes') {
                  if (count <= 0 && trig == 1) {
                    if (cartredirect == 'yes') {
                      window.location.href = ""+carturl+"";
                    }
                    if (cartredirect == 'no') {
                      location.reload();
                    }
                  }
                }
                
                if (count <= 0) {
                  jQuery(".vtspinner_"+ pid[1]).stop(true, true).fadeOut(100);
                  if (trig == 1)  {
                    jQuery(".added2cartglobal_"+ pid[1]).stop(true).fadeIn(200); 
                    jQuery(".added2cartglobal_"+ pid[1]).delay(3000).fadeOut(200); 
                  }
                  
                  if (numofadded > 0) {
                    jQuery('#vt_added_to_cart_notification span').text(numofadded);
                    jQuery('#vt_added_to_cart_notification').stop(true).slideDown(100, function() {
                      jQuery('#vt_added_to_cart_notification').delay(6000).slideUp(200);
                      glob_clicked = 0;
                    });
                  }
                  
                  glob_clicked = 0;
                  gclicked = 0;
                  clickthis.removeClass('working').removeAttr('disabled');

                   // alert('All done');
                   console.log('finished');
                }
                 
              // });
            });

            
          });
          
          


        });
      }
      
      
    }
  }
  
  vartable_init();
  
  jQuery(document).on("input", ".vartable .qtycol input.input-text.qty",function() {
    
    var valmin  =jQuery(this).attr('min');
    var valmax  =jQuery(this).attr('max');
    
    
    if( typeof valmin === 'undefined' || valmin === null ){
      valmin = 0;
    }
    if( typeof valmax === 'undefined' || valmax === null ){
      valmax = -1;
    }
    
    if (parseInt(jQuery(this).val()) < valmin) {
      jQuery(this).val(valmin);
    }    
    if (parseInt(jQuery(this).val()) > valmax && valmax != -1) {
      jQuery(this).val(valmax);
    }
    
    jQuery(this).closest('tr').find(".totalcol").text(get_price_html(jQuery(this).val() * jQuery(this).closest('tr').data('price')));
    jQuery(this).closest('tr').find(".cartcol input.hidden_quantity").val(jQuery(this).val());
    jQuery(this).trigger( "qty:change" );
  });
  
  if (jQuery('.giftcol').length > 0) {
    jQuery(document).on("change", "input.var_gift_wrap",function() {
      if(jQuery(this).is(":checked")) {
        jQuery(this).closest('tr').find(".cartcol input.gift_wrap").val("yes");
      } else {
        jQuery(this).closest('tr').find(".cartcol input.gift_wrap").attr("value", "");
      }
    });
  }
  
  
  jQuery(document).on('qty:change', 'input[name="var_quantity"]', function() {
    update_global_sum(jQuery(this));
  });
  jQuery(document).on('change', 'input.globalcheck',function() {
    update_global_sum(jQuery(this));
  });
  
  // update global cart button count on page load
  jQuery(window).load(function() {
    if (jQuery('table.vartable').length > 0) {
      jQuery('table.vartable').each(function() {
        update_global_sum(jQuery(this));
      });
    }
    
  });
  
  function update_global_sum(object) {
    var random_id = object.closest('table.vartable').data('random');
    var numofchecked = 0;
    jQuery("table#tb_"+random_id+" tbody td.qtycol input.qty").each(function(index) {
      
      //jQuery(this).find('tr').each(function(row) {
        
        //if (jQuery(this).find('.globalcheck').is(":checked") && jQuery(this).data('price') != '' && parseInt(jQuery(this).find('input[name="quantity"]').val()) > 0) {
          if(parseInt(jQuery(this).val()) > 0){
			numofchecked = numofchecked + parseInt(jQuery(this).val());
		  }
        //}
        
      //});
      
    });
    
    jQuery('#gc_'+ random_id +'_top span.vt_products_count, #gc_'+ random_id +'_bottom span.vt_products_count').text(' ('+ numofchecked +')');
    
  }
  
  jQuery(document).on("change", ".vartable_selectall_check",function(event) {

    var said = jQuery(this).attr("id").split("_");

    if(this.checked) {
      jQuery("table#tb_"+ said[1] +" tr").each(function(index) {
        
        if(jQuery(this).find("input.globalcheck").length > 0) {
        
          jQuery(this).find("input.globalcheck").attr("checked", "checked");
        }          
        
      });
    } else {
      jQuery("table#tb_"+ said[1] +" tr").each(function(index) {
      
        if(jQuery(this).find("input.globalcheck").length > 0) {
          jQuery(this).find("input.globalcheck").removeAttr("checked");
        }
        
      });
    }
    
    update_global_sum(jQuery(this));
  });
  
  Number.prototype.formatMoney = function(c, d, t){
  var n = this, 
      c = isNaN(c = Math.abs(c)) ? 2 : c, 
      d = d == undefined ? "." : d, 
      t = t == undefined ? "," : t, 
      s = n < 0 ? "-" : "", 
      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
      j = (j = i.length) > 3 ? j % 3 : 0;
     return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   };
     
  function get_price_html(price) {
    price = parseFloat(price).formatMoney(decimal_decimals,decimal_separator,thousand_separator);
    
    
    
    if (currency_pos == 'left') {
      price = currency_symbol + price;
    }
    if (currency_pos == 'right') {
      price = price + currency_symbol;
    }
    if (currency_pos == 'left_space') {
      price = currency_symbol +' '+price;
    }
    if (currency_pos == 'right_space') {
      price = price + ' ' + currency_symbol;
    }
    
    if (price_display_suffix != '') {
      price = price +' '+ price_display_suffix;
    }
    
    return price;
  }
  
  
  function get_form_data(formobj){
    formdata['variation_id']    = formobj.find('input.hidden_variation').val();
    formdata['product_id']      = formobj.find('input.hidden_product').val();
    formdata['quantity']        = formobj.find('input.qty').val();
    //formdata['gift_wrap']       = formobj.find('input[name="gift_wrap"]').val();
    //formdata['variations_json'] = formobj.find('input[name="form_vartable_attribute_json"]').val();
    //formdata['variations']      = formobj.find('input[name="form_vartable_attribute_array"]').val();
    
    //formdata['vartable_ajax']       = formobj.closest('table.vartable').data('vartable_ajax');
    //formdata['vartable_globalcart'] = formobj.closest('table.vartable').data('globalcart');
    //formdata['cartredirect']        = formobj.closest('table.vartable').data('cartredirect');
    
    //formdata['addvtdata']     = formobj.serialize();
    //formdata['thisid']        = formobj.attr("data-variation_id");
    //formdata['thisbutton']    = formobj.find('button.add_to_cart');
    //formdata['thisbuttonid']  = formdata['thisbutton'].attr('id').split('_');
    
    // console.log(formdata);
    
    return formdata;
    
  }
  
  function vartable_request(formdata){
    
    jQuery.ajaxQueue({
      type:"POST",
      url: ajaxurl,
      data: {
        "action" : "add_variation_to_cart",
        "product_id" : formdata['product_id'],
        "variation_id" : formdata['variation_id'],
        "variations" : formdata['variations'],
        "quantity" : formdata['quantity'],
        "gift_wrap" : formdata['gift_wrap']
      },
      success:function(data){
        
        
        //conditionally perform fragment refresh
        if (formdata['vartable_ajax'] == 1) {
          $.ajax( $fragment_refresh );
        }
        if (glob_clicked == 0) {
          jQuery('#vt_added_to_cart_notification span').text(formdata['quantity']);
          jQuery('#vt_added_to_cart_notification').stop(true, true).slideDown(200, function() {
            jQuery('#vt_added_to_cart_notification').delay(3000).slideUp(200);
          });
        }
        
        // set counter to track when all requests are done
        count = count - 1;
        
        console.log(count);
        if (count <= 0) {
            if (glob_clicked == 1 || gclicked == 1) {
              jQuery('body').trigger( 'vartable_global_add_finished' );
              glob_clicked = 0;
              gclicked = 0;
            }
          }
		/*
        jQuery(".vtspinner_"+ formdata['thisbuttonid'][1]).fadeOut(200, function() {

          if (count <= 0) {
            if (glob_clicked == 1 || gclicked == 1) {
              jQuery('body').trigger( 'vartable_global_add_finished' );
              glob_clicked = 0;
              gclicked = 0;
            }
          }
          
        });

        jQuery("#added2cart_"+ formdata['thisbuttonid'][1]).fadeIn(200, function() {
          jQuery("#added2cart_"+ formdata['thisbuttonid'][1]).delay(1000).fadeOut(1000);
          
          if (jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).length > 0){
            jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).removeAttr('disabled');
            jQuery('#add2cartbtn_'+formdata['thisbuttonid'][1]).removeClass('working');
          }
          
          
          if ((formdata['vartable_ajax'] != 1 ) || formdata['cartredirect'] == 'yes') {
            if (gclicked == 0) {
              if (formdata['cartredirect'] == 'yes') {
                window.location.href = ""+carturl+"";
              }
              if (formdata['cartredirect'] == 'no') {
                location.reload();
              }
            }
          }
          
        });*/
      },
      error: function(data) {
        console.log(data);
      }
    });
    
  }
  
  
  // reload for yith quick view
  jQuery(document).on('qv_loader_stop', function(){ 
    vartable_init();
  });
  
  (function($) {

    var ajaxQueue = $({});

    $.ajaxQueue = function(ajaxOpts) {

      var oldComplete = ajaxOpts.complete;

      ajaxQueue.queue(function(next) {

        ajaxOpts.complete = function() {
          if (oldComplete) oldComplete.apply(this, arguments);

          next();
        };

        $.ajax(ajaxOpts);
      });
    };

  })(jQuery);
  
  
});