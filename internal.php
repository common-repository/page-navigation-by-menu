<?php
/**
 * Internal functions for plugin
 *
 * @author               Lovro Hrust <lovro@makeiteasy.hr>
 * @since                1.0.0
 * @copyright            2022 Lovro Hrust
 * @package              page-navigation-by-menu
 * @license              Mozilla Public License Version 2.0
 */

namespace page_nav_by_menu;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( 'No direct call!', 'Script kiddies, ey?' );
}

/**
 * Private part
 */


$mie_nav_menu_items   = array();
$mie_menu_items_count = 0;

add_action( 'the_post', 'page_nav_by_menu\page_nav_initialization_callback' );

/**
 * Initialize to defaults, so no parameters to calls are needed.
 *
 * @return void
 */
function page_nav_initialization_callback() {
	page_nav_initialization();
}

/**
 * Initialize, initialization values are used
 * in case if in later calls $menu is null
 *
 * @param int|string|WP_Term $menu menu ID, slug or object.
 * @return void
 */
function page_nav_initialization( $menu = null ) {
	global $mie_nav_menu_items,
			$mie_menu_items_count,
			$mie_current_page_menu_order;

	$menus                       = wp_get_nav_menus();
	$default_menu                = ( null !== $menu && '' !== $menu ) ?
	$menu :
	( $menus ? $menus[0] : null );
	$mie_nav_menu_items          = wp_get_nav_menu_items(
		apply_filters( 'page_nav_menu', $default_menu )
	);
	$isArray = is_array( $mie_nav_menu_items );
	$mie_menu_items_count        = $isArray ? count( $mie_nav_menu_items ) : 0;
	$mie_current_page_menu_order = $isArray ? current_order_num( $mie_nav_menu_items ) : 0;
}

// Define vars to avoid multiple calls to functions.
$mie_stylesheet_directory_uri_images = __DIR__ . '/assets/images/';


/**
 * Ordinal number of item in menu
 *
 * @param WP_Term[] $mie_nav_menu_items Array of menu items.
 * @return int
 */
function current_order_num( $mie_nav_menu_items ) {

	if ( ! $mie_nav_menu_items ) {
		return;
	}

	$found      = false;
	$current_id = get_the_id();
	foreach ( $mie_nav_menu_items as $nav_item ) {
		// Comparing of different types, ignore phpcs strict equality request.
		// phpcs:ignore
		if ( $nav_item->object_id == $current_id ) {
			$found = true;
			break;
		}
	}
	return $found ? $nav_item->menu_order : null;
}

/**
 * Insert navigation link; left or right
 *
 * @param boolean $left Is navigation for left or right part, i.e. previous - next? Left is in html before right.
 * @param integer $start_position Start position in menu from where page navigation begins.
 * @param integer $end_position   End position in menu from where page navigation ends.
 * @return string html for output
 */
function get_page_navigation_verbose( $left, $start_position = 0, $end_position = 0 ) {
	global $mie_stylesheet_directory_uri_images,
			$mie_nav_menu_items,
			$mie_current_page_menu_order,
			$mie_nav_menu_items,
			$mie_menu_items_count;

	if ( is_null( $mie_current_page_menu_order ) ) {
		return;
	}

	$enabled     = $left ? $mie_current_page_menu_order > $start_position + 1 : $mie_current_page_menu_order < $mie_menu_items_count - $end_position;
	$output_link = '';
	$output_text = '';

	// Array index starts from 0, menu_order starts from 1, hence -2 and 0.

	if ( $enabled ) {
		$nav_menu_item = $mie_nav_menu_items[ $left ? $mie_current_page_menu_order - 2 : $mie_current_page_menu_order ];
		$output_link   = 'href="' . esc_url( $nav_menu_item->url ) . '"';
		$output_text   = preg_replace( '/,(?=[^ ])/', ', ', wp_strip_all_tags( $nav_menu_item->title ) );
	};

	$arrow_left = apply_filters(
		'page_nav_left_arrow',
		'<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M2.06 19.2v1.5h38v-1.5z" color="#000"/><path d="M18.1 26.6l-18-6.63 18-6.63c-2.88 3.91-2.86 9.27 0 13.3z" color="#000" fill-rule="evenodd"/></svg>'
	);

	$arrow_right = apply_filters(
		'page_nav_right_arrow',
		'<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g color="#000"><path d="M38.04 19.2v1.5h-38v-1.5z"/><path d="M22 26.6l18-6.63-18-6.63c2.88 3.91 2.86 9.27 0 13.3z" fill-rule="evenodd"/></g></svg>'
	);

	$output_image = $left ? $arrow_left : $arrow_right;
	$output_div   = '<div class="nav-page-title">' . $output_text . '</div>';

	return apply_filters(
		'arrow_wrapper',
		'<div class="arrow ' . ( $left ? 'left' : 'right' ) . ( ! $enabled ? ' disabled' : '' ) . '">' .
		"<a $output_link>" .
			( $left ? ( $output_image . $output_div ) : ( $output_div . $output_image ) ) .
		'</a>' .
		'</div>',
		$left,
		$enabled,
		$output_image,
		$output_link,
		$output_div,
		$output_text
	);
}