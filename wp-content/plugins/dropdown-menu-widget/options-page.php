<?php

global $pluginname, $pluginoptions;

$i=0;
 
//if ( @$_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$pluginname.' settings saved.</strong></p></div>';
//if ( @$_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$pluginname.' settings reset.</strong></p></div>';
 
?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<?php if ( isset($_GET['message']) && isset($messages[$_GET['message']]) ) { ?>
<div id="message" class="updated"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php } ?>
<?php if ( isset($_GET['error']) && isset($errors[$_GET['error']]) ) { ?>
<div id="message" class="error"><p><?php echo $errors[$_GET['error']]; ?></p></div>
<?php } ?>

<form id="frmShailanDm" name="frmShailanDm" method="post" action="">

<div class="widget-liquid-left">
<div id="widgets-left">


<?php foreach ($pluginoptions as $value) {
switch ( $value['type'] ) {
 
case "open":
?>
 
<?php break;
 
case "close":
?>

<div class="shailan_dm_input alignright">
<input type="hidden" name="action" value="save" />
<input name="save99" type="submit" class="button-primary menu-save" value="Save changes" />
</div>
	<br class="clear">
</div>
</div>

 
<?php break;
 
case "title":
?>
<p>To easily use the <?php echo $pluginname;?>, you can use the menu below.</p>

 
<?php break;

case 'picker':
?>
	<div id="picker"></div> 
	
<?php break;
case 'text':
?>

<div class="shailan_dm_input shailan_dm_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
 
case 'textarea':
?>

<div class="shailan_dm_input shailan_dm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
  
<?php
break;
 
case 'select':
?>

<div class="shailan_dm_input shailan_dm_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
	<!-- <pre>
	<?php print_r($value['options']); ?>
	</pre> -->
	
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $key=>$option) { ?>
		<option <?php if (get_option( $value['id'] ) == $key) { echo 'selected="selected"'; } ?> value="<?php echo $key; ?>"><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
 
case "checkbox":
?>

<div class="shailan_dm_input shailan_dm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />


	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php break; 
case "section":

$i++;

?>

<div id="available-widgets" class="widgets-holder-wrap">

<div class="sidebar-name"><h3><?php echo $value['name']; ?></h3> <span style="float:right;"></span><br class="clear" /></div>
<div class="widget-holder">

 
<?php break;
 
}
}
?>

</div>
</div>

<div class="widget-liquid-right"> 
<div id="widgets-right"> 

<div class="widgets-holder-wrap"> 
	<div class="sidebar-name">
	<div class="sidebar-name-arrow"><br /></div>
	<h3>Help
	<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_dark.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span></h3></div>

	<div id='widgets-entry-bottom' class='widgets-sortables'> 
	<div class='sidebar-description'>
		<ul>
			<li><a href="http://shailan.com/wordpress/plugins/dropdown-menu">Plugin page</a></li>
			<li><a href="http://wordpress.org/tags/dropdown-menu-widget">Support</a></li>
		</ul></div> 
	</div> 
</div> 

<div class="widgets-holder-wrap"> 
	<div class="sidebar-name">
	<div class="sidebar-name-arrow"><br /></div>
	<h3>Shailan.com
	<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_dark.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span></h3></div>

	<div id='widgets-entry-bottom' class='widgets-sortables'> 
	<div class='sidebar-description'><p class='description'>
		
		<?php
			//echo get_latest_tweet('mattsay');			
			
			$rss_options = array(
				'link' => 'http://shailan.com',
				'url' => 'http://feeds.feedburner.com/shailan',
				'title' => '',
				'items' => 3,
				'show_summary' => 0,
				'show_author' => 0,
				'show_date' => 0,
				'before' => 'text'
			);

			wp_widget_rss_output( $rss_options ); ?>
		
		
	</p></div> 
	</div> 
</div> 

<div class="widgets-holder-wrap"> 
	<div class="sidebar-name">
	<div class="sidebar-name-arrow"><br /></div>
	<h3>Support
	<span><img src="<?php echo esc_url( admin_url( 'images/wpspin_dark.gif' ) ); ?>" class="ajax-feedback" title="" alt="" /></span></h3></div>

	<div id='widgets-entry-bottom' class='widgets-sortables'> 
	<div class='sidebar-description'><p class='description'>
		
	<p>If you like this plugin you can use one of the following options to support it:</p>
	
	<ul style="padding:0px 15px; list-style:disc;">
	<li><a href="http://shailan.com/wordpress/plugins/dropdown-menu/">Commenting on plugin page</a></li>
	<li><a href="http://wordpress.org/extend/plugins/dropdown-menu-widget/">Rating it on wordpress plugin page</a></li>
	<li><a href="http://shailan.com/wordpress/plugins/dropdown-menu/" title="Permanent link to Dropdown menu widget" >Writing a post about it</a></li>
	<!-- <li><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fshailan.com%2Fwordpress%2Fplugins%2Fdropdown-menu&amp;layout=button_count&amp;show_faces=false&amp;width=200&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe></li>
	<li><script type="text/javascript">
tweetmeme_url = 'http://shailan.com/wordpress/plugins/dropdown-menu/'; tweetmeme_style = 'compact'; tweetmeme_source = 'mattsay';
</script><script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script></li> -->
	</ul>

	<p>You can also donate a few bugs using the button below: <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8F7M79S2PBU3G">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/tr_TR/i/scr/pixel.gif" width="1" height="1">
</form></p>
			
	</div> 
	</div> 
</div> 	

</div>
</div>

<br class="clear">

<input type="hidden" name="action" value="save" />
<input name="save99" type="submit" class="button-primary menu-save" value="Save changes" />
</form>

<p class="aligncenter">
<a href="http://shailan.com/wordpress/plugins/dropdown-menu">Dropdown Menu <?php echo SHAILAN_DM_VERSION; ?></a> by <a href="http://shailan.com">shailan</a> &copy; 2010
</p>


</div> <!-- wrap -->
		



		






