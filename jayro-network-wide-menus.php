<?php

/**
 * Plugin Name: Network-Wide Menus
 * Plugin URI: https://github.com/jasonroman/network-wide-menus
 * Description: Implements one or more network wide menus taken from menus in the main site of your network
 * Version: 1.0
 * Author: Jason Roman
 * Author URI: http://jayroman.com
 * Network: true
 * 
 * Modified From: Ron Ronnick's Network Wide Menus
 *   @link http://wpmututorials.com/plugins/networkwide-menu/
 *   @link https://github.com/rrennick/network-wide-menu
 */

/**
 * On each menu load, check to see if it should load the network menu instead
 * 
 * @param mixed $content the menu content 
 * @param mixed $args the menu arguments
 * @return mixed $content the menu content
 */
function jayro_wp_nav_menu_filter( $content, $args )
{
    // the index of menus to be network-wide; defaults to the first 10 menus
    // modify this as needed; future improvement could use an interface for setting and storing these
    $network_menu_indices = range( 0, 9 );

	// retrieve all registered menus in the theme
	$registered_menus = get_registered_nav_menus();

	// search the registered menus for this particular menu and get its index if it exists
	$index = array_search( $args->theme_location, array_keys( $registered_menus ) );

	// if the menu exists, check the index of this menu against our specified network-wide menu indices
	// if the menu does not exist or no match is found, simply return the menu content from the existing network site
	if ( $index === false || !in_array( $index, $network_menu_indices ) ) {
		return $content;
    }

	// set a unique name for each site option by using the menu index value
	$menu_option_name = 'jayro_network_menus_'.$index;

	// if this is not the main site, try to get the network menu at this index and return it
	if ( !is_main_site() )
	{
		$network_menu = get_site_option( $menu_option_name );

		if ( !empty( $network_menu ) ) {
			return $network_menu;
		}
	}
	// otherwise this is the main site, so check if the option has been created yet and if not, create it
	elseif ( !get_option( $menu_option_name ) )
	{
		// add/update the option (the value doesn't matter) and update the site option with the menu content
		update_option( $menu_option_name, '1' );
		update_site_option( $menu_option_name, $content );
	}

	// return the menu	
	return $content;
}

// add a filter to run this function when each menu loads
add_filter( 'wp_nav_menu_objects', 'jayro_wp_nav_menu_filter', 999, 2 );

/**
 * Flush all existing network menus in the cache when saving a navigation menu
 * 
 * @param integer $post_id the post id
 * @param mixed $post the post content
 */
function jayro_wp_nav_menu_flush( $post_id, $post )
{
	// only flush if on the main site and saving a navigation menu
	if ( !(is_main_site() && $post->post_type == 'nav_menu_item' ) ) {
		return;
	}

	// loop through up to the first 10 network menus that were created and flush them
    // this coincides with the default 10 menus - change as needed
	for ( $i = 0; $i < 10; $i++ )
	{
        // set the option name to the menu index
		$menu_option_name = 'jayro_network_menus_'.$i;

		// make sure the option exists before flushing
		if ( get_option( $menu_option_name ) ) {
			update_option( $menu_option_name, '' );
		}
	}
}

// add an action to run this function when saving a post
add_action('save_post', 'jayro_wp_nav_menu_flush', 999, 2);