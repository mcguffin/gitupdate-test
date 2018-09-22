<?php

namespace GitupdateTest\Core;

use GitupdateTest\Compat;

class Core extends Plugin {

	/**
	 *	Private constructor
	 */
	protected function __construct() {
		add_action( 'plugins_loaded' , array( $this , 'load_textdomain' ) );
		add_action( 'plugins_loaded' , array( $this , 'init_compat' ), 0 );
		add_action( 'init' , array( $this , 'init' ) );
		add_action( 'wp_enqueue_scripts' , array( $this , 'wp_enqueue_style' ) );

		$args = func_get_args();
		parent::__construct( ...$args );

		add_filter('is_plugin_active_' . $this->get_slug(), '__return_true' );

	}


	/**
	 *	Load frontend styles and scripts
	 *
	 *	@action wp_enqueue_scripts
	 */
	public function wp_enqueue_style() {
	}

	 /**
 	 *	Load Compatibility classes
 	 *
 	 *  @action plugins_loaded
 	 */
 	public function init_compat() {

 		if ( is_multisite() && is_plugin_active_for_network( $this->get_wp_plugin() ) ) {
 			Compat\WPMU::instance();
 		}
 		if ( function_exists('\acf') && version_compare( acf()->version,'5.0.0','>=') ) {
 			Compat\ACF::instance();
 		}
 		if ( defined('POLYLANG_VERSION') && version_compare( POLYLANG_VERSION, '1.0.0', '>=' ) ) {
 			Compat\Polylang::instance();
 		}
 	}



	/**
	 *	Load text domain
	 *
	 *  @action plugins_loaded
	 */
	public function load_textdomain() {
		$path = pathinfo( $this->get_plugin_file(), PATHINFO_FILENAME );
		load_plugin_textdomain( 'gitupdate-test' , false, $path . '/languages' );
	}

	/**
	 *	Init hook.
	 *
	 *  @action init
	 */
	public function init() {
	}

	/**
	 *	Get asset url for this plugin
	 *
	 *	@param	string	$asset	URL part relative to plugin class
	 *	@return wp_enqueue_editor
	 */
	public function get_asset_url( $asset ) {
		return plugins_url( $asset, $this->get_plugin_file() );
	}



}
