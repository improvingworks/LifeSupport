<?php

/**
* Generic object functions
*/
class Mappress_Obj {
	function Mappress_Obj($atts=null) {
		$this->update($atts);
	}

	function update($atts=null) {
		if (!$atts)
			return;

		$obj_atts = get_object_vars($this);

		foreach ($obj_atts as $key => $value ) {
			if (substr($key, 0, 1) == '_')
				continue;

			$newvalue = (isset($atts[$key])) ? $atts[$key] : null;

			// Attributes are sometimes passed in lowercase
			if ($newvalue === null) {
				$lkey = strtolower($key);
				$newvalue = (isset($atts[$lkey])) ? $atts[$lkey] : null;
			}

			if ($newvalue === null)
				continue;

			// Convert any string versions of true/false
			if ($newvalue === "true")
				$newvalue = true;
			if ($newvalue === "false")
				$newvalue = false;

			$this->$key = $newvalue;
		}
	}
} // End class Mappress_Ojb

/**
* Options
*/
class Mappress_Options extends Mappress_Obj {
	var $directions = 'inline',                             // inline | google | none
		$mapTypeControl = true,
		$streetViewControl = true,
		$scrollwheel = false,
		$keyboardShortcuts = true,
		$navigationControlOptions = array('style' => 0),
		$initialOpenInfo = false,
		$initialOpenDirections = false,
		$country = null,
		$language = null,
		$traffic = true,
		$tooltips = true,
		$alignment = 'default',
		$autodisplay = 'top',
		$editable = false,
		$mapName = null,
		$postid = null,
		$autoCenter = false,
		$postTypes = array('post', 'page'),
		$customCSS = '',
		$control = true,
		$poiList = false,
		$poiListTemplate = "<td class='mapp-marker'>[icon]</td><td><b>[title]</b>[directions]</td>",
		$metaKey = null,
		$metaSyncSave = true,
		$metaSyncUpdate = true,
		$metaKeyErrors = null;

	// Options are saved as array because WP settings API is fussy about objects
	function get() {
		$options = get_option('mappress_options');
		return new Mappress_Options($options);
	}

	function save() {
		return update_option('mappress_options', get_object_vars($this));
	}
} // End class Mappress_Options


/**
* POIs
*/
class Mappress_Poi extends Mappress_Obj {
	var $point = array('lat' => 0, 'lng' => 0),
		$title = '',
		$titleUrl = null,
		$body = '',
		$address = null,
		$correctedAddress = null,
		$iconid = null,
		$viewport = null,       // array('sw' => array('lat' => 0, 'lng' => 0), 'ne' => array('lat' => 0, 'lng' => 0))
		$poiListTemplate = null;

	/**
	* Geocode an address using http
	*
	* @param mixed $auto true = automatically update the poi, false = return raw geocoding results
	* @return true if auto=true and success | raw geocoding results if auto=false | WP_Error on failure
	*/
	function geocode($auto=true) {
		// If point was defined using only lat/lng then no geocoding
		if (!empty($this->point->lat) && !empty($this->point->lng)) {
			// Default title if empty
			if (empty($this->title))
				$this->title = $this->point->lat . ',' . $this->point->lng;
			return;
		}

		$options = Mappress_Options::get();
		$language = $options->language;
		$country = $options->country;

		$address = urlencode($this->address);
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&output=json";
		if ($country)
			$url .= "&region=$country";
		if ($language)
			$url .= "&language=$language";

		$response = wp_remote_get($url);

		// If auto=false, then return the RAW result
		if (!$auto)
			return $response;

		// Check for http error
		if (is_wp_error($response))
			return $response;

		if (!$response)
			return new WP_Error('geocode', sprintf(__('No geocoding response from Google: %s', 'mappress'), $response));

		//Decode response and automatically use first address
		$response = json_decode($response['body']);

		if (!$response  || !isset($response->results) || !isset($response->results[0]))
			return new WP_Error('geocode', sprintf(__("No geocoding result for address: %s", 'mappress'), $this->address));

		$status = isset($response->status) ? $response->status : null;
		if ($status != 'OK')
			return new WP_Error('geocode', sprintf(__("Google cannot geocode address: %s, status: %s", 'mappress'), $this->address, $status));

		$placemark = $response->results[0];

		// Point
		$this->point = array('lat' => $placemark->geometry->location->lat, 'lng' => $placemark->geometry->location->lng);

		// Viewport
		$this->viewport = array(
			'sw' => array('lat' => $placemark->geometry->viewport->southwest->lat, 'lng' => $placemark->geometry->viewport->southwest->lng),
			'ne' => array('lat' => $placemark->geometry->viewport->northeast->lat, 'lng' => $placemark->geometry->viewport->northeast->lng)
		);

		// Corrected address
		$this->correctedAddress = $placemark->formatted_address;

		// Default the title and body if empty
		if ($this->title && $this->body)
			return true;

		// Remove USA
		if (strstr($this->correctedAddress, ', USA')) {
			$this->correctedAddress = str_replace(', USA', '', $this->correctedAddress);  // Strip ", USA"

			// If there's exactly ONE comma and address is in USA, return single line, e.g. "New York, NY"
			if (substr_count($this->correctedAddress, ',') == 1) {
				$this->title = $this->correctedAddress;
				return true;
			}
		}

		// If no commas then use a single line, e.g. "France" or "Ohio"
		if (!strpos($this->correctedAddress, ',')) {
			$this->title = $this->correctedAddress;
			return true;
		}

		// Otherwise return first line from before first comma+space, second line after, e.g. "Paris, France" => "Paris<br>France"
		// Or "1 Main St, Brooklyn, NY" => "1 Main St<br>Brooklyn, NY"
		$this->title = substr($this->correctedAddress, 0, strpos($this->correctedAddress, ","));
		$this->body = substr($this->correctedAddress, strpos($this->correctedAddress, ",") + 2);
		return true;
	}
} // End class Mappress_Poi



/**
* Map class
*/
class Mappress_Map extends Mappress_Obj {
	var $mapid = null,
		$width = 425,
		$height = 350,
		$zoom = null,
		$center = array('lat' => 0, 'lng' => 0),
		$mapTypeId = 'roadmap',
		$title = 'Untitled',
		$metaKey = null,
		$pois = array();

	function Mappress_Map($atts=null) {
		$parent = get_parent_class($this);
		$this->$parent($atts);
		$this->_fixup_pois();
	}

	function _fixup_pois() {
		// Convert POIs from arrays to objects if needed
		foreach((array)$this->pois as $index => $poi) {
			if (is_array($poi))
				$this->pois[$index] = new Mappress_Poi($poi);
		}
	}

	function db_create() {
		global $wpdb;
		$maps_table = $wpdb->prefix . 'mappress_maps';
		$posts_table = $wpdb->prefix . 'mappress_posts';

		$wpdb->show_errors(true);

		$result = $wpdb->query ("CREATE TABLE IF NOT EXISTS $maps_table (
								mapid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
								obj LONGTEXT)
								CHARACTER SET utf8;");

		$result = $wpdb->query ("CREATE TABLE IF NOT EXISTS $posts_table (
								postid INT,
								mapid INT,
								PRIMARY KEY (postid, mapid) )
								CHARACTER SET utf8;");

		$wpdb->show_errors(false);
	}

	/**
	* Get a map.  Called statically.
	*
	* @param mixed $mapid
	* @return mixed false if failure, or a map object on success
	*/
	function get($mapid) {
		global $wpdb;
		$maps_table = $wpdb->prefix . 'mappress_maps';
		$result = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $maps_table WHERE mapid = %d", $mapid) );  // May return FALSE or NULL

		if (!$result)
			return false;

		// Fix up mapid
		$map = unserialize($result->obj);
		$map->mapid = $result->mapid;
		return $map;
	}

	/**
	* Gets a list of ALL maps
	*
	* @return mixed false if failure, array of maps if success
	*
	*/
	function get_list() {
		global $wpdb;
		$maps_table = $wpdb->prefix . 'mappress_maps';
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $maps_table"));

		if ($results === false)
			return false;

		// Fix up mapid
		foreach ($results as $result) {
			$map = unserialize($result->obj);
			$map->mapid = $result->mapid;
			$maps[] = $map;
		}

		return $maps;
	}

	function save($postid) {
		global $wpdb;
		$maps_table = $wpdb->prefix . 'mappress_maps';
		$posts_table = $wpdb->prefix . 'mappress_posts';

		$map = serialize($this);

		// Update map
		if (!$this->mapid) {
			// If no ID then autonumber
			$result = $wpdb->query($wpdb->prepare("INSERT INTO $maps_table (obj) VALUES(%s)", $map));
			$this->mapid = (int)$wpdb->get_var("SELECT LAST_INSERT_ID()");
		} else {
			// Id provided, so insert or update
			$result = $wpdb->query($wpdb->prepare("INSERT INTO $maps_table (mapid, obj) VALUES(%d, '%s') ON DUPLICATE KEY UPDATE obj = %s", $this->mapid, $map, $map));
		}

		if ($result === false || !$this->mapid)
			return false;

		// Update posts
		$result = $wpdb->query($wpdb->prepare("INSERT INTO $posts_table (postid, mapid) VALUES(%d, %d) ON DUPLICATE KEY UPDATE postid = %d, mapid = %d", $postid, $this->mapid,
			$postid, $this->mapid));

		if ($result === false)
			return false;

		$wpdb->query("COMMIT");
		return $this->mapid;
	}

	// Delete the map and all posts for it
	function delete() {
		global $wpdb;
		$maps_table = $wpdb->prefix . 'mappress_maps';
		$posts_table = $wpdb->prefix . 'mappress_posts';

		// If map was never saved there's nothing to do
		if(!$this->mapid)
			return true;

		// Delete from posts table
		$result = $wpdb->query($wpdb->prepare("DELETE FROM $posts_table WHERE mapid = %d", $this->mapid));
		if ($result === false)
			return false;

		$result = $wpdb->query($wpdb->prepare("DELETE FROM $maps_table WHERE mapid = %d", $this->mapid));
		if ($result === false)
			return false;

		$wpdb->query("COMMIT");
		return true;
	}

	/**
	* Delete a map assignment to a post - currently unused
	*
	* @param int $mapid Map to remove
	* @param int $postid Post to remove from
	* @return TRUE if map has been removed, FALSE if map wasn't assigned to the post
	*/
	function delete_post_map($postid) {
		global $wpdb;
		$posts_table = $wpdb->prefix . 'mappress_posts';

		$results = $wpdb->get_results($wpdb->query("DELETE $posts_table WHERE postid = %d AND mapid = %d", $postid, $this->mapid));

		if ($results === false)
			return false;
		return true;
	}

	/**
	* Get a single map attached to a post
	*
	* @param int $postid Post for which to get the list
	* @param int $mapid Map id of the map to retrieve
	* @param string $meta_key retrieve map for a given meta_key (assumption is that there can be only one)
	* @param int $postid Post for which to get the list
	* @return a single Map object or FALSE if no map exist for the given criteria
	*/
	function get_post_map ($postid, $mapid = null, $meta_key = null) {
		global $wpdb;
		$posts_table = $wpdb->prefix . 'mappress_posts';

		// Search by map ID
		if ($mapid) {
			$result = $wpdb->get_row($wpdb->prepare("SELECT postid, mapid FROM $posts_table WHERE postid = %d AND mapid = %d", $postid, $mapid));
			if ($result !== false)
				return Mappress_Map::get($mapid);
			else
				return false;
		}

		// Search by meta_key
		$results = $wpdb->get_results($wpdb->prepare("SELECT postid, mapid FROM $posts_table WHERE postid = %d", $postid));

		if ($results === false)
			return false;

		// Find which map, if any, has the given meta_key
		foreach($results as $key => $result) {
			$map = Mappress_Map::get($result->mapid);
			if ($map->metaKey == $meta_key)
				return $map;
		}
		return false;
	}


	/**
	* Get a list of maps attached to the post
	*
	* @param int $postid Post for which to get the list
	* @return an array of all maps for the post or FALSE if no maps exist.  If $postid is null, dumps all post/map combinations for debugging
	*/
	function get_post_map_list($postid=null) {
		global $wpdb;
		$posts_table = $wpdb->prefix . 'mappress_posts';

		// For debugging: dump the entire table
		if (!$postid) {
			$results = $wpdb->get_results($wpdb->prepare("SELECT postid, mapid FROM $posts_table"));
			return $results;
		}

		$results = $wpdb->get_results($wpdb->prepare("SELECT postid, mapid FROM $posts_table WHERE postid = %d", $postid));

		if ($results === false)
			return false;

		// Get all of the maps
		$maps = array();
		foreach($results as $key => $result) {
			$maps[] = Mappress_Map::get($result->mapid);
		}
		return $maps;
	}

	function display($atts = null) {
		global $mappress;
		static $div = 0;

		$options = Mappress_Options::get();

		// Update the options and map settings with any passed attributes
		$options->update($atts);
		$this->update($atts);

		// For anyone using WPML (wpml.org): set the selected language if it wasn't specified in the options screen
		if (defined('ICL_LANGUAGE_CODE') && !$options->language)
			$options->language = ICL_LANGUAGE_CODE;

		// Append 'px' if needed - some browsers like Chrome are fussy about it
		$width = (stripos($this->width, 'px') || strpos($this->width, '%')) ? $this->width : $this->width . 'px';
		$height = (stripos($this->height, 'px') || strpos($this->height, '%')) ? $this->height : $this->height . 'px';


		// Set justification as inline styles if specified on options screen
		$container_style = "";
		$canvas_style = "";
		switch ($options->alignment) {
			case 'left' :
				$container_style = "style='float:left'";
				break;
			case 'right' :
				$container_style = "style='float:right'";
				break;
			case 'center' :
				$canvas_style = "margin-left:auto; margin-right:auto;";
				break;
		}

		// Assign a map name if none provided
		if (!isset($options->mapName)) {
			$options->mapName = "mapp$div";
			$div++;
		}

		// Use default POI list template for each row if no alternate template was provided
		if ($options->poiList) {
			foreach($this->pois as $i => $poi) {
				if (!$poi->poiListTemplate)
					$this->pois[$i]->poiListTemplate = $options->poiListTemplate;
			}
		}

		Mappress_Map::_load($options);

		echo "<script type='text/javascript'>"
			. "/* <![CDATA[ */"
			. "var mapdata = " . json_encode($this) . ";"
			. "var options = " . json_encode($options) . ";"
			. "var $options->mapName = new MappMap(mapdata, options);"
			. "$options->mapName.display();"
			. "/* ]]> */"
			. "</script>";

		$html = "<div class='mapp-container' $container_style>"
			. "<div id='$options->mapName' class='mapp-canvas' style='$canvas_style width: $width; height: $height;' ><span class='mapp-loading'></span></div>";

		// List of locations
		if ($options->poiList) {
			$html .= "<div id='{$options->mapName}_poi_list' style='width:$width;max-height:$height' class='mapp-poi-list'></div>";
		}

		if ($options->directions == 'inline') {
			$html .= "<div id='{$options->mapName}_directions' class='mapp-directions'>"
						. "<form action=''>"
							. "<table>"
								. "<col class='mapp-directions-table-col1'/>"
								. "<col/>"
								. "<tr>"
									. "<td colspan='2'>"
										. "<span style='float:right'><input type='button' id='{$options->mapName}_closedirections' value ='" . __('Close', 'mappress') . "'/></span>"
										. "<span id='{$options->mapName}_car_button' class='mapp-car-button mapp-travelmode selected' title='" . __('By car', 'mappress') . "' ></span>"
										. "<span id='{$options->mapName}_walk_button' class='mapp-walk-button mapp-travelmode' title='" . __('Walking', 'mappress') . "' ></span>"
										. "<span id='{$options->mapName}_bike_button' class='mapp-bike-button mapp-travelmode' title='" . __('Bicycling', 'mappress') . "' ></span>"
									. "</td>"
								. "</tr>"
								. "<tr>"
									. "<td>"
										. "<span class='mapp-a' title='" . __('Start', 'mappress') . "'></span>"
									. "</td>"
									. "<td>"
										. "<input type='text' id='{$options->mapName}_saddr' value='' />"
									. "</td>"
								. "</tr>"
								. "<tr>"
									. "<td><span class='mapp-swap' id='{$options->mapName}_addrswap' title='" . __('Swap start and end', 'mappress') . "'></span></td>"
									. "<td>"
										. "<span id='{$options->mapName}_saddr_corrected' class='mapp-address-corrected'></span>"
									. "</td>"
								. "</tr>"
								. "<tr>"
									. "<td><span class='mapp-b' title='" . __('End', 'mappress') . "'></span></td>"
									. "<td><input type='text' id='{$options->mapName}_daddr' value='' /></td>"
								. "</tr>"
								. "<tr>"
									. "<td></td>"
									. "<td><span id='{$options->mapName}_daddr_corrected' class='mapp-address-corrected'></span></td>"
								. "</tr>"
							. "</table>"
							. "<p>"
							. "<input type='submit' value='" . __('Get Directions', 'mappress'). "' id='{$options->mapName}_get_directions' />"
							. "<input type='button' value='" . __('Print Directions', 'mappress') . "' id='{$options->mapName}_print_directions' />"
							. "</p>"
						. "</form>"
						. "<div id='{$options->mapName}_directionspanel'></div>"
					. "</div>";
		}
		$html .= "</div>";
		return $html;
	}

	function edit($maps = null, $postid) {
		global $mappress;

		// Set options for editing
		$options = Mappress_Options::get();
		$options->postid = $postid;
		$options->mapName = 'mapp0';
		$options->directions = 'none';
		$options->mapTypeControl = true;
		$options->navigationControlOptions = array('style' => 0);
		$options->initialOpenInfo = false;
		$options->traffic = false;
		$options->editable = true;

		Mappress_Map::_load($options);
		echo "<script type='text/javascript'>"
			. "/* <![CDATA[ */"
			. "var mapdata = " . json_encode($maps) . ";"
			. "var options = " . json_encode($options) . ";"
			. "var version = '" . $mappress->get_version() . "';"
			. "var mappEditor = new MappEditor(mapdata, options);"
			. "/* ]]> */"
			. "</script>";

		?>
		<div id='mapp_metabox'>
			<div style='border-bottom:1px solid black; overflow: auto'>
				<div>
					<br/>
					<a target='_blank' style='vertical-align: middle;text-decoration:none'  href='http://wphostreviews.com/mappress'>
						<img alt='MapPress' title='MapPress' src='<?php echo plugins_url('images/mappress_logo_small.png', __FILE__); ?>'>
					</a>
					<?php echo $mappress->get_version(); ?>
					| <a target='_blank' href='http://wphostreviews.com/mappress/mappress-documentation-144'><?php _e('Documentation', 'mappress')?></a>
					| <a target='_blank' href='http://wphostreviews.com/mappress/mappress-faq'><?php _e('FAQ', 'mappress')?></a>
					| <a target='_blank' href='http://wphostreviews.com/mappress/chris-contact'><?php _e('Report a bug', 'mappress')?></a>

					<?php if (!class_exists('Mappress_Pro')) { ?>
							<input id='mapp_paypal' style='vertical-align: middle;width:92px;height:26px' type='image' src='<?php echo plugins_url('images/btn_donate_LG.gif', __FILE__);?>' name='donate' alt='PayPal - The safer, easier way to pay online!' />
					<?php } ?>

				</div>

				<div id='mapp_add_panel' style='visibility:hidden'>
					<p>
						<span class='submit' style='padding: 0; float: none' >
							<input class='button-primary' type='button' id='mapp_add_btn' value='<?php _e('Add', 'mappress'); ?>' />
						</span>

						<span id='mapp_add_swap_btn' title='<?php _e('Toggle lat/lng entry', 'mappress')?>'></span>

						<span  id='mapp_add_address'>
							<b><?php _e('Location', 'mappress') ?>: </b>
							<input size='50' type='text' id='mapp_saddr' />
						</span>

						<span id='mapp_add_latlng'>
							<b><?php _e('Lat/Lng', 'mappress') ?>: </b>
							<input type='text' size='18' id='mapp_lat' value='' />
							<input type='text' size='18' id='mapp_lng' value='' />
						</span>
						<br/><span id='mapp_saddr_corrected' class='mapp-address-corrected'></span>
					</p>
				</div>
			</div>

			<table style='width:100%'>
				<tr>
					<td valign="top">
						<div id='mapp_left_panel'>
							<div id='mapp_maplist_panel'>
								<p>
									<b><?php _e('Current Maps', 'mappress')?></b>
									<input class='button-primary' type='button' id='mapp_create_btn' value='<?php _e('New Map', 'mappress')?>' />
								</p>

								<div id='mapp_maplist'></div>
							</div>

							<div id='mapp_adjust_panel' style='display:none'>
								<div id='mapp_adjust'>
									<p>
										<b><?php _e('Map ID', 'mappress')?>: </b><span id='mapp_mapid'></span>
									</p>
									<p>
										<b><?php _e('Title')?>: </b><input id='mapp_title' type='text' size='20' />
									</p>
									<p>
										<a href='#' class='mapp-edit-size' title='300x300'><?php _e('Small', 'mappress')?></a> |
										<a href='#' class='mapp-edit-size' title='425x350'><?php _e('Medium', 'mappress')?></a> |
										<a href='#' class='mapp-edit-size' title='640x480'><?php _e('Large', 'mappress')?></a>
										<br/><input type='text' id='mapp_width' size='2' value='' /> x <input type='text' id='mapp_height' size='2' value='' />
									</p>
									<p class='submit' style='padding: 0; float: none' >
										<input class='button-primary' type='button' id='mapp_save_btn' value='<?php _e('Save', 'mappress'); ?>' />
										<input type='button' id='mapp_recenter_btn' value='<?php _e('Center', 'mappress'); ?>' />
									</p>
									<hr/>
								</div>
								<div id='<?php echo $options->mapName?>_poi_list' class='mapp-edit-poi-list'></div>
							</div>
						</div>
					</td>
					<td id='mapp_preview_panel' valign='top'>
						<div id='<?php echo $options->mapName?>' class='mapp-edit-canvas'></div>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	function _load($options) {
		global $mappress;
		static $loaded;

		if ($loaded)
			return;
		else
			$loaded = true;

		$url = (isset($_GET['mp_remote'])) ? "http://localhost/wordpress/wp-content/plugins/mappress-google-maps-for-wordpress" : plugins_url('', __FILE__);
		$min = ($mappress->debug) ? "" : "_min";

		echo "<script type='text/javascript' src='http://www.google.com/jsapi'></script>";
		echo "<script type='text/javascript' src='$url/mappress$min.js?version=$mappress->version'></script>";
		echo "<script type='text/javascript' src='$url/json2_min.js?version=$mappress->version'></script>";  // Json is always minified

		if (class_exists('Mappress_Pro')) {
			Mappress_Pro::_load_icons( plugins_url('', __FILE__), $options );
		}

		$script = "var mappl10n = " . json_encode(Mappress_Map::_localize()) . ";"
			. "var s = document.createElement('LINK'); s.rel = 'stylesheet'; s.type = 'text/css';"
			. "s.href = '$url/css/mappress.css?version=$mappress->version'; document.getElementsByTagName('head').item(0).appendChild(s);";

		// Add custom CSS
		if ($options->customCSS && !empty($options->customCSS)) {
			// Older versions may just have "true" in the customCSS setting - if so ignore it
			if ($options->customCSS !== true) {
				$script .= "var s = document.createElement('LINK'); s.rel = 'stylesheet'; s.type = 'text/css';"
				. "s.href = '{$options->customCSS}?version=$mappress->version'; document.getElementsByTagName('head').item(0).appendChild(s);";
			}
		}

		echo "<script type='text/javascript'>/* <![CDATA[ */ $script /* ]]> */</script>";
	}

	function _localize() {
		// Localize script texts
		return array(
			'maps_in_post' => __('Maps in this post', 'mappress'),
			'no_maps_in_post' => __('There are no maps yet for this post', 'mappress'),
			'create_map' => __('Create a new map', 'mappress'),
			'map_id' => __('Map ID', 'mappress'),
			'untitled' => __('Untitled', 'mappress'),
			'dir_not_found' => __('The starting or ending address could not be found.', 'mappress'),
			'dir_zero_results' => __('Google cannot return directions between those addresses.  There is no route between them or the routing information is not available.', 'mappress'),
			'dir_default' => __('Unknown error, unable to return directions.  Status code = ', 'mappress'),
			'enter_address' => __('Enter address', 'mappress'),
			'no_address' => __('No matching address', 'mappress'),
			'did_you_mean' => __('Did you mean: ', 'mappress'),
			'directions' => __('Directions', 'mappress'),
			'edit' => __('Edit', 'mappress'),
			'save' => __('Save', 'mappress'),
			'cancel' => __('Cancel', 'mappress'),
			'del' => __('Delete', 'mappress'),
			'view' => __('View', 'mappress'),
			'back' => __('Back', 'mappress'),
			'insert_into_post' => __('Insert into post', 'mappress'),
			'select_a_map' => __('Select a map', 'mappress'),
			'title' => __('Title', 'mappress'),
			'delete_prompt' => __('Delete this map marker?', 'mappress'),
			'delete_map_prompt' => __('Delete this map?', 'mappress'),
			'del' => __('Delete', 'mappress'),
			'map_saved' => __('Map saved', 'mappress'),
			'map_deleted' => __('Map deleted', 'mappress'),
			'ajax_error' => __('Error: AJAX failed!  ', 'mappress'),
			'click_and_drag' => __('Click & drag to move this marker', 'mappress'),
			'zoom' => __('Zoom', 'mappress'),
			'traffic' => __('Traffic', 'mappress'),
			'standard_icons' => __('Standard icons', 'mappress'),
			'my_icons' => __('My icons', 'mappress')
		);
	}
} // End class Mappress_Map
?>