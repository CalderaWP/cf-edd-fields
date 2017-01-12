<?php
/**
 * EDD SL License DB querier
 *
 * @package CF_EDD_Fields
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\cfeddfields\licenses;


class query {

	/**
	 * @var \wpdb
	 */
	protected $wpdb;

	/**
	 * @var int|null
	 */
	protected $user_id;

	/**
	 * @var bool
	 */
	protected $include_expired;

	/**
	 * query constructor.
	 *
	 * @param null $user_id
	 * @param bool $include_expired
	 * @param \wpdb $wpdb
	 */
	public function __construct( $user_id = null, $include_expired = false, \wpdb $wpdb ) {
		if ( is_null( $user_id ) ){
			$user_id = get_current_user_id();
		}

		$this->user_id = $user_id;
		$this->include_expired = $include_expired;
		$this->wpdb = $wpdb;



	}

	/**
	 * Get licensed downloads as flat array ID => Post Title
	 *
	 * @return array
	 */
	public function get_downloads(){
		$licensed_downloads = [];
		$licenses = $this->get_license_ids();
		if ( ! empty( $licenses ) ) {
			foreach ( $licenses as $license  ) {
				$id = get_post_meta( $license[ 'post_id' ], '_edd_sl_download_id', true );
				if ( $id ) {
					$licensed_downloads[ $id ] = get_the_title( $id );
				}
			}
		}

		return $licensed_downloads;
	}

	/**
	 * Get licensed downloads with details
	 *
	 * @return array
	 */
	public function get_licenses(){
		$meta_keys = [
			'_edd_sl_download_id',
			'_edd_sl_payment_id',
			'_edd_sl_key',
			'_edd_sl_status'
		];
		$licenses = $this->get_license_ids();
		if(  ! empty( $licenses ) ){
			foreach ( $licenses as &$license ) {
				foreach ( $meta_keys as $key ){
					$license[ str_replace( '_edd_sl_', '', $key ) ] = get_post_meta( $license[ 'post_id' ], $key, true );
				}


			}
		}

		return $licenses;
	}


	protected function get_license_ids(){

	$query    = $this->wpdb->prepare( 'SELECT `post_id` FROM `%2s` WHERE `meta_value` = %d AND `meta_key` = "_edd_sl_user_id"', $this->wpdb->postmeta, $this->user_id );
	$licenses = $this->wpdb->get_results( $query, ARRAY_A );

	if ( ! empty( $licenses ) ) {
		foreach ( $licenses as $license ) {
			if ( ! $this->include_expired ) {
				$status = get_post_meta( $license[ 'post_id' ], '_edd_sl_status', true );
				if ( false == $status ) {
					continue;
				}

			}

		}

	}



		return $licenses;
	}
}