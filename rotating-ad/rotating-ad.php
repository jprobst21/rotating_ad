<?php
/*
Plugin Name: Rotating Ad Widget
Plugin URI: 
Description: Rotates a group of ads using a widget
Author: Josh Probst
Version: 0.0.1
Author URI: 
*/
/*  Copyright 2013  Josh Probst  (email : jprobst21@gmail.com )

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


define('ROTATING_AD_CORE_VERSION', '0.0.1');
define('ROTATING_AD_PLUGIN_NAME', 'rotating-ad');
define('ROTATING_AD_FILE', __FILE__);
define('ROTATING_AD_PLUGIN_BASENAME', plugin_basename( ROTATING_AD_FILE ) );
if (!defined('ROTATING_AD_CORE_DIR')){
  define('ROTATING_AD_CORE_DIR', WP_PLUGIN_DIR . '/rotating-ad' );
}
if ( !defined( 'ROTATING_AD_CORE_LIB' ) ) {
  define( 'ROTATING_AD_CORE_LIB', ROTATING_AD_CORE_DIR . '/lib' );
}
if ( !defined( 'ROTATING_AD_CORE_URL' ) ) {
  define( 'ROTATING_AD_CORE_URL', WP_PLUGIN_URL . '/rotating-ad' );
}
#require_once( ROTATING_AD_CORE_LIB . '/constants.php' ); 
require_once( ROTATING_AD_CORE_LIB . '/wp-init.php');
