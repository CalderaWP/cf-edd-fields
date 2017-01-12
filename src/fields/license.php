<?php
/**
 * EDD SL License Field
 *
 * @package CF_EDD_Fields
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\cfeddfields\fields;


use calderawp\cfeddfields\licenses\query;

class license {

	/**
	 * Get all licensed add-ons for a user
	 *
	 * @param null|int $user_id Optional. User ID, current user ID if mull
	 * @param bool $include_expired Optional. If false the default, expired licenses will be skipped.
	 *
	 * @return bool|array Array of download_id => download title or false if none found.
	 */
	public  static function get_downloads_by_licensed_user( $user_id = null, $include_expired = false ) {
		if ( is_null( $user_id ) ){
			$user_id = get_current_user_id();
		}

		global  $wpdb;
		$licensed_downloads = ( new query( $user_id, $include_expired, $wpdb ) )->get_downloads();

		return $licensed_downloads;

	}

}