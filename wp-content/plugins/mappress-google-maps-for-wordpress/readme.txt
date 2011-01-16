=== MapPress Easy Google Maps ===
Contributors: chrisvrichardson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4339298
Tags: google maps,google,map,maps,easy,poi,mapping,mapper,gps,lat,lon,latitude,longitude,geocoder,geocoding,georss,geo rss,geo,v3,marker,mashup,mash,api,v3,buddypress,mashup,geo,wp-geo,geo mashup,simplemap,simple,wpml
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 2.28

MapPress is the easiest way to create great-looking Google Maps and driving directions in your blog.

== Description ==
MapPress adds an interactive map to the wordpress editing screens.  When editing a post or page just enter any addresses you'd like to map.

The plugin will automatically insert a great-looking interactive map into your blog. Your readers can get directions right in your blog and you can even create custom HTML for the map markers (including pictures, links, etc.)!

For even more features, try the [MapPress Pro Version](http://wpplugins.com/plugin/235/mappress-pro)

* Need help?  Check out the [support forum](http://wphostreviews.com/forums/forum.php?id=1)
* What would you like to see next? [Take the Poll](http://www.wphostreviews.com/mappress).
* For questions and suggestions: [contact me](http://wphostreviews.com/chris-contact) using the web form or email me (chrisvrichardson@gmail.com)

= Key Features =
* MapPress is based on the latest Google maps API v3 - it's fast, optimized for mobile phones - and no API keys are required!
* WordPress 3.0 and MultiSite compatible
* Create maps from custom fields
* Custom post types are supported
* Easily create maps right in the standard post edit and page edit screens
* Add markers for any address, place or latitude/longitude location, or drag markers where you want them
* Create custom text and HTML for the markers, including photos, links, etc.
* Street view supported
* Readers can get driving, walking and bicycling directions right in your blog.  Directions can be dragged to change waypoints or route
* Multiple maps can be created in a single post or page
* Automatically create a table of locations under each map
* Real-time traffic
* New shortcodes with many parameters: "mapid" (to specify which map to show), "width" "height", "zoom", etc.
* Programming API to develop your own mapping plugins

= Pro Version Features =
* Get the [MapPress Pro Version](http://wpplugins.com/plugin/235/mappress-pro) for additional functionality
* Use different marker icons in your maps - over 200 standard icons included
* Use your own custom icons in your maps or download thousands of icons from the web
* Shortcodes and template tags for "mashups": easily create a "mashup" showing all of your map locations on a single map
* Mashups can automatically link to your blog posts and pages and they can display posts by category, date, tags, etc.
* MapPress widgets: add widgets to your sidebar to show a map or a mashup
* Display a clickable list of mapped icons and locations right under the map
* Remove the 'powered by' link

[Home Page](http://www.wphostreviews.com/mappress) |
[Documentation](http://www.wphostreviews.com/mappress-documentation-144) |
[FAQ](http://www.wphostreviews.com/mappress-faq) |
[Support](http://www.wphostreviews.com/mappress-faq)

== Screenshots ==
1. Options screen
2. Visual map editor in posts and pages
3. Map displayed in your blog
4. Map directions

= Localization =
Please [Contact me](http://wphostreviews.com/chris-contact) if you'd like to provide a translation or an update.  Special thanks to:

* Spanish - Seymour
* Italian - Gianni D.
* Finnish - Jaakko K.
* German - Stefan S. and Stevie
* Dutch	- Wouter K.
* Chinese / Taiwanese - Y.Chen
* Simplified Chinese - Yiwei
* Swedish - Mikael N.
* French - Sylvain C. and Jérôme
* Russian - Alexander C.
* Hungarian - Németh B.

== Upgrade Notice ==
If you're upgrading by copying the files please be sure to DEACTIVATE your old version, copy the files, then ACTIVATE the new version

== Installation ==

See full [installation intructions and Documentation](http://www.wphostreviews.com/mappress-documentation-144)
1. Unzip the files into a directory in `/wp-content/plugins/`, for example `/wp-content/plugins/mappress-google-maps-for-wordpress`.  Be sure to put all of the files in this directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's it - now you'll see a MapPress meta box in in the 'edit posts' screen.  You can use it to add maps to your posts just by entering the address to display and an (optional) comment for that address.

== Upgrade ==

1. Deactivate your old MapPress version
1. Unzip the files into a directory in `/wp-content/plugins/`, for example `/wp-content/plugins/mappress-google-maps-for-wordpress`.  Be sure to put all of the files in this directory.
1. Activate the new version through the 'Plugins' menu in WordPress
1. That's it - now you'll see a MapPress meta box in in the 'edit posts' screen.  You can use it to add maps to your posts just by entering the address to display and an (optional) comment for that address.

== Frequently Asked Questions ==

Please read the **[FAQ](http://www.wphostreviews.com/mappress-faq)**

== Screenshots ==

1. Options screen
2. Visual map editor in posts and pages
3. Edit map markers in the post editor
4. Get directions from any map marker

== Changelog ==
2.28
=
* Fixed: unable to add new locations (broken by a change in the Pro version)

2.27
=
* Added: ability to show directions initially.  Use [mappress initialopendirections="true"] to use this feature.
* Changed: changed label "location list" to "marker list" (no functionality change, just the labels)
* Fixed: added missing texts for locationlization
* Fixed: added <p> tags around directions to support strict XHTML validation
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Changed: the default marker list template now just shows [title] rather than [title] and [body]
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Changed: the marker list template [body] tag now shows FULL HTML for the body.  Use [bodytext] to show the text with the HTML stripped out.
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: new widget options for showing directions and a marker list
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: editor now remembers last icon selected

2.26
=
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: bug in 2.25 caused markers to list incorrectly when editing
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Changed: updated marker list display to show marker title + plain text of marker body (see docs for details)

2.25
=
* Added: "reset defaults" button on options screen
* Fixed: in some cases the mappress shortcode could appear in RSS feeds
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Changed: when saving empty custom address field, no map created
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: POI template function wasn't using user template
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: powered by link incorrectly labeled

2.24
=
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: error saving custom field name for errors

2.23
=
* Fixed: incorrect directions routing for foreign addresses, e.g. French
* Fixed: missing translation for some strings
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: directions link not working in marker list
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: incorrect overflow handling for POI list in IE8

2.22
=
* Fixed: warning on settings screen

2.20-2.21
=
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: setting for list of locations under map
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: setting to remove powered by link
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: extended automatic map creation for custom fields
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: extended query processing to allow array options

2.19
=
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Added: create maps from custom field metadata for [TurboCSV](http://wphostreviews.com/turbocsv)

*
2.18
=
* Same as 2.17.  Trying a re-upload to fix the 404 errors in the wordpress repository

2.17
=
* Fixed: plugin was not reporting database tables correctly when table prefix was in upper case
* Fixed: zoom was wrong for only 1 POI if entered by lat/lng
* Fixed: multisite network activation implemented

2.16
=
* Set marker link color blue (some themes use white links); you can override in mappress.css ".mapp-overlay a"
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: for mashups, WP editor replaced & with &amp; and defaults were not set correctly
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: better title/directions URL handling for mashups & widget if POI was created using lat/lng instead of address

2.15
=
* Enhanced address correction for US/Foreign addresses
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: bugs related to TurboCSV integration
* [MapPress Pro](http://wpplugins.com/plugin/235/mappress-pro) Fixed: "my icons" click events

2.14
=
* Fixed: bug in 2.13 for lat/lng directions broke adding addresses to new maps

2.13
=
* Added: better user icon handling for Pro version

2.12
=
* Added: directions for lat/lng locations.  Just enter lat,lng in the from or to directions input box.
* Plugin version displayed in post/page edit metaboxes
* Simplified marker overlay layout and CSS; should help prevent scrollbars when displaying and editing map
* Added routines for TurboCSV integration

2.11
=
* Plugin version displayed in post/page edit metaboxes

2.10
=
* Fixed: marker body change lost when changing icon (Pro)

2.0.9
=
* Fixed: dragging didn't work until map was save
* Fixed: javascript warning when adding new POI
* Fixed: icon 'back' link didn't work (Pro)
* Fixed: icon reset after canceling icon selection (Pro)

2.0.8
=
* Fixed bug preventing saving some options as unchecked.

2.0.7
=
* You can now specify "center_lat" and "center_lng" in the shortcode to set the map center
* Fixed bug where zoom was not being set if provided in shortcode
* Fixed bug where directions link would not work
* Rewrote meta_key shortcode processing - will be available in Pro version
2.0.6
=
* Workaround added for prototype.js JSON bugs caused by other plugins including prototype library.  Prototype 1.6.1 breaks jQuery width(), height(), and JSON stringify for arrays
* Added additional debug info to find cases where plugin PHP JSON libraries have conflict
* Fixed an error in CSS class .mapp-overlay-links

2.0.4
=
* Added some missing strings for translations
* Added new option to the MapPress 'settings' screen to resize all maps at once.
* Widened lat/lng input
* Added support for WPML language settings (http://wpml.org)
* Converted custom CSS checkbox to an input field
* Settings should no longer be reset on upgrade

2.0.3
=
* Added warning about need to activate new plugin version

2.0.2
=
* Fixed: some PHP versions were giving error T_OBJECT_OPERATOR

2.0.1
=
* Fixed activation error for 2.0
* Added street view support
* Added keyboard shortcuts setting to enable/disable keyboard scrolling & zoom

2.0
=
* MapPress now uses Google maps API v3 - it's faster, optimized for mobile phones - and no more API keys!
* WordPress 3.0 and MultiSite compatible
* Multiple maps in a single post or page
* Custom post types support
* Optimized loading: javascript and CSS are loaded ONLY on pages with a page
* Maps can be generated from custom fields - you can even use [TurboCSV](http://wphostreviews.com/turbocsv) to upload maps from a spreadsheet
* Custom post types are fully supported
* Driving, walking and bicycling directions, and directions can be dragged to change waypoints or route
* Real-time traffic
* New shortcodes with many parameters: "mapid" (to specify which map to show), "width" "height", "zoom", etc.
* Programming API to develop your own mapping plugins
* Marker tooltips
