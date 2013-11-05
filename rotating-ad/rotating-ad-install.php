<?php
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
		size varchar(10) NOT NULL DEFAULT '200x200',
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

	$rows_affected = $wpdb->insert( $table2_name, array( 'name' => 'Default', 'size' => '250x250' ) );
}


?>