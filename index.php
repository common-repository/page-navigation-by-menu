<?php
/**
 * Plugin Name
 *
 * @package           Page navigation by menu
 * @author            Lovro Hrust <lovro@makeiteasy.hr>
 * @copyright         2022 Lovro Hrust
 * @license           Mozilla Public License Version 2.0
 *
 * @wordpress-plugin
 * Plugin Name:       Page navigation by menu
 * Plugin URI:
 * Description:       Output navigation
 * Version:           1.0.1
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            Lovro Hrust
 * Author URI:        https://makeiteasy.hr
 * Text Domain:       page-navigation
 * License:           Mozilla Public License Version 2.0
 * License URI:       https://www.mozilla.org/en-US/MPL/2.0/
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( 'No direct call!', 'Script kiddies, ey?' );
}

require 'internal.php';

/**
 * Public part
 */

/**
 * Return links to previous and next page in menu
 *
 * @param int|string|WP_Term $menu menu ID, slug or object.
 * @param integer            $start_position Start position in menu from where page navigation begins.
 * @param integer            $end_position   End position in menu from where page navigation ends.
 * @return string resulting HTML
 */
function get_the_pages_navigation( $menu, $start_position = 0, $end_position = 0 ) {
	if ( $menu ) {
		page_nav_by_menu\page_nav_initialization( $menu );
	}

	return '<nav class="page-navigation">' .
		page_nav_by_menu\get_page_navigation_verbose( true, $start_position, $end_position ) .
		page_nav_by_menu\get_page_navigation_verbose( false, $start_position, $end_position ) .
	'</nav>';
}

/**
 * Echo version of function
 *
 * @param int|string|WP_Term $menu menu ID, slug or object.
 * @param integer            $start_position Start position in menu from where page navigation begins.
 * @param integer            $end_position   End position in menu from where page navigation ends.
 * @return void
 */
function the_pages_navigation( $menu, $start_position = 0, $end_position = 0 ) {
	// Necessary parts are already escaped inside internal.php.
	// phpcs:ignore
	echo get_the_pages_navigation( $menu, $start_position, $end_position );
}