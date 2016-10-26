<?php
/**
 *	Kalium WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

// This will enqueue style.css of child theme
add_action( 'wp_enqueue_scripts', 'enqueue_childtheme_scripts', 100 );

function enqueue_childtheme_scripts() {
	wp_enqueue_style( 'kalium-child', get_stylesheet_directory_uri() . '/style.css' );
}