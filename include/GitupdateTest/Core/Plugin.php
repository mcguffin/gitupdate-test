<?php

namespace GitupdateTest\Core;

use GitupdateTest\PostType;
use GitupdateTest\Compat;

class Plugin extends Singleton {

	private static $components = array(
	);

	/**
	 *	Private constructor
	 */
	protected function __construct() {

		register_activation_hook( GITUPDATE_TEST_FILE, array( __CLASS__ , 'activate' ) );
		register_deactivation_hook( GITUPDATE_TEST_FILE, array( __CLASS__ , 'deactivate' ) );
		register_uninstall_hook( GITUPDATE_TEST_FILE, array( __CLASS__ , 'uninstall' ) );

		add_action( 'wp_upgrade', array( $this, 'maybe_upgrade' ) );

		parent::__construct();
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
