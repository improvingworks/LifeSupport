/*var farbtastic; */

function pickColor(obj, color) {
	farbtastic.setColor(color);
	jQuery("#" + obj).val(color);
}

jQuery(document).ready(function() {

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