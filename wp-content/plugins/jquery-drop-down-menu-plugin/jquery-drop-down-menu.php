<?php
/*
Plugin Name: JQuery Drop Down Menu 
Plugin URI: http://www.phpinterviewquestion.com/jquery-dropdown-menu-plugin/
Description: A plugin to create Jquery Drop Down Menu with  fully customization.To show menu  Add <code>&lt;?php jquery_drop_down_menu('HOME') ?&gt;</code>  on your theme header.php or where you want to display menu.<strong>Configuration: <a href="options-general.php?page=jquery_drop_down_menu">Options &raquo; Jquery Drop Down Menu </a></strong>.
Author: Sana  Ullah
Version: 2.0
Author URI: http://www.phpinterviewquestion.com/

 Copyright 2009 - phpinterviewquestion.com
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
if(get_option('pluginactive')!='yes')
{
jquery_dropdown_install();
}
}

function jquery_dropdown_install() {
			
add_option('home_link', '1');
add_option('include', '1');
add_option('fadein', '100');
add_option('fadeout', '100');
add_option('fadein1', '150');
add_option('fadeout1', '150');
add_option('sort_by', 'menu_order');
add_option('sort_order', 'ASC');
add_option('depth', '0');
 update_option('home_link', 1);
  update_option('pluginactive', 'yes');
 update_option('include', 1);
 update_option('fadein', 100);
 update_option('fadeout', 100);
 update_option('sort_by', 'menu_order');
 update_option('sort_order', 'ASC');
 update_option('depth', 0);
 update_option('custom_menu', 0);
 update_option('custom_menu_value', "<li>menu1
 <ul><li><a href='#' >submenu1</a></li>
 <li><a href='#'> submenu2</a></li>
 <li><a href='#' >submenu3</a></li></ul></li>");
  update_option('custom_menu_include', "0");
 
}

function jquery_drop_down_adminpage()
   {
   add_options_page('Menu Management', 'Dropdown Menu', 'edit_plugins', "jquery_drop_down_menu",'jquery_drop_down_menu_admin');
    }


 if( isset($_POST[action]) && $_POST[action]=='jquerymenuupdate' )
 {
     $pages = $_POST[pageexclude];
     $count=1;
	 if(count($pages))
	 {
     foreach ($pages as $pagg)
	 {
	 if($count==1)	
	 {
	 $exclude=$pagg;
	
     }
	 else
	 {
	  $exclude.=",".$pagg;
	 }
	 $count++;
	 }
	 }
		  update_option('exclude_pages', $exclude);
		  update_option('home_link', $_POST['home_link']);
		  update_option('include', $_POST['include']);
		  update_option('fadein', $_POST['fadein']);
		  update_option('fadeout', $_POST['fadeout']);
		  update_option('fadein1', $_POST['fadein1']);
		  update_option('fadeout1', $_POST['fadeout1']);
		  update_option('sort_by', $_POST['sort_by']);
		  update_option('sort_order', $_POST['sort_order']);
		  update_option('depth', $_POST['depth']);
		  update_option('custom_menu', $_POST['custom_menu']);
		  update_option('custom_menu_value', $_POST['custom_menu_value']);
		  update_option('custom_menu_include', $_POST['custom_menu_include']);
  
 

 }


function jquery_drop_down_menu_admin() {


		if ( !current_user_can('edit_plugins') )
			wp_die('<p>'.__('You do not have sufficient permissions to edit templates for this blog.').'</p>');
			
			$exclude_pages = get_option('exclude_pages');
			$pagearray = explode("," , $exclude_pages);
		    $home_link = get_option('home_link');
			$custom_menu = get_option('custom_menu');
			$custom_menu_value = get_option('custom_menu_value');
			$include = get_option('include');
			$fadein = get_option('fadein');
			$fadeout = get_option('fadeout');
			$fadein1 = get_option('fadein1');
			$fadeout1 = get_option('fadeout1');
			$sort_by = get_option('sort_by');
			$sort_order = get_option('sort_order');
			$depth = get_option('depth');
			$custom_menu_include = get_option('custom_menu_include');
				
?>
<div class="wrap">
<h2><?php echo __('Drop Down Menu Options'); ?></h2>
		<script>
		function displayController(chk)
		{
		if(chk.checked)
		{
		document.getElementById('custommenuid').style.display='';
		}
		else
		{
		document.getElementById('custommenuid').style.display='none';
		}
		
		}

</script>
<form name="dropdown" method="post" action="">
    <input type="hidden" name="action" value="jquerymenuupdate" />
        <fieldset class="options">
	<h3 >Includes Below link in menu:</h3>
	<table>
		<tr>
			<td><input type="checkbox" name="home_link"  value="1"
			
			<?php if($home_link == "1"){ echo 'checked="checked"'; } ?>/> Home &nbsp;</td>
			</tr>
			<tr>
			<td> <input type="checkbox"  onclick="displayController(this)"  name="custom_menu_include"  value="1"
			
			<?php if($custom_menu_include == "1"){ echo 'checked="checked"'; } ?>/> Include custom menu  <br />
			</td>
			</tr>
			<tr id="custommenuid"  <?php if (!$custom_menu_include){ echo 'style="display: none;"'; }?>>
			<td><br /><input type="radio"  name="custom_menu"  value="1"
			
			<?php if($custom_menu == "1"){ echo 'checked="checked"'; } ?>/> Before dynamic pages menu &nbsp;  <br />
			<input type="radio"  name="custom_menu"  value="2"
			
			<?php if($custom_menu == "2"){ echo 'checked="checked"'; } ?>/> After dynamic pages menu &nbsp;<br /><textarea  name="custom_menu_value" style="font-size:10px"  cols="100" rows="10" ><?php echo stripslashes($custom_menu_value); ?></textarea></td>
			</tr>
	</table>
	
    </fieldset>
	<fieldset class="options">
	<h3 >Menu Animation Setting:</h3>
	<table>
	<tr>
			<td><input type="radio" name="include" id="donotinclude" onclick="javascript:document.getElementById('fadeinbox').style.display='none';document.getElementById('fadeoutbox').style.display='none';"  value="0" <?php if($include == "0"){ echo 'checked="checked"'; } ?>/>  Don't Include Animation &nbsp;</td></tr>
	<tr>
			<td><input type="radio" name="include" id="include"    onclick="javascript:document.getElementById('fadeinbox').style.display='';document.getElementById('fadeoutbox').style.display='';"   value="1" <?php if($include == "1"){ echo 'checked="checked"'; } ?>/> Include Animation (Fading) &nbsp;</td></tr>
			<tr><td height="10"></td></tr>
		<tr id="fadeinbox" <?php if ($include=="0" ){ echo 'style="display: none;"'; }?>> 
			<td><input type="textbox" name="fadein"  id="fadein" size="4"  value="<?php echo $fadein?>"/> On Mouse Over &nbsp;(Speed when menu will be open. Also can use <b>slow</b>  and <b>fast</b>)</td></tr>
			<tr>
			<td id="fadeoutbox"  <?php if ($include=="0" ){ echo 'style="display: none;"'; }?>><input type="textbox" name="fadeout" id="fadeout" size="4"  value="<?php echo $fadeout?>"/> On Mouse Out &nbsp;(Speed when menu will be close. Also can use <b>slow</b>  and <b>fast</b> )</td></tr>
			
				<tr><td height="20"></td></tr><tr>
			<td><input type="radio" name="include" id="include1"    onclick="javascript:document.getElementById('fadeinbox1').style.display='';document.getElementById('fadeoutbox1').style.display='';"   value="2" <?php if($include == "2"){ echo 'checked="checked"'; } ?>/> Include Animation (slideToggle)&nbsp;</td></tr>
			<tr><td height="10"></td></tr>
		<tr id="fadeinbox1" <?php if ($include=="0" ){ echo 'style="display: none;"'; }?>> 
			<td><input type="textbox" name="fadein1"  id="fadein1" size="4"  value="<?php echo $fadein1 ?>"/> On Mouse Over &nbsp;(Speed when menu will be open. Please don't write <b>slow</b>  and <b>fast</b>)</td></tr>
			<tr>
			<td id="fadeoutbox1"  <?php if ($include=="0" ){ echo 'style="display: none;"'; }?>><input type="textbox" name="fadeout1" id="fadeout1" size="4"  value="<?php echo $fadeout1?>"/> On Mouse Out &nbsp;(Speed when menu will be close. Please don't write <b>slow</b>  and <b>fast</b> )</td></tr>
	</table>
	
    </fieldset>
	
	<fieldset class="options">
	<h3 >Exclude Pages:</h3>
	<table width="98%" cellpadding="10" cellspacing="0" border="1">

	<?php $pages = get_pages(); 
			$count=1;
  foreach ($pages as $pagg) {
 if($count%5==1)
 { 
 echo "<tr>";
 }
   if
   (
 in_array("$pagg->ID", $pagearray)) {
  $checked= "checked='checked'"; }
   else
   {
   $checked= "";
   }
  
 

  	$option = '<td><input type="checkbox" '. $checked .' name="pageexclude[]" value="'.$pagg->ID.'">';
	$option .= $pagg->post_title;
	$option .= '</td><td width="10px"></td>';
	echo 	$option;
	
	if($count%5==0)
    { 
      echo "</tr><tr><td height='10px'></td></tr>";
     $count=0;
    }
 
     $count++;
	}
	?>
	
		
	
	</table>
		</fieldset>
	<fieldset class="options">
	<h3 >Page Sorting  Setting:</h3>
	<table>
	<tr>
			<td><input type="radio" name="sort_by"  value="post_title" <?php if($sort_by == "post_title"){ echo 'checked="checked"'; } ?>/> Sort Pages alphabetically. &nbsp;</td></tr>
	<tr>
			<td><input type="radio" name="sort_by" value="menu_order"  <?php if($sort_by == "menu_order" || $sort_by == " " ){ echo 'checked="checked"'; } ?>/> Sort Pages by Page Order. &nbsp;</td></tr>
			<tr> 
			<td><input type="radio" name="sort_by"  value="post_date"   <?php if($sort_by == "post_date"){ echo 'checked="checked"'; } ?>/> Sort by creation time. &nbsp;</td></tr>
			<tr>
			<td><input type="radio" name="sort_by" value="post_modified"   <?php if($sort_by == "post_modified"){ echo 'checked="checked"'; } ?>/> Sort by time last modified. &nbsp;</td></tr>
			<tr>
			<td><input type="radio" name="sort_by"  value="ID"  <?php if($sort_by == "ID"){ echo 'checked="checked"'; } ?>/> Sort Pages by Page Order. &nbsp;</td></tr>
			<tr>
			<td><input type="radio" name="sort_by"  value="post_author"  <?php if($sort_by == "post_author"){ echo 'checked="checked"'; } ?>/> Sort by the Page author's numeric ID.&nbsp;</td></tr>
			<tr>
			<td><input type="radio" name="sort_by"  value="post_name"  <?php if($sort_by == "post_name"){ echo 'checked="checked"'; } ?>/> Sort alphabetically by Post slug. </td></tr>
			<tr><td height="10"></td></tr>
		
	
	</table>
	
    </fieldset>
	
	<fieldset class="options">
	<h3 >Page Sorting  Order:</h3>
	<table>
	<tr>
			<td><input type="radio" name="sort_order"  value="ASC" <?php if($sort_order == "ASC" || !$sort_order ){ echo 'checked="checked"'; } ?>/> Sort from lowest to highest . &nbsp;</td></tr>
	<tr>
			<td><input type="radio" name="sort_order" value="DESC"  <?php if($sort_order == "DESC"){ echo 'checked="checked"'; } ?>/> Sort from highest to lowest. &nbsp;</td></tr>
			
			
			<tr><td height="10"></td></tr>
		
	
	</table>
	
    </fieldset>
	
	<fieldset class="options">
	<h3 >Depth :</h3>
	<table>
      <tr>
        <td><input type="textbox" name="depth"  id="depth" size="4"  value="<?php echo $depth;?>"/> 
             <br />             (integer) This parameter controls how many levels in the hierarchy of pages are to be included . The default value is 0 (display all pages, including all sub-pages). <br />
              <br />*  0 - Pages and sub-pages displayed in hierarchical (indented) form (Default).<br />
    * -1 - Pages in sub-pages displayed in flat (no indent) form.<br />
    * 1 - Show only top level Pages <br />
    * 2 - Value of 2 (or greater) specifies the depth (or level) to descend in displaying Pages.  . &nbsp;</td>
      </tr>
	   
      
	  
      <tr>
        <td height="10"></td>
      </tr>
    </table>
	</fieldset>
    
    <p class="submit">
	<input type="submit" name="Submit"
	    value="<?php _e('Update Options') ?> &raquo;" />
    </p>
</form>
</div>
<?php
}



function jquery_drop_down_menu_style() { 
	$gdd_wp_url = get_bloginfo('wpurl') . "/";

	echo '<link rel="stylesheet" href="'.$gdd_wp_url.'wp-content/plugins/jquery-drop-down-menu-plugin/menu_style.css" type="text/css" />';

		
$include = get_option('include');
$fadein = get_option('fadein');
$fadeout = get_option('fadeout');
$fadein1 = get_option('fadein1');
$fadeout1 = get_option('fadeout1');

if($include==1)
{
if(empty($fadein))
{
$fadein =100;
}


if(empty($fadeout))
{
$fadeout =100;
}
}
else if ($include==2)
{
if(empty($fadein1))
{
$fadein1 =100;
}
if(empty($fadeout1))
{
$fadeout1 =100;
}
}
else
{
$fadein ='fast';
$fadeout ='fast';
}
     if($include==2)
      {
		   $Jquerycode ='noCon("#dropmenu li").hover(function(){
			noCon(this).find("ul:first").slideToggle("'.$fadein1.'");
			},
			function(){
			noCon(this).find("ul:first").slideUp("'.$fadeout1.'");
			});';		
		}
		else
		{
			$Jquerycode ='noCon("#dropmenu li").hover(function(){
		noCon(this).find("ul:first").fadeIn("'.$fadein.'");
		},
		function(){
		noCon(this).find("ul:first").fadeOut("'.$fadeout.'");
		});';
		}
		
	
	echo'<script> 	

	  noCon(document).ready(function(){
		 noCon("#dropmenu ul").css({display: "none"}); 
				 // For 1 Level
	     noCon("#dropmenu li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
	     noCon("#dropmenu li ul a span").text("");
	   // For 2 Level
	     noCon("#dropmenu li ul li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
         noCon("#dropmenu li ul li ul a span").text(""); 
	   // For 3 Level
	     noCon("#dropmenu li ul li ul li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
	     noCon("#dropmenu li ul li ul li ul li a span").text("");
	  
	  // For 4 Level
	    noCon("#dropmenu li ul li ul li ul li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
	    noCon("#dropmenu li ul li ul li ul li ul li a span").text("");
		
	  // For 5 Level
	     noCon("#dropmenu li ul li ul li ul li ul li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
	     noCon("#dropmenu li ul li ul li ul li ul li ul li a span").text("");
	  
	     // For 6 Level    
	     noCon("#dropmenu li ul li ul li ul li ul li ul li:has(ul) a").append("<span>&nbsp;&raquo;</span>"); 
	     noCon("#dropmenu li ul li ul li ul li ul li ul li ul li a span").text("");
		 '.$Jquerycode.'
	 });
		</script>
' ;
	   
	
	
}

function jquery_drop_down_menu($home='Home') {
	$gdd_wp_url = get_bloginfo('wpurl') . "/";	
	$home_link = get_option('home_link');
	$include = get_option('include');
	$fadein = get_option('fadein');
	$fadeout = get_option('fadeout');
	$sort_by = get_option('sort_by');
	$sort_order = get_option('sort_order');
	$depth = get_option('depth');
	$exclude_pages = get_option('exclude_pages');
	$custom_menu = get_option('custom_menu');
	$custom_menu_value = get_option('custom_menu_value');
	$custom_menu_include = get_option('custom_menu_include');
	
	  $parameters='title_li=';
	  if($sort_by)	
	  $parameters.='&sort_column='.$sort_by.'';
	  if($sort_order)
	  $parameters.='&sort_order='.$sort_order.'';
	
	  $parameters.='&depth='.$depth.'';
	 	  if($exclude_pages)
	    {
		 $parameters.='&exclude='.$exclude_pages.'';		
		}	

	echo '<ul  id="dropmenu">';
	if($home_link)
	{
	echo '<li ><a href="'.$gdd_wp_url.'" title="'.$home.'">'.$home.'</a></li>';
	}
		   if($custom_menu==1 && $custom_menu_include==1)
			{
		   echo stripslashes($custom_menu_value);
			}
		
			wp_list_pages($parameters);	
			
			 if($custom_menu==2 && $custom_menu_include==1)
			{
		   echo stripslashes($custom_menu_value);
			}
			 
	echo '</ul>';
	

}

     if (function_exists('add_action')) {
	 	add_action('wp_head', 'jquery_drop_down_menu_style'); 
		add_action('admin_menu', 'jquery_drop_down_adminpage');
	}
	add_action('wp_print_scripts', 'down_menu_plugin_scripts');
function down_menu_plugin_scripts() {
	if(!is_admin())
	wp_enqueue_script('down-menu-plugin', $src = WP_CONTENT_URL.'/plugins/jquery-drop-down-menu-plugin/noConflict.js', $deps = array('jquery'));
}
?>