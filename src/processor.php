<?php
/**
 * EDD SL License Processor
 *
 * @package CF_EDD
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\cfeddfields;


use calderawp\cfeddfields\fields\license;

class processor extends \Caldera_Forms_Processor_Processor {


	/**
	 * @inheritdoc
	 */
	public function pre_processor( array $config, array $form, $proccesid ) {
		$value = \Caldera_Forms::get_field_data( $config[ 'edd_licensed_downloads' ], $form );
		$_user = \Caldera_Forms::do_magic_tags( $config[  'edd_licensed_downloads_user'] );
		if ( 0 < absint( $_user ) ) {
			$user = $_user;
		}else{
			$user = get_current_user_id();
		}

		$downloads = license::get_downloads_by_licensed_user( $user );

		if ( ! in_array( $value, array_keys( $downloads ) ) ) {
			return array(
				'type'=>'error',
				'note' => var_export( [ 'd' => $downloads, 'v' => $value ] , true )
			);
		}
	}


	public function processor( array $config, array $form, $proccesid ) {}


}
