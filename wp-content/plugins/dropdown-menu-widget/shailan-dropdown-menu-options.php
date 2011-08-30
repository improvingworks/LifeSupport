<?php 

// Define themes
$available_themes = array(
	'None' => '*none*',
	'From URL' => '*url*',
	'Custom CSS' => '*custom*',
	'Color Scheme' => 'color-scheme',
	'Web 2.0' => plugins_url('/themes/web20.css', __FILE__),
	'Simple White' => plugins_url('/themes/simple.css', __FILE__),
	'Wordpress Default' => plugins_url('/themes/wpdefault.css', __FILE__),
	'Grayscale' => plugins_url('/themes/grayscale.css', __FILE__),
	'Aqua' => plugins_url('/themes/aqua.css', __FILE__),
	'Blue gradient' => plugins_url('/themes/simple-blue.css', __FILE__),
	'Shiny Black' => plugins_url('/themes/shiny-black.css', __FILE__),
	'Flickr theme' =>  plugins_url('/themes/flickr.com/default.ultimate.css', __FILE__),
	'Nvidia theme' =>  plugins_url('/themes/nvidia.com/default.advanced.css', __FILE__),
	'Adobe theme' => plugins_url('/themes/adobe.com/default.advanced.css', __FILE__),
	'MTV theme' =>  plugins_url('/themes/mtv.com/default.ultimate.css', __FILE__),
	'Hulu theme' =>  plugins_url('/themes/hulu/hulu.css', __FILE__)
);

// Check for theme style file
if( file_exists( trailingslashit( get_stylesheet_directory() ) . 'dropdown.css') ){
	$available_themes['Dropdown.css (theme)'] = get_stylesheet_directory_uri() . '/dropdown.css';
}

if( file_exists( trailingslashit( get_template_directory() ) . 'dropdown.css') ){
	$available_themes['Dropdown.css (template)'] = get_template_directory_uri() . '/dropdown.css';
}

// Swap array for options page
$themes = array();
while(list($Key,$Val) = each($available_themes))
	$themes[$Val] = $Key;

$overlays = array(
	'none'=>'none',
	'glassy'=>'glassy',
	'flat'=>'flat',
	'shadow'=>'shadow',
	'soft' =>'soft'
);

$alignment = array('left'=>'left', 'center' => 'center', 'right'=> 'right');
$types = array('pages'=>'Pages', 'categories'=>'Categories');
$effects = array('fade'=>'Fade In/Out', 'slide'=>'Slide Up/Down');
$speed = array('400'=>'Normal', 'fast'=>'Fast', 'slow'=>'Slow');
$delay = array('100'=>'100', '200'=>'200', '300'=>'300');

if( function_exists('wp_nav_menu') ){
	// Get available menus
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	$navmenus = array();
	
	if($menus){
		foreach( $menus as $menu ){
			$navmenus[ 'navmenu_' . $menu->term_id ] = $menu->name;
		}
	}
	
	// Merge type with menu array
	$types = array_merge($types, $navmenus);
}

$this->menu_types = $types; // Back it up

// Define plugin options	
$options = array(
	
array(
	"name" => "General",
	"label" => __("General"),
	"type" => "section"
),

	array(  "name" => "Dropdown Menu Theme",
	"desc" => "Skin for the menu",
	"id" => "shailan_dm_active_theme",
	"std" => "None",
	"options" => $themes,
	"type" => "select"),
	
	array(  "name" => "Theme URL",
	"desc" => "If From URL is selected you can specify theme URL here. <br />Warning : Please make sure this file only modifies dropdown theme. Otherwise your theme may be affected by this css file.",
	"id" => "shailan_dm_theme_url",
	"std" => "http://",
	"type" => "text"),
	
	array(  "name" => "Rename Homepage",
	"desc" => "You can change your homepage link here",
	"id" => "shailan_dm_home_tag",
	"std" => __("Home"),
	"type" => "text"),
	
	array(  "name" => "Wrap long menu items",
	"desc" => "If checked long menu items will wrap",
	"id" => "shailan_dm_allowmultiline",
	"type" => "checkbox"),
	
	array(  "name" => "Remove title attributes from menu items",
	"desc" => "This will remove 'View all posts under..' title attributes from menu links",
	"id" => "shailan_dm_remove_title_attributes",
	"type" => "checkbox"),
	
	array(  "name" => "Remove links from top levels",
	"desc" => "This will remove links from top level pages/categories. So user can only click to sub-level menu.",
	"id" => "shailan_dm_remove_top_level_links",
	"type" => "checkbox"),
	
array( "type" => "close" ),
	
array(
	"name" => "Effects",
	"label" => __("Effects"),
	"type" => "section"
),
	
	array(  "name" => "Enable dropdown effects",
	"desc" => "If checked sub menus will use effects below",
	"id" => "shailan_dm_effects",
	"type" => "checkbox"),
	
	array(  "name" => "Effect",
	"desc" => "Select effect you want to use",
	"id" => "shailan_dm_effect",
	"type" => "select",
	"options" => $effects ),
	
	array(  "name" => "Effect Speed",
	"desc" => "Select effect speed",
	"id" => "shailan_dm_effect_speed",
	"type" => "select",
	"options" => $speed ),
	
	array(  "name" => "Effect delay",
	"desc" => "Select effect delay",
	"id" => "shailan_dm_effect_delay",
	"type" => "select",
	"options" => $delay ),
	
array( "type" => "close" ),

array(
	"name" => "Colors",
	"label" => __("Colors"),
	"type" => "section"
),
	
	array(  "name" => "Use custom colors",
	"desc" => "If not checked custom colors won't work.",
	"id" => "shailan_dm_custom_colors",
	"std" => true,
	"type" => "checkbox"),
	
	array("type"=>"picker"),
	
	array(  "name" => "Menu Background Color",
	"desc" => "Background color of the dropdown menu",
	"id" => "shailan_dm_color_menubg",
	"std" => '#000000',
	"type" => "text"),
	
	array(  "name" => "Hover Background Color",
	"desc" => "Background color of list item link.",
	"id" => "shailan_dm_color_lihover",
	"std" => '#333333',
	"type" => "text"),
	
	array(  "name" => "Link Text Color",
	"desc" => "Default link color",
	"id" => "shailan_dm_color_link",
	"std" => '#FFFFFF',
	"type" => "text"),
	
	array(  "name" => "Link Text Color on mouse over",
	"desc" => "Secondary link color",
	"id" => "shailan_dm_color_hoverlink",
	"std" => '#FFFFFF',
	"type" => "text"),
	
	array(  "name" => "Overlay",
	"desc" => "Menu overlay (Works on browsers that support png transparency only.)",
	"id" => "shailan_dm_overlay",
	"std" => "glass",
	"type" => "select",
	"options" => $overlays ),
	
	array( "type" => "close" ),
	
	array(
		"name" => "Advanced",
		"label" => __("Advanced"),
		"type" => "section"
	),
	
	array(  "name" => "Dropdown Menu Font",
	"desc" => "Font family for the menu<br />Please leave blank to use your wordpress theme font.",
	"id" => "shailan_dm_font",
	"std" => '',
	"type" => "text"),
	
	array(  "name" => "Dropdown Menu Font Size",
	"desc" => "Font size of the menu items (Eg: 12px OR 1em) <br />Please leave blank to use your wordpress theme font-size.",
	"id" => "shailan_dm_fontsize",
	"std" => '',
	"type" => "text"),
	
	array(  "name" => "Custom css",
	"desc" => "You can paste your own customization file here.",
	"id" => "shailan_dm_custom_css",
	"std" => '',
	"type" => "textarea"),
	
	array(  "name" => "Show Empty Categories",
	"desc" => "If checked categories with no posts will be shown.",
	"id" => "shailan_dm_show_empty",
	"std" => false,
	"type" => "checkbox"),
	
	array( "type" => "close" ),
	
	array(
		"name" => "Template Tag",
		"label" => __("Template Tag"),
		"type" => "section"
	),
	
	array(
		"desc" => "Settings here only effect menus inserted with template tag : <code>&lt;?php shailan_dropdown_menu(); ?&gt;</code>. Widget settings are NOT affected by these settings. ",
		"type" => "paragraph"
	),
	
	array(  "name" => "Menu Type",
	"desc" => "Dropdown Menu Type",
	"id" => "shailan_dm_type",
	"std" => "pages",
	"options" => $types,
	"type" => "select"),
	
	array(  "name" => "Home link",
	"desc" => "If checked dropdown menu displays home link",
	"id" => "shailan_dm_home",
	"std" => true,
	"type" => "checkbox"),
	
	array(  "name" => "Login",
	"desc" => "If checked dropdown menu displays login link",
	"id" => "shailan_dm_login",
	"std" => true,
	"type" => "checkbox"),
	
	array(  "name" => "Register / Site Admin",
	"desc" => "If checked dropdown menu displays register/site admin link.",
	"id" => "shailan_dm_login",
	"std" => true,
	"type" => "checkbox"),
	
	array(  "name" => "Vertical menu",
	"desc" => "If checked dropdown menu is displayed vertical.",
	"id" => "shailan_dm_vertical",
	"std" => true,
	"type" => "checkbox"),
	
	array(  "name" => "Exclude Pages",
	"desc" => "Excluded page IDs.",
	"id" => "shailan_dm_exclude",
	"std" => "",
	"type" => "text"),
	
	array(  "name" => "Alignment",
	"desc" => "Menu alignment.",
	"id" => "shailan_dm_align",
	"std" => "left",
	"options" => $alignment,
	"type" => "select"),
	
	array( "type" => "close" )
	
);