<?php
/*
Plugin Name: Rotating Ad Widget
Plugin URI: 
Description: Rotates a group of ads using a Widget
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

global $ra_db_version;
$ra_db_version = "0.0.1";

function ra_install(){
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_name = $wpdb->prefix . "rotating_ad"; 

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		image varchar(200) NOT NULL DEFAULT '',
		link varchar(200) NOT NULL DEFAULT '',
		group_id tinyint(2) NOT NULL DEFAULT 1,
		PRIMARY KEY  (id)
	);";
	dbDelta($sql);


	$table2_name = $wpdb->prefix . "rotating_ad_groups";

	$sql2 = "CREATE TABLE $table2_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		name varchar(50) NOT NULL DEFAULT '',
		width int(11) NOT NULL DEFAULT '200',
		height int(11) NOT NULL DEFAULT '200',
		PRIMARY KEY  (id)
	);";

	dbDelta($sql2);

	add_option( "ra_db_version", $ra_db_version );


	$installed_ver = get_option( "ra_db_version" );

	if( $installed_ver != $ra_db_version ) {

	  	/****************

	  	Use if upgrading db

	  	******************/

	  	update_option( "ra_db_version", $ra_db_version );
	}
	
}

function ra_install_data(){
	global $wpdb;

	$table2_name = $wpdb->prefix . "rotating_ad_groups";

	$rows_affected = $wpdb->insert( $table2_name, array( 'name' => 'Default', 'width' => '200', 'height' => '200' ) );
}


?>