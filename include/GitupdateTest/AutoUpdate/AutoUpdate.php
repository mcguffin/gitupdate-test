<?php

namespace GitupdateTest\AutoUpdate;

use GitupdateTest\Core;

abstract class AutoUpdate extends Core\Singleton {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_filter( 'upgrader_pre_download', array( $this, 'upgrader_pre_download' ), 10, 3 );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_transient' ), 10, 3 );

	}

	/**
	 *	@action admin_init
	 */
	public function admin_init() {

		//$this->pre_set_transient( get_site_transient('update_plugins') );
	}

	/**
	 *	@filter upgrader_pre_download
	 */
	public function upgrader_pre_download( $return, $package, $wp_upgrader ) {
		if ( false ) { // package is our plugin
			// $slug = ...;

		}
		return $return;
	}
	/**
	 *	@filter	pre_set_site_transient_update_plugins
	 */
	public function pre_set_transient( $transient ) {

		if ( ! is_object( $transient ) || ! isset( $transient->response ) ) {
			return $transient;
		}

		// get own version
		if ( $release_info = $this->get_release_info() ) {
			$plugin 		= plugin_basename( GITUPDATE_TEST_FILE );
			$slug			= basename(GITUPDATE_TEST_DIRECTORY);
			$plugin_info	= get_plugin_data( GITUPDATE_TEST_FILE );
			$remote_version = $this->format_version_number( $release_info->tag_name );

			if ( version_compare( $release_info['version'], $plugin_info['Version'] , '>' ) ) {
				$transient->response[ $plugin ] = (object) array(
					'id'			=> sprintf( 'github.com/%s', $this->get_github_repo() ),
					'slug'			=> $slug,
					'plugin'		=> $plugin,
					'new_version'	=> $remote_version,
					'url'			=> $plugin_info['PluginURI'],
					'package'		=> $release_info['download_url'],
					'icons'			=> array(),
					'banners'		=> array(),
					'banners_rtl'	=> array(),
					//'tested'		=> '',
					'compatibility'	=> array(),
				);
				if ( isset( $transient->no_update ) && isset( $transient->no_update[$plugin] ) ) {
					unset( $transient->no_update[$plugin] );
				}
			}
		}

		return $transient;
	}

	/**
	 *	Should return info for current release
	 *
	 *	@return array(
	 *		'version'		=> '...'
	 *		'download_url'	=> 'https://...'
	 *	)
	 */
	abstract function get_release_info();


}
