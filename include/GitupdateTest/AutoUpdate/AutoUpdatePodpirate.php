<?php

namespace GitupdateTest\AutoUpdate;

use GitupdateTest\Core;

class AutoUpdatePodpirate extends AutoUpdate {

	private $info_url = 'https://dl.podpirate.org/dl/%s/releaase.json?token=%s';

	/**
	 *	@inheritdoc
	 */
	public function get_remote_release_info() {
		if ( $release_info_url = $this->get_release_info_url() ) {

			$response = wp_remote_get( $release_info_url, array() );
			$slug = basename( GITUPDATE_TEST_DIRECTORY );

			if ( ! is_wp_error( $response ) ) {
				$release_info = json_decode( wp_remote_retrieve_body( $response ) );
				$release_info['download_link'] = add_query_arg('token',$this->get_access_token(),$release_info['download_link']);

				return $release_info;
			}
		}

		return false;
	}

	/**
	 *	@inheritdoc
	 */
	protected function get_plugin_sections() {
		$release_info = $this->get_release_info();
		return $release_info['sections'];
	}

	/**
	 *	@inheritdoc
	 */
	protected function get_plugin_banners() {
		return array();
	}


	/**
	 *	@return	string	access token
	 */
	private function get_access_token() {
		if ( defined( 'PODPIRATE_ACCESS_TOKEN' ) ) {
			return PODPIRATE_ACCESS_TOKEN;
		}
		if ( $token = get_option('podpirate_access_token') ) {
			return $token;
		}
		return apply_filters( 'podpirate_access_token', '' );
	}

	/**
	 *	@return	string	github api url
	 */
	private function get_release_info_url() {

		if ( $token = $this->get_access_token() ) {
			$slug = basename( GITUPDATE_TEST_DIRECTORY );
			return sprintf( $this->info_url, $slug, $token );
		}
		return false;
	}

}
