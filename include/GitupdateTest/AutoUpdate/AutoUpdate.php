<?php

namespace GitupdateTest\AutoUpdate;

use GitupdateTest\Core;

class AutoUpdate extends Core\Singleton {

	private $github_repo = null;

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {
		add_filter( 'upgrader_pre_download', array( $this, 'upgrader_pre_download' ), 10, 3 );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_transient' ), 10, 3 );
		//var_dump(get_site_transient('update_plugins'));exit();
		// https://api.github.com/repos/mcguffin/acf-quick-edit-fields/releases/latest
		add_action('admin_init', array( $this, 'admin_init') );
		//var_dump(get_site_transient('update_plugins'));exit();
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

	public function pre_set_transient( $transient ) {

		if ( ! is_object( $transient ) || ! isset( $transient->response ) ) {
			return $transient;
		}

		// get own version
		if ( $release_info = $this->github_release_info() ) {
			$plugin 		= plugin_basename( GITUPDATE_TEST_FILE );
			$slug			= basename(GITUPDATE_TEST_DIRECTORY);
			$plugin_info	= get_plugin_data( GITUPDATE_TEST_FILE );
			$remote_version = $this->format_version_number( $release_info->tag_name );
			if ( version_compare($remote_version,$plugin_info['Version'],'>') ) {
				$transient->response[ $plugin ] = (object) array(
					'id'			=> sprintf( 'github.com/%s', $this->get_github_repo() ),
					'slug'			=> $slug,
					'plugin'		=> $plugin,
					'new_version'	=> $remote_version,
					'url'			=> $plugin_info['PluginURI'],
					'package'		=> $release_info->zipball_url,
					'icons'			=> array(),
					'banners'		=> array(),
					'banners_rtl'	=> array(),
					'tested'		=> '4.8.1',
					'compatibility'	=> array(),
				);
				if ( isset( $transient->no_update ) && isset( $transient->no_update[$plugin] ) ) {
					unset( $transient->no_update[$plugin] );
				}
			}
		}

		return $transient;

	}
	private function format_version_number( $version ) {
		return preg_replace('/^([^0-9]+)/ims','',$version);
	}

	private function github_release_info( ) {
		if ( $release_info_url = $this->get_release_info_url() ) {
			$response = wp_remote_get( $release_info_url, array() );
			if ( ! is_wp_error( $response ) ) {
				$release_info = json_decode( wp_remote_retrieve_body( $response ) );
				return $release_info;
			}
		}
		return false;
	}


	private function get_github_repo() {
		if ( is_null( $this->github_repo ) ) {
			$this->github_repo = false;
			$data = get_file_data( GITUPDATE_TEST_FILE, array('GithubRepo'=>'Github Repository') );
			if ( ! empty( $data['GithubRepo'] ) ) {
				$this->github_repo = $data['GithubRepo'];
			}
		}
		return $this->github_repo;

	}

	private function get_release_info_url() {
		$url = false;
		if ( $repo = $this->get_github_repo() ) {
			$url = sprintf('https://api.github.com/repos/%s/releases/latest', $repo );
		}
		return $url;
	}

}
