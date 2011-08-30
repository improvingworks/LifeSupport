<div class="wrap stf_options_page">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( $title ); ?></h2>
	
<!-- Notifications -->
<?php if ( isset($_GET['message']) && isset($messages[$_GET['message']]) ) { ?>
<div id="message" class="updated fade"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php } ?>
<?php if ( isset($_GET['error']) && isset($errors[$_GET['error']]) ) { ?>
<div id="message" class="error fade"><p><?php echo $errors[$_GET['error']]; ?></p></div>
<?php } ?>
<!-- [End] Notifications -->

	
<div id="shailancom">

	<div id="donate">
	This plugin is FREE, </br> If you like it, please support it. <br />
		<div class="center">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="hidden" name="hosted_button_id" value="2UF8Y5ZSHAGZL" />
			<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
			<img alt="" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/scr/pixel.gif" width="1" height="1" />
			</form>
		</div>
	</div>

	<div id="stf_tweet_wrap">
	<div id="stf_tweet"></div><script type="text/javascript"> 
	jQuery(document).ready(function($) {
		$('#stf_tweet').tweetable({username: 'shailancom', time: false, limit: 1, replies: false});
	});
	</script>
	
	<a href="http://twitter.com/shailancom" class="follow-link">Follow Shailan.com on twitter!</a>
	</div>

	<div id="headlines">
	<h3>Latest headlines from Shailan.com</h3>
		<?php
				//echo get_latest_tweet('mattsay');			
				
				$rss_options = array(
					'link' => 'http://shailan.com',
					'url' => 'http://feeds.feedburner.com/shailan',
					'title' => 'Shailan.com',
					'items' => 5,
					'show_summary' => 0,
					'show_author' => 0,
					'show_date' => 0,
					'before' => 'text'
				);

				wp_widget_rss_output( $rss_options ); ?>
	</div>
</div>

<div id="nav"><?php if(!empty($navigation)){echo $navigation;} ?></div>

<div class="stf_opts_wrap">
<div class="stf_options">
<form method="post">
<div id="options-tabs">

	<!-- Tabs navigation -->
	<ul id="tabs-navigation" class="tabs">
	<?php
		foreach ($options as $field) {
			if ( $field['type'] == "section" ) {
				echo "<li><a href=\"#" . sanitize_title( $field['name'] ) . "\" class=\"" . sanitize_title( $field['name'] ) . "\">".$field['label']."</a></li>";
			}
		}
	?>
	</ul>
	<!-- [End] Tabs Navigation -->

<div class="tab_container">
<?php foreach ($options as $field) {
switch ( $field['type'] ) {
 
	case 'open': ?>
 
<?php break;
	
	case 'close': ?>

</div>
 
<?php break;

	case 'paragraph': ?>

<div class="stf_paragraph clearfix">
	<?php echo $field['desc']; ?>
</div>

<?php
break;
	
	case 'text': ?>

<div class="stf_input stf_text clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
 	<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" value="<?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] != "") { echo esc_html(stripslashes($current[ $field['id'] ] ) ); } ?>" />
	<small><?php echo $field['desc']; ?></small>
</div>

<?php
break;
 
case 'textarea':
?>

<div class="stf_input stf_textarea clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
 	<textarea name="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" cols="" rows=""><?php if ( $current[ $field['id'] ] != "") { echo stripslashes($current[ $field['id'] ] ); } else { echo $field['std']; } ?></textarea>
 <small><?php echo $field['desc']; ?></small>
 
 </div>
  
<?php
break;

case 'htmlarea':
?>

<div class="stf_input stf_textarea clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
	
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#<?php echo $field['id']; ?>').wysiwyg();
});
</script>
	
 	<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" cols="" rows=""><?php if ( $current[ $field['id'] ] != "") { echo stripslashes($current[ $field['id'] ] ); } else { echo $field['std']; } ?></textarea>
 <small><?php echo $field['desc']; ?></small>
 
</div>
  
<?php
break;
 
case 'select':
?>

<div class="stf_input stf_select clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
	
<select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
<?php foreach ($field['options'] as $key=>$name) { ?>
		<option <?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] == $key) { echo 'selected="selected"'; } ?> value="<?php echo $key;?>"><?php echo $name; ?></option><?php } ?>
</select>

	<small><?php echo $field['desc']; ?></small>
</div>
<?php
break;
 
case "checkbox":
?>

<div class="stf_input stf_checkbox clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
	
	<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="on" <?php checked($current[ $field['id'] ], "on") ?> />

	<small><?php echo $field['desc']; ?></small>
 </div>
<?php break; 
case "section":

?>

<div class="stf_section tab_content" id="<?php echo sanitize_title( $field['name'] ); ?>">
 
<?php break;

case "splitter":
?>
<div class="shailan_dm_input shailan_dm_splitter"></div> 
<?php break;

case 'picker':
?>
	<div id="picker"></div> 
	
<?php break;
 
}
}
?>

<div id="tabs-footer" class="clearfix">
	<p class="submit">
		<input name="save" type="submit" class="button-primary" value="Save changes" />
		<input type="hidden" name="action" value="save" />
	</p>
	</form>
	
	<form method="post">
		<input name="reset" type="submit" class="button-secondary" value="Reset Options" />
		<input type="hidden" name="action" value="reset" />
	</form>


	<div class="copyright"><?php if(!empty($footer_text)){echo $footer_text;} ?></div>
</div>
</div>
</div>
</div>




<script type="text/javascript">
jQuery(document).ready(function($) {

	//When page loads...
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:first").show(); //Show first tab content

	//On Click Event
	jQuery("ul.tabs li").click(function() {

		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".tab_content").hide(); //Hide all tab content

		var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
</script>
</div> 