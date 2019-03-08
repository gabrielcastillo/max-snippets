<?php

/**
 * Fired during plugin activation
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/includes
 * @author     Gabriel Castillo <gabriel@stellervision.com>
 */
class Max_Sher_Snippets_Activator {

	protected static $db_version = 1;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$current_db_version = get_option('max_sher_snippet_db_version');

		//Check if plugin has been activated before.
		if ( ! $current_db_version ) {
			$current_db_version = 0;
		}
		
		if ( intval($current_db_version) < Max_Sher_Snippets_Activator::$db_version ) {
			if ( Max_Sher_Snippets_Activator::create_or_upgrade_db() ) {
				update_option('max_sher_snippet_db_version', Max_Sher_Snippets_Activator::$db_version);
			}
		}
	}

	public static function create_or_upgrade_db()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'max_snippets';

		$charset_collate = '';

		if ( ! empty($wpdb->charset) ) {
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty($wpdb->collate) ) {
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}

		$sql = "CREATE TABLE " . $table_name . "("
		 . "id mediumint(9) NOT NULL AUTO_INCREMENT, "
		 . "post_author int(10) NOT NULL, "
         . "snippet_title varchar(200) NOT NULL, "
		 . "snippet_text text NOT NULL, "
		 . "snippet_excerpt text NOT NULL, "
         . "created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
         . "updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
         . "UNIQUE KEY id (id)"
         . ")" . $charset_collate. ";";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($sql);
		return true;
	}

}
