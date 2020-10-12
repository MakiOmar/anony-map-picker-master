<?php
if ( !defined('ABSPATH') ) exit();
/**
 * Plugin Name: AnonyEngine WP map picker
 * Description: This jQuery plugin for WordPress can be used to transform an input field into a flexible map field with a location picker.
 * Version: 1.0.0
 * Author: makiomar
 * Author URI: https://makiomar.com
 * License: GPL2
*/
 
/**
 * Holds plugin PATH
 * @const
 */ 
define('ANONY_PICKER_DIR' , wp_normalize_path(plugin_dir_path( __FILE__ )));

/**
 * Holds plugin uri
 * @const
 */
define('ANONY_PICKER_URI', plugin_dir_url( __FILE__ ));

function anony_map_picker(){
	$gmaps_url = add_query_arg( 'language', str_replace( '_', '-', get_locale() ), 'https://maps.google.com/maps/api/js' );
	$gmaps_url = add_query_arg( 'key', 'AIzaSyDJGYMVnbZ6kbjnuSjdGV0tljQ75CFtsuM', $gmaps_url );
	$gmaps_url = add_query_arg( 'libraries', 'places', $gmaps_url );
	
	wp_enqueue_script( 'google-maps', $gmaps_url, array(), false, true );
	wp_enqueue_script( 'wp-map-picker', ANONY_PICKER_URI. 'wp-map-picker.min.js', array( 'jquery', 'jquery-ui-widget', 'jquery-ui-autocomplete', 'google-maps' ), '0.7.1', true );
	wp_enqueue_style( 'wp-map-picker', ANONY_PICKER_URI.'wp-map-picker.min.css', array(), '0.7.1' );
}

add_action( 'wp_enqueue_scripts', 'anony_map_picker' );
add_action( 'admin_enqueue_scripts', 'anony_map_picker' );

add_action( 'wp_footer', function(){?>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var picker = $( '.anony-map-picker input' ).wpMapPicker({
				zoom: 20,
				defaultLocation: { lat: '30.048772', lng: '31.235459', zoom: 15 }
			});
			
			var autocomplete = new google.maps.places.Autocomplete($(".anony-map-picker input")[0], {});
			
			// Avoid paying for data that you don't need by restricting the set of
  			// place fields that are returned to just the address components.
			autocomplete.setFields(["address_component", "formatted_address", "geometry"]);
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
				
				$( '.anony-map-picker input' ).val(place.formatted_address);
				var lat = place.geometry.location.lat();
				var lng = place.geometry.location.lng();
				/*
				var Gisborne = new google.maps.LatLng(
					lat,
					lng,
				);
				var mapProp = {
		                  center:Gisborne,
		                  zoom:13,
		                  scrollwheel: false,
		                  mapTypeId:google.maps.MapTypeId.ROADMAP
		            };  
		          
		        	var map = new google.maps.Map($('.wp-mappicker-map-canvas')[0],mapProp);
					
					var marker=new google.maps.Marker({
		                  position:Gisborne,
						  draggable: true
		            });
		        	marker.setMap(map);*/
                console.log(picker);
            });
		});
	</script>
	
<?php } );
