=== Dropdown Menu Widget ===
Contributors: mattsay
Donate link: http://shailan.com/donate  
Tags: css, dropdown, menu, widget, pages, categories, multi, jquery, navigation
Requires at least: 2.8  
Tested up to: 3.0.2
Stable tag: 1.5.8

This widget adds a beatiful vertical/horizontal CSS only dropdown menu of Pages, Categories or Custom navigation menus of your blog.

== Description ==

Dropdown Menu widget adds a beautiful, CSS only dropdown menu, listing pages, categories of your blog. You can also turn your wordpress navigation menu into a beatiful dropdown menu using this plugin. 
It allows you to chose vertical or horizontal layout. It supports multiple instances. You can select a theme for your widget from the Dropdown Menu Settings page or you can CREATE  YOUR OWN THEME WITHIN SAME PLUGIN!! You can also customize your dropdown menu using CSS. If you want a custom dropdown theme you can [request](http://shailan.com/contact) one. Please visit [plugin site](http://shailan.com/wordpress/plugins/dropdown-menu) for more information. 

== Installation ==

1. Upload the plugin to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance -> Widgets to add this widget to one of your sidebars
1. You can also use `<?php shailan_dropdown_menu(); ?>` in your template to display the menu.
1. Don't forget to change menu settings from Settings -> Dropdown Menu panel.

== Frequently Asked Questions ==

= I added this widget to my sidebar but it looks all weird! =

This widget is intented for *wide header widget areas*, not regular sidebars. You can add a sidebar to your theme or you can get a *all-widget* theme from  [shailan.com](http://shailan.com). 

= Can i create my own theme? =

Since this plugin works on CSS, if you are capable of writing CSS, you can customize the theme as you like it.

= I don't know CSS, how can i customize it? = 

Plugin comes with various themes already installed. If you want something different, then you can [request a new theme](http://shailan.com/contact). 

= I found a bug! Where do i submit it? =

You can submit errors and bugs using the [online form](http://shailan.com/contact) on my site OR you can submit via [wordpress support tags here](http://wordpress.org/tags/dropdown-menu-widget).

== Screenshots ==

1. A preview of the widget in action
1. Shiny Black menu theme
1. Brand new dropdown menu options page.

== Changelog ==

= 1.5.8 = 
* Added dropdown_menu_defaults filter for changing default options like order, depth etc.

= 1.5.7 =
* Fixed various css issues.
* Removed backgrounds for IE on certain themes.
* Hopefully works with all browsers with javascript enabled (Tested with Chrome, Firefox, Safari, Opera and IE6).
* Please submit any bugs you see on [dropdown menu plugin page](http://shailan.com/wordpress/plugins/dropdown-menu/)

= 1.5.6beta =
* Added hooks for inserting your own menu & other navigational elements.
* Fixed z-index errors.
* Fixed background problem with custom theme selection.
* Added "include archives" option to Dropdown-Multi widget.

= 1.5.6alpha1 = 
* Fixed sub menu display errors. 
* Added alignment option to template tag support.

= 1.5.6alpha = 
* Fixed options page saving error.
* Added a brand new color selection interface for custom "Color Scheme" (beta). 
* Now you can easily select your own colors & plus overlays!

= 1.5.5 = 
* New theme! Hulu style theme.
* Added new action hooks to insert your items to the dropdown menu.

= 1.5.4 = 
* Added first-child & last-child selector for styling to dropdown menu. (available when jquery is active only)
* Fixed IE display errors for shiny black theme.
* Fixed not saving issues with the plugin options.

= 1.5.3 =
* Made Custom CSS area available even when a theme is selected. You can now use this area for your theme customizations.
* Fixed `Call undefined function wp_nav_menu on 365` error.
* Fixed font-size problems with `Shiny Black` theme.
* Removed behaviour fix for IE. Now using only jquery as dropdown fix which comes packed with wordpress.

= 1.5.2 = 
* Fixed tested version number.
* Added screenshot 2.
* Added options page screenshot.
* Now plugin allows you to rename the home link.

= 1.5.1 = 
* Removed custom walker support for now.
* Removed blue tabs theme since it was using custom walkers.
* Fixed all the styles to work with new css classes. 
* Updated Shiny Black theme to work with wide links.
* Complete support for custom css insert.

= 1.5 = 
* Fixed issues with wordpress 3.0.
* Renamed plugin to dropdown menu.
* Removed inline style option.
* Removed unnecessary screenshots.
* Added support for wordpress 3.0 navigation menus.
* Removed exclude pages plugin.

= 1.4.1 = 
* A minor fix for `Parse error: parse error, expecting 'T_FUNCTION' in C:\wamp\www\wordpress\wp-content\plugins\dropdown-menu-widget\shailan-multi-dropdown.php on line 194` error. 

= 1.4.0 = 
* Added option for multiline links. If checked a link with more than one word will be wrapped.
* Another fix for IE. Hopefully last.

= 1.3.9 = 
* Fixed errors for IE jquery support. 
* Added belorussian translation provided by [Marcis G.](http://pc.de)
* Added lang folder for translations.

= 1.3.8 =
* Added option for displaying title attributes. You may now turn title display on from the Settings page.

= 1.3.7 =
* Removed unnecessary extrude line from Dropdown Multi widget. 

= 1.3.6 = 
* Fixed "Dropdown Multi" widget error with categories and links.

= 1.3.5 = 
* Added "Dropdown Multi" widget that allows you to inlude pages, categories and links in one menu.

= 1.3.4 = 
* Fixed dropdown errors for IE7. Report any bugs with a screenshot please. Thanks.

= 1.3.3 =
* Fixed function name collisions with "Exclude Pages" plugin. The plugin is fully functional now.

= 1.3.2 =
* Bundled with "Exclude Pages" plugin by [Simon Wheatley](http://simonwheatley.co.uk/wordpress/). You can now easily exclude pages from the navigation. Just uncheck the the "Include page in menus" checkbox on the page edit screen. See [screenshots](http://wordpress.org/extend/plugins/dropdown-menu-widget/screenshots/) for more information.

= 1.3.1 = 
* Added "Blue gradient" theme.

= 1.3.0 = 
* Fixed "Home" link bug for the template tag. Thanks to Jeff.

= 1.2.7 =  
* Added "include homepage link" for both pages and categories now. You can enable/disable this link from the widget options easily.

= 1.2.6 = 
* Fixed a minor bug.

= 1.2.5 = 
* Added translation support. 
* Added pot file for translators.

= 1.2.4 =
* Fixed category walker for the advanced styling.

= 1.2.3 =
* Added Aqua theme.
* Added <span> elements in the menu so more advanced styling can be made.
* Added alignment option. Now you can align your menu wherever you wanted!
* Added Shiny Black theme.

= 1.2.0 =
* Removed title attributes for the categories dropdown menu items.

= 1.1.0 =
* Added custom walker class to disable title attributes on menu items.
* Renamed class and style files.
* Fixed default theme.

= 1.0.0 =
* Added vertical dropdown menu functionality.
* Fixed widget code.
* Changed dropdown widget classname to : shailan-dropdown-menu
* Changed wrapper div classname to : shailan-dropdown-wrapper
* Moved li item paddings to anchor elements.

= 0.4.3 =
* New grayscale theme.
* Template tag `shailan_dropdown_menu()` is available now. See usage for more info.
* Template tag options added.

= 0.4.2 = 
* Fixed XHTML error on link tags.
* Fixed Inline Style error on categories dropdown menu.
* Removed unnecessary files.

= 0.4.1 =
* Fixed XHTML issues.
* Added WP Default theme.
* Made some minor fixes to widget options form.

= 0.3 =
* Fixed problems about styling. Now you can change dropdown menu style from the options page.

= 0.2 = 
* First public release.
* Added login and register button options.

== TODO == 

* Add option for custom menus.
* Add some more themes.. [Request a theme](http://shailan.com/contact)