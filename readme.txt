=== Page navigation by menu ===
Contributors: lovor
Tags: navigation, page, menu, next, previous
Requires at least: 4.9
Tested up to: 6.5.4
Requires PHP: 5.6
Stable tag: 1.0.1
License: Mozilla Public License Version 2.0
License URI: https://www.mozilla.org/en-US/MPL/2.0

Create navigation to next/previous pages similar to navigation for posts. Previous and next pages are determined from menu.

== Description ==
Plugin to support navigation by pages - similar to built in function the_posts_navigation() for posts.
Pages in navigation are filtered by provided menu (Primary menu by default) and sorted in the same order as in menu.
By default, main menu is used, but different menu could be used by calling function for displaying with different menu as parameter.

### Disclaimer!
This plugin works only with classic themes. With FSE themes it will not work, since it depends on classic menus.

== Installation ==
Standard installation procedure - from repository or downloaded zip file. Plugin does not have settings or admin part.
It does not store any information in database nor in file system.
Use hooks in code for customizing.

== Changelog ==

= 1.0.1 =
* Fix PHP errors if FSE theme is active

= 1.0.0 =
* Initial version

== Upgrade Notice ==

= 1.0.0 =
No upgrade notices.

== Usage ==

= Basic =

Just install and call `the_pages_navigation()` in your php template. There is also a `get_the_pages_navigation()` function which returns string with same content.
Arguments to these functions could be supplied, all optional.
* $menu - a menu ID, slug, name, or object (WP_Term) for which to show pages
* $start_position - start position from which position in menu to start navigation
? $end_position - to which position in menu to show navigation

= Advanced =

There are filters that can help to customize plugin output.

Change menu filter

`apply_filters('page_nav_menu', $default_menu)`

Filters menu used for navigation.

* $default_menu - a menu ID, slug, name, or object (WP_Term)


Arrow filters

`apply_filters( 'page_nav_left_arrow', $HTML )
apply_filters( 'page_nav_right_arrow', $HTML )`

Filters arrow output.

* $HTML - HTML of arrow, coded as SVG, img or something else.


Filter output

`apply_filters( 'arrow_wrapper', $HTML_output, $left, $enabled, $output_image, $output_link, $output_div, $output_text )`

Filters whole HTML output.

* $HTML_output - as the name says
* $left - output is for left side (previous)
* $enabled - navigation on that side is enabled
* $output_image - html of arrow image
* $output_link - URL of link to which arrow leads
* $output_div - wrapper of text besides arrow
* $output_text - text besides arrow


= Examples of filtering =

`add_filter( 'page_nav_left_arrow', function() {
	return '<svg width="53" height="32" viewBox="0 0 53 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M51.77 15.994H1M16.622 31L1 15.998 16.622 1v30z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
} );`