<?php
/**
 *	@package GitupdateTest\Compat
 *	@version 1.0.0
 *	2018-09-22
 */

namespace GitupdateTest\Compat;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}


use GitupdateTest\Core;


class WPMU extends Core\PluginComponent {

	protected function __construct() {
	}

	/**
	 *	@inheritdoc
	 */
	 public function activate(){

	 }

	 /**
	  *	@inheritdoc
	  */
	 public function deactivate(){

	 }

	 /**
	  *	@inheritdoc
	  */
	 public static function uninstall() {
		 // remove content and settings
	 }

	/**
 	 *	@inheritdoc
	 */
	public function upgrade( $new_version, $old_version ) {
	}

}
