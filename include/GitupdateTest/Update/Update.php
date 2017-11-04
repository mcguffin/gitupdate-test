<?php

namespace GitupdateTest\Update;

use GitupdateTest\Core;

class Update extends Core\Singleton {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {
		add_filter( 'upgrader_pre_download', array( $this, 'upgrader_pre_download' ), 10, 3 );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_transient' ), 10, 3 );
		//var_dump(get_site_transient('update_plugins'));exit();
	}

	/**
	 *	@filter upgrader_pre_download
	 */
	public function upgrader_pre_download( $return, $package, $wp_upgrader ) {
		if ( false ) { // package is our plugin
			// $slug = ;
		}
		return $return;
	}

	public function pre_transient( $transient ) {
		
		$slug = plugin_basename( GITUPDATE_TEST_FILE );
		return $transient;

	}
}
