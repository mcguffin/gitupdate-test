<?php

namespace GitupdateTest\Core;

use GitupdateTest\PostType;
use GitupdateTest\Compat;

class Plugin extends Singleton {

	/** @var string plugin main file */
	private $plugin_file;

	/** @var array metadata from plugin file */
	private $plugin_meta;

	/** @var string plugin components which might need upgrade */
	private static $components = array(
	);

	/**
	 *	Private constructor
	 */
	protected function __construct( $file ) {

		$this->plugin_file = $file;

		register_activation_hook( $this->get_plugin_file(), array( __CLASS__ , 'activate' ) );
		register_deactivation_hook( $this->get_plugin_file(), array( __CLASS__ , 'deactivate' ) );
		register_uninstall_hook( $this->get_plugin_file(), array( __CLASS__ , 'uninstall' ) );

		add_action( 'wp_upgrade', array( $this, 'maybe_upgrade' ) );

		parent::__construct();
	}

	/**
	 *	@return string full plugin file path
	 */
	final public function get_plugin_file() {
		return $this->plugin_file;
	}

	/**
	 *	@return string Path to the main plugin file from plugins directory
	 */
	final public function get_wp_plugin() {
		return str_replace( trailingslashit( WP_PLUGIN_DIR ), '', $this->plugin_file );
	}

	/**
	 *	@return string full plugin file path
	 */
	final public function get_plugin_dir() {
 		return plugin_dir_path( $this->get_plugin_file() );
 	}

	/**
	 *	@return string current plugin version
	 */
	final public function get_version() {
		return $this->get_plugin_meta( 'Version' );
	}

	/**
	 *	@return string current plugin version
	 */
	final public function get_slug() {
		return basename( $this->get_plugin_dir() );
	}

	/**
	 *	@param string $which Which plugin meta to get. NUll
	 *	@return string|array plugin meta
	 */
	final public function get_plugin_meta( $which = null ) {
		if ( ! isset( $this->plugin_meta ) ) {
			$this->plugin_meta = get_plugin_data( $this->plugin_file() );
		}
		if ( isset( $this->plugin_meta[ $which ] ) ) {
			return $this->plugin_meta[ $which ];
		}
		return $this->plugin_meta;
	}

	/**
	 *	@action plugins_loaded
	 */
	public function maybe_upgrade() {
		// trigger upgrade
		$meta = get_plugin_data( GITUPDATE_TEST_FILE );
		$new_version = $meta['Version'];
		$old_version = get_option( 'gitupdate_test_version' );

		// call upgrade
		if ( version_compare($new_version, $old_version, '>' ) ) {

			$this->upgrade( $new_version, $old_version );

			update_option( 'gitupdate_test_version', $new_version );

		}

	}

	/**
	 *	Fired on plugin activation
	 */
	public static function activate() {

		foreach ( self::$components as $component ) {
			$comp = $component::instance();
			$comp->activate();
		}

	}


	/**
	 *	Fired on plugin updgrade
	 *
	 *	@param string $nev_version
	 *	@param string $old_version
	 *	@return array(
	 *		'success' => bool,
	 *		'messages' => array,
	 * )
	 */
	public function upgrade( $new_version, $old_version ) {

		$result = array(
			'success'	=> true,
			'messages'	=> array(),
		);

		foreach ( self::$components as $component ) {
			$comp = $component::instance();
			$upgrade_result = $comp->upgrade( $new_version, $old_version );
			$result['success'] 		&= $upgrade_result['success'];
			$result['messages'][]	=  $upgrade_result['message'];
		}

		return $result;
	}

	/**
	 *	Fired on plugin deactivation
	 */
	public static function deactivate() {
		foreach ( self::$components as $component ) {
			$comp = $component::instance();
			$comp->deactivate();
		}
	}

	/**
	 *	Fired on plugin deinstallation
	 */
	public static function uninstall() {
		foreach ( self::$components as $component ) {
			$comp = $component::instance();
			$comp->unistall();
		}
	}

}
