<?php
/**
 * Upgrades the db
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Noptin_Install Class.
 */
class Noptin_Install {

	public $charset_collate;

	public $table_prefix;

	/**
	 * Install Noptin
	 */
	public function __construct( $upgrade_from ) {
		global $wpdb;

        //Abort if this is MS and the blog is not installed
		if ( ! is_blog_installed() ) {
			return;
		}

		$this->charset_collate = $wpdb->get_charset_collate();
		$this->table_prefix    = $wpdb->prefix;

		//Force update the subscribers table
		if( false === $upgrade_from ){
			$this->force_update_subscribers_table();
		}

        //If this is a fresh install
		if( !$upgrade_from ){
			$this->do_full_install();
		}

		//Upgrading from version 1
		if( 1 == $upgrade_from ){
			$this->upgrade_from_1();
		}

	}

	/**
	 * Force update the subscribers table
	 */
	private function force_update_subscribers_table(){
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta( array( $this->get_subscribers_table_schema() ) );
	}

	/**
	 * Returns the subscribers table schema
	 */
	private function get_subscribers_table_schema() {

		$table = $this->table_prefix . 'noptin_subscribers';
		$charset_collate = $this->charset_collate;

		return "CREATE TABLE IF NOT EXISTS $table
			(id bigint(20) unsigned NOT NULL auto_increment,
            first_name varchar(60) NOT NULL default '',
            second_name varchar(60) NOT NULL default '',
            email varchar(100) NOT NULL default '',
            active tinyint(2) NOT NULL DEFAULT '0',
            confirm_key varchar(50) NOT NULL default '',
            confirmed tinyint(2) NOT NULL DEFAULT '0',
            date_created DATE NOT NULL DEFAULT '0000-00-00',
			PRIMARY KEY  (id),
			KEY email (email)) $charset_collate";

	}

	/**
	 * Returns the subscriber meta table schema
	 */
	private function get_subscriber_meta_table_schema() {

		$table = $this->table_prefix . 'noptin_subscriber_meta';
		$charset_collate = $this->charset_collate;

		return "CREATE TABLE IF NOT EXISTS $table
			(meta_id bigint(20) unsigned NOT NULL auto_increment,
			noptin_subscriber_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY noptin_subscriber_id (noptin_subscriber_id),
			KEY meta_key (meta_key(191))) $charset_collate";

	}

	/**
	 * Upgrades the db from version 1 to 2
	 */
	private function upgrade_from_1() {
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$table = $this->table_prefix . 'noptin_subscribers';

		$wpdb->query("ALTER TABLE $table ADD active tinyint(2)  NOT NULL DEFAULT '0'");
		$wpdb->query("ALTER TABLE $table ADD date_created  DATE");

		//Had not been implemented
		$wpdb->query("ALTER TABLE $table DROP COLUMN source");

		//Not really helpful
		$wpdb->query("ALTER TABLE $table DROP COLUMN time");

        dbDelta( array( $this->get_subscriber_meta_table_schema() ) );
	}

	/**
	 * Does a full install of the plugin.
	 */
	private function do_full_install() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		//Create the subscriber and subscriber meta table
		dbDelta( array( $this->get_subscribers_table_schema() ) );
		dbDelta( array( $this->get_subscriber_meta_table_schema() ) );

	}


}
