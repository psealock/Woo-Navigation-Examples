<?php
/**
 * Plugin Name: dev-blog
 *
 * @package WooCommerce\Admin
 */

use Automattic\WooCommerce\Admin\Features\Navigation\Menu;
use Automattic\WooCommerce\Admin\Features\Navigation\Screen;

/**
 * Register the navigation items in the WooCommerce navigation.
 */
function register_navigation_items() {
	if (
		! class_exists( '\Automattic\WooCommerce\Admin\Features\Navigation\Menu' ) ||
		! class_exists( '\Automattic\WooCommerce\Admin\Features\Navigation\Screen' )
	) {
		return;
	}

	function page_content(){
		echo '<div class="wrap"><h2>Testing</h2></div>';
	}

	add_menu_page( 'My Extension', 'My Extension', 'manage_woocommerce', 'my-extension-slug', 'page_content' );
	

	Menu::add_plugin_item(
		array(
			'id'         => 'my-extension',
			'title'      => __( 'My Extension', 'my-extension' ),
			'capability'    => 'manage_woocommerce',
			'url'        => 'my-extension-slug',
		)
	);

	Menu::add_plugin_category(
		array(
			'id'         => 'my-extension-category',
			'title'      => __( 'My Extension Category', 'my-extension' ),
			'parent' => 'woocommerce',
		)
	);

	Menu::add_plugin_item(
		array(
			'id'         => 'my-extension-cat-page',
			'title'      => __( 'My Extension Cat Page', 'my-extension' ),
			'capability'    => 'manage_woocommerce',
			'url'        => 'my-extension-slug-cat-page',
			'parent' => 'my-extension-category',
		)
	);

	register_post_type( 'my-post-type', array(
		'label'        => 'My Post Type',
		'public'       => true,
		'show_in_menu' => true,
	) );

	Screen::register_post_type( 'my-post-type' );

	$post_type_items = Menu::get_post_type_items(
		'my-post-type',
		array(
			'title' => __( 'My Extension Post Type', 'my-extension' ),
			'parent' => 'my-extension-category',
		)
	);

	Menu::add_plugin_item( $post_type_items['all'] );
}

// Register menu items in the new WooCommerce navigation.
add_action( 'admin_menu', 'register_navigation_items' );

/**
 * Register the JS.
 */
function add_extension_register_script() {
	if ( ! class_exists( 'Automattic\WooCommerce\Admin\Loader' ) || ! \Automattic\WooCommerce\Admin\Loader::is_admin_or_embed_page() ) {
		return;
	}
	
	$script_path       = '/build/index.js';
	$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
	$script_asset      = file_exists( $script_asset_path )
		? require( $script_asset_path )
		: array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
	$script_url = plugins_url( $script_path, __FILE__ );

	wp_register_script(
		'dev-blog',
		$script_url,
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_enqueue_script( 'dev-blog' );
	wp_enqueue_style( 'dev-blog' );
}

add_action( 'admin_enqueue_scripts', 'add_extension_register_script' );
