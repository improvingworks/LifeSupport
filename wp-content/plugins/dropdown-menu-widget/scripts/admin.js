/*var farbtastic; */

function pickColor(obj, color) {
	farbtastic.setColor(color);
	jQuery("#" + obj).val(color);
}

jQuery.fn.enable = function(){
	return jQuery(this).removeAttr('disabled');
}

jQuery.fn.disable = function(){
	return jQuery(this).attr('disabled', 'disabled');
}

jQuery(document).ready(function() {

	function shailan_dm_active_theme_change(){
		if(jQuery('#shailan_dm_active_theme').val() == '*url*'){
			jQuery('#shailan_dm_theme_url').enable();
		} else {
			jQuery('#shailan_dm_theme_url').disable();
		}
	}
	
	jQuery('#shailan_dm_active_theme').change(function(){ shailan_dm_active_theme_change() });
	shailan_dm_active_theme_change();
	
	function shailan_dm_effects_change(){
		if( jQuery('#shailan_dm_effects').attr('checked') == true || jQuery('#shailan_dm_effects').attr('checked') == 'checked' ){
			jQuery('#shailan_dm_effect').enable();
			jQuery('#shailan_dm_effect_speed').enable();
		} else {
			jQuery('#shailan_dm_effect').disable();
			jQuery('#shailan_dm_effect_speed').disable();
		}
	}
	
	jQuery('#shailan_dm_effects').change(function(){ shailan_dm_effects_change() });
	shailan_dm_effects_change();
	
	var f = jQuery.farbtastic('#picker');
	var p = jQuery('#picker').fadeOut();
	var selected;

	// Color selector areas:
	var pickers = ["shailan_dm_color_lihover", "shailan_dm_color_menubg", "shailan_dm_color_link", "shailan_dm_color_hoverlink"];

	jQuery.each(pickers, function() {		
	
		f.linkTo(this);
		
		jQuery("#" + this).css('background-color', jQuery("#" + this).val());
	
		jQuery("#" + this).focus(function(){
			if (selected) {
				jQuery(selected).removeClass('selected');
			}
			f.linkTo(this);
			p.fadeIn(2);
			jQuery(selected = this).addClass('selected');
			//jQuery('#picker').show();
		});
		
		jQuery("#" + this).keyup(function() {
			f.linkTo(this);
			p.fadeIn(2);
			var _hex = jQuery(this).val(), hex = _hex;
			if ( hex[0] != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				jQuery(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 ){
				jQuery(this).removeClass('color-error');
				pickColor( this, hex );
			} else {
				jQuery(this).addClass('color-error');
			}
		});
		
	});

	jQuery(document).mousedown(function(){
		jQuery('#picker').each(function(){
			var display = jQuery(this).css('display');
			if ( display == 'block' )
				jQuery(this).fadeOut(10);
		});
	});
	
	
	
	
});