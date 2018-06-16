<?php

/*
Plugin Name: GitUpdate Test
Plugin URI: https://github.com/mcguffin/gitupdate-test
Description: Enter description here.
Author: Jörn Lund
Version: 1.1.32
Author URI: https://github.com/mcguffin
License: GPL3
Github Repository: mcguffin/gitupdate-test

Text Domain: gitupdate-test
Domain Path: /languages/
*/

/*  Copyright 2017  Jörn Lund

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin was generated by WP Plugin Scaffold
https://github.com/mcguffin/wp-plugin-scaffold
Command line args were: `"GitUpdate Test" git`
*/


namespace GitupdateTest;

define( 'GITUPDATE_TEST_FILE', __FILE__ );
define( 'GITUPDATE_TEST_DIRECTORY', plugin_dir_path(__FILE__) );

require_once GITUPDATE_TEST_DIRECTORY . 'include/autoload.php';

Core\Core::instance();





if ( is_admin() || defined( 'DOING_AJAX' ) ) {
	/*
	AutoUpdate\AutoUpdatePodpirate::instance()->set_type('plugin')->init( __FILE__ );
	/*/
	AutoUpdate\AutoUpdateGithub::instance()->init( __FILE__ );
	//*/
}
