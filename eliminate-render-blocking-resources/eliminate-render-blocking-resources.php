<?php
/*
 * Plugin Name: Eliminate Render Blocking Resources
 * Version: 0.1
 * Description: Eliminate Render Blocking Resources.
 * Author: Catur Pamungkas
*/

// Defer JS
function defer_parsing_of_js( $url, $handle  ) {
	if ( is_user_logged_in() || is_admin() ) return $url; //don't break WP Admin
	if ( is_page('Home') ) {
		// add script handles to the array below
		$scripts = array(
			'magnific-popup-js',
			'clipboard-script',
			'child-script'
		);
		foreach ( $scripts as $script ) {
			if ( $script === $handle ) {
				return str_replace( " src",  " defer src", $url );
			}
		}
		return $url;
	}
	else
	{
		return $url;
	}
}
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10, 2 );

// Async JS
function async_parsing_of_js( $url, $handle  ) {
	if ( is_user_logged_in() ) return $url; //don't break WP Admin
	if ( is_page('Home') ) {
		// add script handles to the array below
		$scripts = array(
			'rtoc_js',
			'rtoc_js_scroll',
			'picturefill',
			'stripe-handler-ng',
			'wpcf7-redirect-script',
			'magnific-popup-au',
			'boot2',
			'boot3',
			'DV_coreScript',
			'custom-script',
			'js-cookie',
			'cookie-js',
			'underscore',
			'wp-util',
			'wp_review-js',
			'wp-embed',
			'splash-popup-script'
		);
		foreach ( $scripts as $script ) {
			if ( $script === $handle ) {
				return str_replace( " type='text/javascript' src", " async src", $url );
			}
		}
		return $url;
	}
	else
	{
		return $url;
	}
}
add_filter( 'script_loader_tag', 'async_parsing_of_js', 10, 2 );

// Load CSS Asynchronously

// Replace media='' and add stylesheet attributes
function add_stylesheet_attributes_media( $html, $handle ) {
	if ( is_page('Home') ) {
		// add style handles to the array below
		$styles = array(
			'fonts',
			'jquery-ui-css',
			'fonts-philosopher',
			'child-style',
			'zuckmin-style',
			'DV_coreStyle',
			'child-index-style'
		);

		foreach ( $styles as $style ) {
			if ( $style === $handle ) {
				return str_replace( "media=''", "media=\"print\" onload=\"this.media='all'\"", $html );
			}
		}

		return $html;
	}
	else {
		return $html;
	}
}
add_filter( 'style_loader_tag', 'add_stylesheet_attributes_media', 10, 2 );

// Replace media='all' and add stylesheet attributes
function add_stylesheet_attributes_media_all( $html, $handle ) {
	if ( is_page('Home') ) {
		// add style handles to the array below
		$styles = array(
			'dashicons',
			'splash-popup-bootstrap-style',
			'wp-block-library',
			'magnific-popup-style',
			'stripe-handler-ng-style',
			'wpcf7-redirect-script-frontend',
			'magnific-popup-au',
			'youtube-channel',
			'wp-pagenavi',
			'parent-style',
			'custom-style',
			'jquery-lazyloadxt-spinner-css',
			'addthis_all_pages',
			'contact-form-7',
			'font-style',
			'a3a3_lazy_load'
		);

		foreach ( $styles as $style ) {
			if ( $style === $handle ) {
				return str_replace( "media='all'", "media=\"print\" onload=\"this.media='all'\"", $html );
			}
		}

		return $html;
	}
	else {
		return $html;
	}
}
add_filter( 'style_loader_tag', 'add_stylesheet_attributes_media_all', 10, 2 );